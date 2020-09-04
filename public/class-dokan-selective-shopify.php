<?php

/**
 * Here are the specific handlers for Shopify.
 *
 * @link       https://upsite.top/
 * @since      1.0.0
 *
 * @package    Dokan_Csv_Selective_Upload
 * @subpackage Dokan_Csv_Selective_Upload/public
 * @author     UPsite <info@upsite.top>
 */
class First_Multi_Retailer_Shopify {

	/**
	 * @param 
	 * @return array
	 */
	public function get_shopify_sample() {
		$sample = array(
			'Title',
			'Body (HTML)',
			'Vendor',
			'Type',
			'Tags',
			'Published',
			'Option1 Name',
			'Option1 Value',
			'Option2 Name',
			'Option2 Value',
			'Option3 Name',
			'Option3 Value',
			'Variant SKU',
			'Variant Grams',
			'Variant Inventory Tracker',
			'Variant Inventory Qty',
			'Variant Inventory Policy',
			'Variant Fulfillment Service',
			'Variant Price',
			'Variant Compare At Price',
			'Variant Requires Shipping',
			'Variant Taxable',
			'Variant Barcode',
			'Image Src',
			'Image Position',
			'Image Alt Text',
			'Gift Card',
			'Variant Image',
			'Variant Weight Unit',
			'Variant Tax Code',
			'Cost per item'
		);
		
		return $sample;
	}
	
	/**
	 * @param array current CSV shopify Row, array Columns from CSV file
	 *
	 * @return array Standard format row
	 *
	 * get one Standard row from CSV file
	 */
	public function get_csv_shopify_product_row( $row_data, $columns ) {
		$product_row = array();
		
		$product_row['origin'] = 'shopify';
		$product_row['shopify_handle'] = $row_data[$columns['Handle']];
		
		$product_row['image'] =  $row_data[$columns['Image Src']];
		$product_row['name'] = $row_data[$columns['Title']];
		$product_row['sku'] = $row_data[$columns['Variant SKU']];
		$regular_price = $row_data[$columns['Variant Compare At Price']];
		$sale_price    = $row_data[$columns['Variant Price']];
		if ( ! floatval( $regular_price ) || floatval( $regular_price ) == floatval( $sale_price ) ) {
			$regular_price = $sale_price;
			$sale_price    = '';
		}
		$product_row['regular_price'] = $regular_price;
		$product_row['sale_price'] = $row_data[$columns['Sale price']];
		$product_row['stock'] = (int)$row_data[$columns['Variant Inventory Qty']];
		if ( $product_row['stock'] ) {
			$product_row['in_stock'] = 1;
		}
		else {
			$product_row['in_stock'] = 0;
		}
		$product_row['categories'] = $row_data[$columns['Type']];
		$product_row['tags'] = $row_data[$columns['Tags']];
		return $product_row;
	}
}