<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/stephanieland352/gravity-forms-tenstreet
 * @since      1.0.0
 *
 * @package    Rlc_Tenstreet_Gf_Integration
 * @subpackage Rlc_Tenstreet_Gf_Integration/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Rlc_Tenstreet_Gf_Integration
 * @subpackage Rlc_Tenstreet_Gf_Integration/includes
 * @author     Stephanie Land
 */
class Rlc_Tenstreet_Gf_Integration_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        if ( ! is_plugin_active( 'gravityforms/gravityforms.php' ) && current_user_can( 'activate_plugins' )   ) {
            // Stop activation redirect and show error
            wp_die('Sorry, but the Tenstreet API Plugin requires the Gravity Forms plugin to be installed and activated <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
        }
	}

}
