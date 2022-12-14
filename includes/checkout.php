<?php
/**
 * All checkout/registration functionality.
 */

use PMPro_Akismet\Akismet;

/**
 * Run spam check during checkout process
 * 
 * @since 1.0
 */
function pmpro_akismet_registration_checks( $continue ) {

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
        'user_ip' => sanitize_text_field(  $_SERVER['REMOTE_ADDR'] ),
        'user_agent' => sanitize_text_field(  $_SERVER['HTTP_USER_AGENT'] ),
        'referrer' => sanitize_text_field(  $_SERVER['HTTP_REFERER'] ),
        'blog' => get_option( 'home' ),
        'blog_lang' => get_locale(),
        'blog_charset' => get_option( 'blog_charset' ),
        'permalink' => get_permalink(),
        'comment_type' => 'signup',
        'comment_author' => sanitize_text_field( $_REQUEST['username'] ),
        'comment_author_email' => sanitize_email( $_REQUEST['bemail'] ),
        'honeypot_field_name' => 'fullname'
    );
    
    // Allow filtering of data.
    $data_to_check = apply_filters( 'pmpro_akismet_data_to_check', $data_to_check ); // Filter to check the data.

    // Check to see if Akismet thinks it's spam or not.
    $is_spam = apply_filters( 'pmpro_akismet_checkout_is_spam', Akismet::is_spam( $data_to_check ) );

    if ( ! $is_spam ) {
        $continue = true;
    } else {
        $continue = false;
        // Set error that user is spam.
        pmpro_setMessage( __( 'Sorry, your username or email has been flagged as suspicious.', 'pmpro-akismet' ), 'pmpro_error' );        
    }

    return $continue;

}
add_filter( 'pmpro_registration_checks', 'pmpro_akismet_registration_checks', 10, 1 );

/**
 * Show Akismet notice on checkout page below the submit button based on Akismet privacy notice setting.
 * 
 * @since 1.0
 * 
 */
function pmpro_akismet_show_privacy_notice() {
    // Bail if Akismet show comment setting is set to 'hide'
    if ( 'display' !== apply_filters( 'pmpro_akismet_checkout_privacy_notice' , get_option( 'akismet_comment_form_privacy_notice', 'hide' ) ) ) {
		return;
	}

    // Show a message that Akismet helps process checkout for spam.
    echo '<p class="pmpro_akismet_privacy_notice">' . sprintf(
				__( 'This site uses Akismet to reduce spam. <a href="%s" target="_blank" rel="nofollow noopener">Learn how your data is processed</a>.', 'akismet' ),
				esc_url( 'https://akismet.com/privacy/' )
			) . '</p>';
    
}
add_action( 'pmpro_checkout_before_submit_button', 'pmpro_akismet_show_privacy_notice' );