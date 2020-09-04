<?php

/**
 * Here are the specific handlers for Woocommerce.
 *
 * @link       https://upsite.top/
 * @since      1.0.0
 *
 * @package    Dokan_Csv_Selective_Upload
 * @subpackage Dokan_Csv_Selective_Upload/public
 * @author     UPsite <info@upsite.top>
 */
class Dokan_Selective_Woocommerce {

	/**
	 * @param 
	 * @return array
	 */
	public function get_woocommerce_sample() {
		$sample = array( 
			'Type',
			'SKU',
			'Name',
			'Published',
			'Is featured?',
			'Visibility in catalog',
			'Short description',
			'Description',
			'Date sale price starts',
			'Date sale price ends',
			'Tax status',
			'Tax class',
			'In stock?',
			'Stock',
			'Low stock amount',
			'Backorders allowed?',
			'Sold individually?',
			'Sale price',
			'Regular price',
			'Categories',
			'Tags',
			'Shipping class',
			'Images',
			'Download limit',
			'Download expiry days',
			'Parent',
			'Position'
		);
		
		return $sample;
	}
	
	/**
	 * @param array current CSV woocommerce Row, array Columns from CSV file
	 *
	 * @return array Standard format row
	 *
	 * get one Standard row from CSV file
	 */
	public function get_csv_woocommerce_product_row( $row_data, $columns ) {
		$product_row = array();
		
		$product_row['origin'] = 'woocommerce';
		$product_row['woocommerce_id'] = $row_data[0];
		$product_row['type'] = $row_data[$columns['Type']];
		
		$images = explode ( ',', $row_data[$columns['Images']] );
		$product_row['image'] = $images[0];
		$product_row['name'] = $row_data[$columns['Name']];
		$product_row['sku'] = $row_data[$columns['SKU']];
		$product_row['in_stock'] = $row_data[$columns['In stock?']];
		$product_row['stock'] = $row_data[$columns['Stock']];
		$product_row['sale_price'] = $row_data[$columns['Sale price']];
		$product_row['regular_price'] = $row_data[$columns['Regular price']];
		$product_row['categories'] = $row_data[$columns['Categories']];
		$product_row['tags'] = $row_data[$columns['Tags']];
		
		return $product_row;
	}


}