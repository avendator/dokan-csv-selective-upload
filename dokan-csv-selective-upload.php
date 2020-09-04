<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://upsite.top/
 * @since             1.0.0
 * @package           Dokan_Csv_Selective_Upload
 *
 * @wordpress-plugin
 * Plugin Name:       Dokan CSV Selective Upload
 * Plugin URI:        https://upsite.top/
 * Description:       Convenient and easy loading of poducts from the CSV file to trading platforms, such as Shopify and Dokan. For work, plugins are required Dokan (version of light or pro) and Woocommerce.
 * Version:           1.0.0
 * Author:            UPsite
 * Author URI:        https://upsite.top/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dokan-csv-selective-upload
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'DOKAN_CSV_SELECTIVE_UPLOAD_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-dokan-csv-selective-upload-activator.php
 */
function activate_dokan_csv_selective_upload() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dokan-csv-selective-upload-activator.php';
	Dokan_Csv_Selective_Upload_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-dokan-csv-selective-upload-deactivator.php
 */
function deactivate_dokan_csv_selective_upload() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dokan-csv-selective-upload-deactivator.php';
	Dokan_Csv_Selective_Upload_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_dokan_csv_selective_upload' );
register_deactivation_hook( __FILE__, 'deactivate_dokan_csv_selective_upload' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-dokan-csv-selective-upload.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_dokan_csv_selective_upload() {

	$plugin = new Dokan_Csv_Selective_Upload();
	$plugin->run();

}
run_dokan_csv_selective_upload();


/*********************************************/
// Alex test
add_shortcode( 'alex_test', 'alex_test' );
function alex_test() {
	$user_id = get_current_user_id();
	$products = get_user_meta( $user_id, 'current_product_page', true );
	Dokan_Csv_Selective_Upload_Public::add_vendor_product($products[2]);
}
