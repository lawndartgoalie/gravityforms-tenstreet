<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/stephanieland352/gravity-forms-tenstreet
 * @since      1.0.0
 *
 * @package    Rlc_Tenstreet_Gf_Integration
 * @subpackage Rlc_Tenstreet_Gf_Integration/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Rlc_Tenstreet_Gf_Integration
 * @subpackage Rlc_Tenstreet_Gf_Integration/includes
 * @author     Stephanie Land
 */
class Rlc_Tenstreet_Gf_Integration_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'gravity-forms-tenstreet',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
