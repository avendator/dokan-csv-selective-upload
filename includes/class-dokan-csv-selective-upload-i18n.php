<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://upsite.top/
 * @since      1.0.0
 *
 * @package    Dokan_Csv_Selective_Upload
 * @subpackage Dokan_Csv_Selective_Upload/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Dokan_Csv_Selective_Upload
 * @subpackage Dokan_Csv_Selective_Upload/includes
 * @author     UPsite <info@upsite.top>
 */
class Dokan_Csv_Selective_Upload_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'dokan-csv-selective-upload',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
