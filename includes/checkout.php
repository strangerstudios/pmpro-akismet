<?php
/**
 * All checkout/registration functionality.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use PMPro_Akismet\Akismet;

/**
 * Run spam check during checkout process
 * 
 * @since 1.0
 */
function pmpro_akismet_registration_checks( $continue ) {
    global $pmpro_akismet_extra_nonce;

    // Bail if another check already failed.
    if ( ! $continue ) {
        return $continue;
    }
    
    // If the user is logged in already during checkout, just bail. Let's assume they're ok.
    if ( is_user_logged_in() ) {
        return $continue;
    }

    // Check if Akismet is active, just bail if it's not active.
    if ( ! Akismet::is_active() ) {
        return $continue;
    }

    // Check if Akismet has a valid API key. Just bail if no API key is found.
    if ( ! Akismet::has_valid_key() ) {
        return $continue;
    }
 
    $data_to_check = array(
        'user_ip' => isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '',
        'user_agent' => isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '',
        'referrer' => isset( $_SERVER['HTTP_REFERER'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) : '',
        'blog' => get_option( 'home' ),
        'blog_lang' => get_locale(),
        'blog_charset' => get_option( 'blog_charset' ),
        'permalink' => get_permalink(),
        'comment_type' => 'signup',
        // phpcs:disable WordPress.Security.NonceVerification.Recommended -- Values are only sent to Akismet for spam scoring; core verifies pmpro_checkout_nonce before this filter on standard checkouts.
        'comment_author' => isset( $_REQUEST['username'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['username'] ) ) : '',
        'comment_author_email' => isset( $_REQUEST['bemail'] ) ? sanitize_email( wp_unslash( $_REQUEST['bemail'] ) ) : '',
        // phpcs:enable WordPress.Security.NonceVerification.Recommended
        'honeypot_field_name' => 'fullname'
    );
    
    // Allow filtering of data.
    $data_to_check = apply_filters( 'pmpro_akismet_data_to_check', $data_to_check ); // Filter to check the data.

    // Check to see if Akismet thinks it's spam or not.
    $is_spam = apply_filters( 'pmpro_akismet_checkout_is_spam', Akismet::is_spam( $data_to_check ) );

    // We are stricter with free levels. (Lower is stricter.)
    $level = pmpro_getLevelAtCheckout();
    if ( pmpro_isLevelFree( $level ) ) {
        $threshold = 1;
    } else {
        $threshold = 2;
    }

    // If an extra nonce was passed in, raise the threshold.
    if ( ! empty( $_REQUEST['pmpro_akismet_extra_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['pmpro_akismet_extra_nonce'] ) ), 'pmpro_akismet_extra_nonce' ) ) {
        $threshold = 2;

        // Update nonce in case they need to submit again.
        $pmpro_akismet_extra_nonce = wp_create_nonce( 'pmpro_akismet_extra_nonce' );
    }

    /**
     * Allow for filtering of the threshold. By default the threshold is 2 (blatant spam only) for paid levels and 1 (likely spam) for free levels.
     * @since [TBD]
     * @param int $threshold The threshold to determine if the user is spam or not.
     * @param array $data_to_check The data to check against Akismet.
     * @param int $level The level the user is signing up for.
     * @param int $is_spam The spam level returned by Akismet.
     * @return int The threshold to determine if the user is spam or not.
     */
    $threshold = apply_filters( 'pmpro_akismet_threshold', $threshold, $data_to_check, $level, $is_spam );

    if ( ! $is_spam ) {
        $continue = true;
    } else {
        // Always track as spam for the PMPro spam protection feature.
        if ( function_exists( 'pmpro_track_spam_activity' ) ) {
            pmpro_track_spam_activity();
        }
        
        // Stop checkout if above the threshold.
        if ( (int)$is_spam >= (int)$threshold ) {    
            $continue = false;
            pmpro_setMessage( esc_html__( 'Your username or email has been flagged as suspicious. Double check all fields below and submit again.', 'pmpro-akismet' ), 'pmpro_error' );

            // Set this global to enable the extra check.
            $pmpro_akismet_extra_nonce = wp_create_nonce( 'pmpro_akismet_extra_nonce' );
        }
    }

    return $continue;
}
add_filter( 'pmpro_registration_checks', 'pmpro_akismet_registration_checks', 10, 1 );

/**
 * Add an the extra hidden nonce to the checkout form if needed.
 */
function pmpro_akismet_add_extra_nonce() {
    global $pmpro_akismet_extra_nonce;

    if ( ! empty( $pmpro_akismet_extra_nonce ) ) {
        ?>
        <input type="hidden" name="pmpro_akismet_extra_nonce" value="<?php echo esc_attr( $pmpro_akismet_extra_nonce ); ?>" />
        <?php
    }
}
add_action( 'pmpro_checkout_before_submit_button', 'pmpro_akismet_add_extra_nonce' );

/**
 * Show Akismet notice on checkout page below the submit button based on Akismet privacy notice setting.
 * 
 * @since 1.0
 * 
 */
function pmpro_akismet_show_privacy_notice() {
  global $pmpro_akismet_extra_nonce;
  // Bail if Akismet show comment setting is set to 'hide'
	if ( 'display' !== apply_filters( 'pmpro_akismet_checkout_privacy_notice' , get_option( 'akismet_comment_form_privacy_notice', 'hide' ) ) ) {
		return;
	}

	// Show a message that Akismet helps process checkout for spam.
	?>
	<p class="pmpro_akismet_privacy_notice">
		<?php esc_html_e( 'This site uses Akismet to reduce spam.', 'pmpro-akismet' ); ?>
		<a href="<?php echo esc_url( 'https://akismet.com/privacy/' ); ?>" target="_blank" rel="nofollow noopener"><?php esc_html_e( 'Learn how your data is processed', 'pmpro-akismet' ); ?></a>.
	</p>
	<?php
}	
add_action( 'pmpro_checkout_before_submit_button', 'pmpro_akismet_show_privacy_notice' );