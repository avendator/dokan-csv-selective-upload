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
 * Defines handlers for extracting products from the csv file for use on trading places
 *
 * @package    Dokan_Csv_Selective_Upload
 * @subpackage Dokan_Csv_Selective_Upload/public
 * @author     UPsite <info@upsite.top>
 */
class CSV_Importer {

	/**
	 * @param array $csv: file
	 * @param string $uri
	 */
	public static function upload( $csv, $uri ) {
		
		if( !wp_verify_nonce( $_POST['_wpnonce'] ) ) {
			echo 'Sorry, the verification data does not match.';
			return;
		}

		if( $csv['size'] == 0 ) return;
		
		// Check Temp file
		$trading_place = self::check_csv_file_format( $csv );
		
		if ( $trading_place && $trading_place != 'not_supported' ) {

			$movefile = self::insert_file( $csv);

			if ( !is_array($movefile) ) return $movefile;

			$url = $movefile['file'];
			$url = home_url() . $uri . '?trading-place='.$trading_place.'&file='.$url;
			echo '<script>document.location.href = "'.$url.'"</script>';
		}
		else {
			// Если NULL файл не открылся или не CSV, Если not_supported это CSV но не поддерживается
		}
	}

	/**
	 * @param array $csv: file
	 * @return string | array
	 */
	private static function insert_file( $file ) {

		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );

			$overrides = [ 'test_form' => false ];
			$movefile = wp_handle_upload( $file, $overrides );

			if ( $movefile && empty($movefile['error']) ) {
				return $movefile;
			} else {
				return 'File was not uploaded.';
			}
		}
	}
	
	/**
	 * @param array $csv: file
	 * @return string
	 */
	private static function check_csv_file_format( $csv ) {

		$name_arr = explode( '.', $csv['name'] );
		if ( $name_arr[count($name_arr) - 1] != 'csv' ) {
			return NULL;
		}
		
		if (($handle = fopen( wc_clean( wp_unslash( $csv['tmp_name'] ) ) , "r")) === FALSE) {
			return NULL;
		}
		
		$title = fgetcsv( $handle, 1000, "," );
		
		fclose( $handle );
		
		// Check to Shopify format
		if ( $title[0] == 'Handle' ) {
			
			$sample = Dokan_Selective_Shopify::get_shopify_sample();
			
			foreach ( $sample as $column_name ) {
				if ( !in_array( $column_name, $title ) ) {
					return 'not_supported';
				}
			}
			return 'shopify';
		}
		
		// Check to Woocommerce format
		
		if ( $title[0] == '﻿ID' ) {

			$sample = Dokan_Selective_Woocommerce::get_woocommerce_sample();
			
			foreach ( $sample as $column_name ) {
				if ( !in_array( $column_name, $title ) ) {
					return 'not_supported';
				}
			}
			
			return 'woocommerce';
		}

		return 'not_supported';
	}
	
	/**
	 * @param 
	 *
	 * @return 
	 *
	 * create User meta 'current_product_page' and 'current_product_pagination'
	 */
	public static function create_product_page() {
		if ( isset( $_GET['trading-place'] ) && isset( $_GET['file'] ) ) {
			self::create_csv_product_page();
		}
	}
	
	/**
	 * @param 
	 *
	 * @return 
	 *
	 * create User meta 'current_product_page' and 'current_product_pagination'
	 */
	private static function create_csv_product_page() {
		$file = $_GET['file'];
		
		if ( $file == '' ) {
			return NULL;
		}
		
		if (($handle = fopen( wc_clean( wp_unslash( $file ) ) , "r")) === FALSE) {
			return NULL;
		}
		
		$page = 0;
		$pagination['previous'] = '';
		$pagination['next'] = '';
		if ( (int)$_GET['pagenum'] ) {
			$page = (int)$_GET['pagenum'];
			$pagination['previous'] = '/dashboard/products/csv-import/?trading-place='.$_GET['trading-place'].'&file='.$file;
		}
		if ( $page > 1 ) {
			$previous_page = $page - 1;
			$pagination['previous'] .= '&pagenum='.$previous_page;
		}
		
		$title = fgetcsv( $handle, 1000, "," );

		$columns = array();
		$i = 0;
		foreach ( $title as $k => $v ) {
			$columns[$v] = $i;
			$i++;
		}
		
		$current_product_page = array();
		$current_original_page = array();
		
		$row = 0;
		while ( $row_data = fgetcsv( $handle, 1000, "," ) ) {
			if ( is_array( $row_data ) && count( $row_data ) > 14 ) {
				
				if ( $row >= $page * 20 ) {
					if ( $row >= $page * 20 + 20 ) {
						$next_page = $page + 1;
						$pagination['next'] = '/dashboard/products/csv-import/?trading-place='.$_GET['trading-place'].'&file='.$file.'&pagenum='.$next_page;
						break;
					}
					$current_original_page[] = $row_data;
					switch ( $_GET['trading-place'] ) {
						case 'woocommerce':
							$current_product_page[] = Dokan_Selective_Woocommerce::get_csv_woocommerce_product_row( $row_data, $columns );
							break;
						case 'shopify':
							$current_product_page[] = Dokan_Selective_Shopify::get_csv_shopify_product_row( $row_data, $columns );
							break;
					}
				}
				$row++;
			}
			else {
				break;
			}
		}
		
		$user_id = get_current_user_id();
		update_user_meta ( $user_id, 'current_original_title', $title );
		update_user_meta ( $user_id, 'current_original_page', $current_original_page );
		update_user_meta ( $user_id, 'current_product_page', $current_product_page );
		update_user_meta ( $user_id, 'current_product_pagination', $pagination );
		
		fclose( $handle );
	}

	/**
	 * Ajax - handler
	 * Adapted analog of Woocommerce function WC_Admin_Importers::do_ajax_product_import()
	 *
	 * @return JSON data (successful responses in AJAX requests or errors)
	 */
	public function do_ajax_product_import() {
		global $wpdb;

	}

}