<?php
/**
 * Plugin Name: Paid Memberships Pro - Akismet Integration
 * Plugin URI: http://www.paidmembershipspro.com/wp/pmpro-akismet/
 * Description: Integrates Akismet with Paid Memberships Pro.
 * Version: 1.0
 * Author: Stranger Studios
 * Author URI: http://www.strangerstudios.com
 * Text Domain: pmpro-akismet
 * Domain Path: /languages
 */

use PMPro_Akismet\Akismet;

/**
 * Includes go here.
 */
require_once( dirname( __FILE__ ) . '/includes/class.pmpro-akismet.php' );
require_once( dirname( __FILE__ ) . '/includes/checkout.php' );

/**
 * Admin notice to show a warning that Paid Memberships Pro is inactive.
 * 
 * @since 1.0
 */
function pmpro_akismet_pmpro_required() {

    // Only show notices on PMPro page.
    if ( ! isset( $_REQUEST['page'] ) || strpos( $_REQUEST['page'], 'pmpro' ) === false ) {
        return;
    }

    // The required plugins for this Add On to work.
    $required_plugins = array(
        'paid-memberships-pro/paid-memberships-pro.php' => __( 'Paid Memberships Pro', 'pmpro-akismet' ),
        'akismet/akismet.php' => __( 'Akismet', 'pmpro-akismet' )
    );

    foreach( $required_plugins as $file => $plugin_name ) {
        if ( ! is_plugin_active( $file ) ) {
            $message = sprintf( __( 'The %s plugin requires the %s plugin to be active.', 'pmpro-akismet' ), 'Paid Memberships Pro - Akismet Integration', $plugin_name );
            echo '<div class="error"><p>' . esc_html( $message ) . '</p></div>';
            return;
        }

    }

    //Check if there's a valid API key in Akismet, if not show a message
    if ( ! Akismet::has_valid_key() ) {
        echo '<div class="error"><p>' . __( 'The Paid Memberships Pro - Akismet Integration add-on requires a valid API key in Akismet. Please enter a valid API key in Akismet to enable anti-spam functionality.', 'pmpro-akismet' ) . '</p></div>';
        return;
    }

}
add_action( 'admin_notices', 'pmpro_akismet_pmpro_required' );

/**
 * Load the plugin text domain for translation.
 * 
 * @since 1.0
 */
function pmpro_akismet_load_textdomain() {
    load_plugin_textdomain( 'pmpro-akismet', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'pmpro_akismet_load_textdomain' );
