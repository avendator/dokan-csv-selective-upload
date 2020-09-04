<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://upsite.top/
 * @since      1.0.0
 *
 * @package    Dokan_Csv_Selective_Upload
 * @subpackage Dokan_Csv_Selective_Upload/public
 */

/**
 * Defines the plugin name, version, and two hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 * Connects pages with HTML parts & ajax handlers
 *
 * @package    Dokan_Csv_Selective_Upload
 * @subpackage Dokan_Csv_Selective_Upload/public
 * @author     UPsite <info@upsite.top>
 */
class Dokan_Csv_Selective_Upload_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string    $plugin_name       The name of the plugin.
	 * @param    string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * An instance of this class should be passed to the run() function
		 * defined in Dokan_Csv_Selective_Upload_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dokan_Csv_Selective_Upload_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dokan-csv-selective-upload-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * An instance of this class should be passed to the run() function
		 * defined in Dokan_Csv_Selective_Upload_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dokan_Csv_Selective_Upload_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dokan-csv-selective-upload-public.js', array( 'jquery' ), $this->version, false );

	}

	/** 
	 * ajax - handlers
	 */
	public function dokan_csv_selective_upload_handlers() {
		global $wpdb;

		$user_id = get_current_user_id();
		switch ( $_POST['request'] ) {

			// case 'delete_shopify_product':
			// 	if ( (int)$_POST['product_id'] ) {
			// 		$this->delete_shopify_product( (int)$_POST['product_id'] );
			// 	}
			// 	$data['products_rows'] = self::shopify_products_rows();
			// 	$data['package_info'] = 'You used ' . $this->get_product_number( $user_id );
			// 	$data['message'] = '1 Product succesfully deleted';
			// 	echo json_encode( $data );
			// 	break;

			case 'add_product':
				if ( self::add_product_by_num( $_POST['num'] ) ) {
					$data['message'] = '1 Product succesfully added';
				}
				else {
					$data['message'] = '<span style="color: red;">Product do not added</span>';
				}
				$data['products_used'] = self::get_product_number();
				$data['products_rows'] = self::get_products_rows();
				echo json_encode( $data );		
				break;		
		}	
		wp_die();		
	}

	/**
	 * "dokan_get_template_part" action handler
	 * @param string $template
	 * @param string $slug
	 * @param $name
	 *
	 * @return string
	 */
	public function dashboard_get_template_part( $template, $slug, $name ) {
		if ( $slug == 'products/products-listing' ) {
			if ( mb_substr( $_SERVER['REQUEST_URI'], 0, 30 ) == '/dashboard/products/csv-import' ) {
				if ( isset($_GET['trading-place'], $_GET['file']) ) {
					$template = plugin_dir_path( dirname( __FILE__ ) )  . 'public/partials/products-listing-display.php';
				} else {
					$template = plugin_dir_path( dirname( __FILE__ ) )  . 'public/partials/csv-import-display.php';					
				}
			}	
		}

    	return $template;
	}
	
	/**
	 * "dokan_before_listing_product" action handler
	 */
	public function render_csv_upload_button() { 
		?>  
        <a href="<?= home_url().'/dashboard/products/csv-import'; ?>">
            <input type="button" class="dokan-btn-theme" id="upload-csv-btn" value="Upload new CSV">
        </a>
    	<?php
	}

	/**
	 * @param string $notice
	 */	
	public static function render_products_notices( $notice ) {
		$user_id = get_current_user_id();
		?>
		<div class="dkn-products-notices">
			<div id="dkn-products-load-image" class="dkn-hidden">
				<img src="<?= plugin_dir_path( dirname( __FILE__ ) )  . 'public/img/upload.gif'; ?>">
			</div>
			<div class="dkn-products-notices-message"><?= $notice; ?></div>
			<div class="dkn-products-notices-product-used">You used: <?= self::get_product_number(); ?></div>
		</div>
		<?php
	}

	/**
	 * @return string ( html table rows with products )
	 */
	public static function get_products_rows() {
		
		$products = get_user_meta( get_current_user_id(), 'current_product_page', true );
		$i = 0;
		foreach ( $products as $product ){
			if ( $product['image'] && $product['image'] != '' ) {
				$rows .= '<tr class="">';
					
					$product_id = self::get_product_id( $product );

					$rows .= '<td class="dokan-product-select">
						<label for="cb-select-'.esc_attr( $product['id'] ).'"></label>
						<input class="ozh_select_item dokan-checkbox" type="checkbox" name="bulk_products[]" value="'.$i.'" data-product_id="'.$product_id.'">
					</td>
					<td>';
						if ( $product_id  ) {
							$rows .= '<a href="'.get_permalink( $product_id ).'" target="_blank"><span style="color: green; font-weight: 600;">ON SITE</span></a>';
						}
					$rows .= '</td>
					<td>
						<img width="42" height="42" src="'.$product['image'].'" class="" >
					</td>
					<td>
						'.$product['name'].'<br>';
						if ( $product_id  ) {
							$rows .= '<div class="ozh_shopify_product_update ozh_link" name="'.$i.'" >Update</div> <div class="ozh_shopify_product_delete ozh_link ozh_link" name="'.$product_id.'" >Delete</div>';
						}
						else {
							$rows .= '<div class="dkn-product-add dkn-link" name="'.$i.'" >Add</div>';
						}
					$rows .= '</td>';
					if ( !wp_is_mobile() ) {
						$rows .= '<td>
							'.$product['sku'].'
						</td>
						<td>
							'.$product['categories'].'
						</td>
						<td>
							'.$product['tags'].'
						</td>
						<td>'.$product['stock'];
							if ( $product['in_stock'] ) {
								$rows .= ' in stock';
							}
						$rows .= '</td>
						<td>';
						if ( (int)$product['sale_price'] ) {
							$rows .= floatval( $product['sale_price'] );
						}
						else { 
							$rows .= floatval( $product['regular_price'] );
						}
						$rows .= '</td>';
					}
				$rows .= '</tr>';
				$i++;
			}
		}
		return $rows;
	}

	/**
	 * the number of products posted by the user on the site
	 * @return int
	 */
	private static function get_product_number() {
		$args = [
			'post_type' => 'product',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'author' => get_current_user_id()
		];

		$current_user_products = get_posts( $args );
		$total = count($current_user_products);

		return $total;
	}

	/**
	 * @param array $product
	 * @return int
	 */
	private static function get_product_id( $product ) {
		return 0;
	}

	/**
	 * @param int $num
	 * @return bool
	 */
	private static function add_product_by_num( $num ) {
		
		$user_id = get_current_user_id();
		$user_data = get_userdata( $user_id );
		if ( isset( $user_data->roles[0] ) ) {
			$products = get_user_meta( $user_id, 'current_product_page', true );
			if ( $user_meta->roles[0] == 'vendor' ) {
				return self::add_vendor_product( $products[$num] );
			}
		}
		return FALSE;
	}
	
	/**
	 * @param array
	 * @return bool
	 */
	// private static function add_vendor_product( $product ) {
	public function add_vendor_product( $product ) {
		
		print_r($product);
		return TRUE;
	}

	/**
	 * @param array $nums
	 * @return bool
	 */
	public static function add_products_by_nums( $nums ) {
		
		return TRUE;
	}

	/**
	 * @param array $nums
	 */
	public static function delete_products_by_nums( $nums ) {
		global $wpdb;
		
		// $wpdb->delete( $wpdb->prefix.'posts', array( 'ID' => $product_id ) );
		// $wpdb->delete( $wpdb->prefix.'postmeta', array( 'post_id' => $product_id ) );
	}

	/**
	 * @param int $num
	 *
	 * @return array
	 */
	public static function update_product_by_num( $num ) {
		
		return $data;
	}

	/**
	 * @param string $next
	 * @return array
	 */
	public static function add_all_products( $next ) {
		return $data;
	}

	/**
	 * @param array $ids
	 * @return $array
	 */
	public static function update_products_by_ids ( $ids ) {}

}