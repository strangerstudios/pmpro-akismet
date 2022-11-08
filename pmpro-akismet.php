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
    if ( is_plugin_active( 'paid-memberships-pro/paid-memberships-pro.php' ) ) {
        return;
    }

    echo '<div class="error"><p>' . __( 'The Paid Memberships Pro - Akismet Integration add-on requires the Paid Memberships Pro plugin. Please install and activate Paid Memberships Pro before activating this add-on.', 'pmpro-akismet' ) . '</p></div>';
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

