<?php
/**
 * Plugin Name: Paid Memberships Pro - Akismet Integration
 * Plugin URI: https://www.paidmembershipspro.com/add-ons/pmpro-akismet/
 * Description: Protect your membership site from checkout spam with Akismet and Paid Memberships Pro.
 * Version: 1.0
 * Author: Paid Memberships Pro
 * Author URI: https://www.paidmembershipspro.com
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

    // Check if the required plugins are installed.
    $missing_plugins = array();
    foreach ( $required_plugins as $plugin => $name ) {
        if ( ! file_exists( WP_PLUGIN_DIR . '/' . $plugin ) ) {
            $missing_plugins[$plugin] = $name;
        }
    }

    // If there are missing plugins, show a notice.
    if ( ! empty( $missing_plugins ) ) {
        // Build install links here.
        $install_plugins = array();
        foreach( $missing_plugins as $path => $name ) {
            $install_plugins[] = sprintf( '<a href="%s">%s</a>', esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $path ), 'install-plugin_' . $path ) ), $name );
        }

        // Show notice with install_plugin links.
        printf(
            '<div class="notice notice-warning"><p>%s</p></div>',
            sprintf(
                __( 'The following plugin(s) are required for the %1$s plugin to work: %2$s', 'pmpro-akismet' ),
                __( 'Paid Memberships Pro - Akismet Integration', 'pmpro-akismet' ),
                implode( ', ', $install_plugins )
            )
        );

        return; // Bail here, so we only show one notice at a time.
    }


    // Check if the required plugins are active and show a notice with activation links if they are not
    $inactive_plugins = array();
    foreach ( $required_plugins as $plugin => $name ) {
        if ( ! is_plugin_active( $plugin ) ) {
            $inactive_plugins[$plugin] = $name;
        }
    }

    // If there are inactive plugins, show a notice.
    if ( ! empty( $inactive_plugins ) ) {
        // Build activate links here.
        $activate_plugins = array();
        foreach( $inactive_plugins as $path => $name ) {
            $activate_plugins[] = sprintf( '<a href="%s">%s</a>', esc_url( wp_nonce_url( self_admin_url( 'plugins.php?action=activate&plugin=' . $path ), 'activate-plugin_' . $path ) ), $name );
        }

        // Show notice with activate_plugin links.
        printf(
            '<div class="notice notice-warning"><p>%s</p></div>',
            sprintf(
                __( 'The following plugin(s) are required for the %1$s plugin to work: %2$s', 'pmpro-akismet' ),
                __( 'Paid Memberships Pro - Akismet Integration', 'pmpro-akismet' ),
                implode( ', ', $activate_plugins )
            )
        );

        return; // Bail here, so we only show one notice at a time.
    }

    //Check if there's a valid API key in Akismet, if not show a message
    if ( ! Akismet::has_valid_key() ) {
        echo '<div class="error"><p>' . __( 'The Paid Memberships Pro - Akismet Integration requires a valid API key in Akismet. Please enter a valid API key in Akismet to enable anti-spam functionality.', 'pmpro-akismet' ) . '</p></div>';
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
