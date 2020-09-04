<?php

/**
 * This template displays the product loading interface from the csv file.
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
if ( isset($_POST['save_step']) ) {
	echo CSV_Importer::upload( $_FILES['file'], $_POST['_wp_http_referer'] );
}
?>
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
		<div class="dokan-csv-import-container">
			<form enctype="multipart/form-data" method="post">
				<h2>Import products from a CSV file</h2>
				<label for="csv-upload">Choose a CSV file from your computer:</label>
				<input type="file" name="file">
				<small>Maximum size: 50 MB</small>
				<div>
					<button type="submit" class="button button-primary button-next" value="Continue" name="save_step">Continue</button>
				</div>
				<?php wp_nonce_field(); ?>
			</form>
		</div>
	</div>
</div>