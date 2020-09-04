<?php

/**
 * This template displays a list of products depending on the trading place.
 *
 * @link       https://upsite.top/
 * @since      1.0.0
 *
 * @package    Dokan_Csv_Selective_Upload
 * @subpackage Dokan_Csv_Selective_Upload/public/partials
 */

if ( !is_user_logged_in() ) {
	return;
}

CSV_Importer::create_product_page();

// Generate Pagination links Next and Previous
$pagination_links = get_user_meta ( get_current_user_id(), 'current_product_pagination', true );

// $cuser = wp_get_current_user();
// if($cuser->roles[0] != 'vendor') {
// 	return;
// }
// $products = same_obj->get_products();

do_action( 'dokan_dashboard_wrap_start' ); ?>

<div class="dokan-dashboard-wrap">

    <?php
    
    /**
     *  dokan_dashboard_content_before hook
     *
     *  @hooked get_dashboard_side_navigation
     *
     *  @since 2.4
     */
    do_action( 'dokan_dashboard_content_before' );
    ?>
	<div class="dokan-dashboard-content dokan-product-listing">
		<h1>Products on your <?= ucfirst($_GET['trading-place']); ?> site</h1>
		<?php
		if ( $products['notice'] == '' ) : ?>

			<?php Dokan_Csv_Selective_Upload_Public::render_products_notices( $products['notice'] ); ?>
			<input type="hidden" id="site_url" val="<?= site_url(); ?>">
			<input type="hidden" id="import-all-next" val="">
			<div class="dkn-products-listing-wrap">

				<div class="dkn-synchronise-button-block">
					<input type="button" id="product-update" class="dokan-btn dokan-btn-success" value="Synchronise all present products">
				</div>
				<div class="dkn-buttons-wrap">
					<div>
						<input type="button" id="product-import-selected" class="dokan-btn dokan-btn-success" value="Import selected">
						<input type="button" id="product-delete-selected" class="dokan-btn dokan-btn-theme" value="Delete selected">
					</div>
					<div class="dkn-product-import-all-block">
						<input type="button" id="product-import-all" class="product-import-all dokan-btn dokan-btn-theme" value="Import all products">
					</div>
				</div>
				<table class="dkn-products-table" id="dokan-product-list-table">
					<thead>
						<tr>
							<th id="cb" class="manage-column column-cb check-column">
								<label for="dkn-select-all"></label>
								<input id="dkn-select-all" class="dokan-checkbox" type="checkbox">
							</th>
							<th>Present</th>
							<th>Image</th>
							<th>Name</th>
							<?php if ( !wp_is_mobile() ) : ?>
								<th>SKU</th>
								<th>Categories</th>
								<th>Tags</th>
								<th>QTY</th>
								<th>Price</th>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody id="dkn-products-tbody">
						<?php echo Dokan_Csv_Selective_Upload_Public::get_products_rows(); ?>
					</tbody>
				</table>
			</div>
			<div class="dkn-pagination">
			<?php if ( $pagination_links['previous'] != '' ) : ?>
				<big>
					<a href="<?= $pagination_links['previous']; ?>">&larr; Previous</a> &nbsp;&nbsp;&nbsp;&nbsp;
				</big>	
			<?php endif; ?>
			<?php if ( $pagination_links['next']!= '' ) : ?>				
				<big>
					<a href="<?= $pagination_links['next']; ?>">Next &rarr;</a>
				</big>
			<?php endif; ?>
			</div>

		<?php endif; ?>
	</div>
</div>