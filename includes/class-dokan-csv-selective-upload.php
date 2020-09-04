<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://upsite.top/
 * @since      1.0.0
 *
 * @package    Dokan_Csv_Selective_Upload
 * @subpackage Dokan_Csv_Selective_Upload/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Dokan_Csv_Selective_Upload
 * @subpackage Dokan_Csv_Selective_Upload/includes
 * @author     UPsite <info@upsite.top>
 */
class Dokan_Csv_Selective_Upload {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Dokan_Csv_Selective_Upload_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'DOKAN_CSV_SELECTIVE_UPLOAD_VERSION' ) ) {
			$this->version = DOKAN_CSV_SELECTIVE_UPLOAD_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'dokan-csv-selective-upload';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Dokan_Csv_Selective_Upload_Loader. Orchestrates the hooks of the plugin.
	 * - Dokan_Csv_Selective_Upload_i18n. Defines internationalization functionality.
	 * - Dokan_Csv_Selective_Upload_Admin. Defines all hooks for the admin area.
	 * - Dokan_Csv_Selective_Upload_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-dokan-csv-selective-upload-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-dokan-csv-selective-upload-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-dokan-csv-selective-upload-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-dokan-csv-selective-upload-public.php';

		/**
		 * The class responsible for processing data related to the Shopify marketplace
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-dokan-selective-shopify.php';

		/**
		 * The class responsible for processing data related to the Woocommerce
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-dokan-selective-woocommerce.php';

		/**
		 * The class includes shortcodes, actions and filters for for extracting goods from the csv file for use on trading floors
		 */		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-csv-importer.php';

		$this->loader = new Dokan_Csv_Selective_Upload_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Dokan_Csv_Selective_Upload_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Dokan_Csv_Selective_Upload_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Dokan_Csv_Selective_Upload_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Dokan_Csv_Selective_Upload_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		// AJAX handlers declared through switch case
		$this->loader->add_action( 'wp_ajax_'.'dokan_csv_selective_upload_handlers', $plugin_public, 'dokan_csv_selective_upload_handlers' );

		$this->loader->add_action( 'dokan_before_listing_product', $plugin_public, 'render_csv_upload_button' );

		$this->loader->add_filter('dokan_get_template_part', $plugin_public, 'dashboard_get_template_part', 10, 3);

		$csv_import = new CSV_Importer();
		
		// AJAX handlers
		$this->loader->add_action( 'wp_ajax_'.'do_ajax_product_import', $csv_import, 'do_ajax_product_import' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Dokan_Csv_Selective_Upload_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}