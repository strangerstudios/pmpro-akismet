<?php
namespace PMPro_Akismet;

use Akismet as AkismetPlugin;

class Akismet {
    
    /**
     * Check if Akismet is active.
     * 
     * @since 1.0
     * 
     * @return bool True if Akismet is active, false otherwise.
     */
    public static function is_active() {
        if ( defined( 'AKISMET_VERSION' ) ) {
            return true;
        } else {
            return false;
        }
    }

    /**
	 * Check if Akismet has a valid API key.
	 *
	 * @since 1.0
	 *
	 * @return bool True if Akismet has a valid API key, false otherwise.
	 */
	public static function has_valid_key() {
        // If Akismet returns an API key, it is valid.
		return ! empty( AkismetPlugin::get_api_key() );
	}

    /**
     * Check if the checkout information provided is spam or not.
     * 
     * @since 1.0
     * 
     * @param $data_to_check array The checkout data to check during registration.
     * @return bool True if the checkout information is spam, false otherwise.
     */
    public static function is_spam( $data_to_check ) {
        // Akismet plugin not configured at all.
        if ( ! self::is_active() || ! self::has_valid_key() ) {
            return false;
        }

        $response = AkismetPlugin::http_post( build_query( $data_to_check ), 'comment-check' );
		return ! empty( $response ) && isset( $response[1] ) && 'true' === trim( $response[1] );
    }


}
