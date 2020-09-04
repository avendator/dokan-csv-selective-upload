<?php

/**
 * Fired during plugin activation
 *
 * @link       https://upsite.top/
 * @since      1.0.0
 *
 * @package    Dokan_Csv_Selective_Upload
 * @subpackage Dokan_Csv_Selective_Upload/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Dokan_Csv_Selective_Upload
 * @subpackage Dokan_Csv_Selective_Upload/includes
 * @author     UPsite <info@upsite.top>
 */
class Dokan_Csv_Selective_Upload_Activator {

	/**
	 * Installs dependencies on other plugins: Dokan (all versions), Wookommerce. 
	 * If the above plugins are missing, stop activation and display a message.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
	    if ( ! function_exists( 'get_plugins' ) ) {
	        require_once ABSPATH . 'wp-admin/includes/plugin.php';
	    }
	    $all_plugins = get_plugins();

	    foreach ($all_plugins as $key => $value) {

	        if ( $pos = stripos( $key, '/dokan.php') ) {
	            $version = substr( $key, 0, $pos );
	            $version = substr( $version, 6 );
	        }
	    }
	    
	    $version = $version ?? 'lite';

		if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			die('It requires WooCommerce in order to work.');
		} 
		elseif ( !is_plugin_active( 'dokan-'.$version.'/dokan.php' ) ) {
			die('It requires Dokan in order to work.'); 
		}
	}

}
