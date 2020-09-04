(function( $ ) {
	'use strict';

	$(function() {

		const ajaxurl = window.location.origin+'/wp-admin/admin-ajax.php';
	
		// Delete One product
		// $("#ozh_shopify_products_tbody").on("click", ".ozh_shopify_product_delete", function(){

		// 	$.ajax({
		// 		url: ajaxurl,
		// 		data: {
		// 			product_id: $(this).attr('name'),
		// 			action: 'dokan_csv_selective_upload_handlers',
		// 			request: 'delete_shopify_product'
		// 		},
		// 		dataType: 'json',
		// 		method: 'POST',
		// 		success: function (response) {
		// 			$(".ozh_products_notices_message").html(response.message);
		// 			$(".ozh_products_notices_package_info").html(response.package_info);
		// 			$("#ozh_shopify_products_tbody").html(response.products_rows);
		// 		},
		// 		error: function (e) {
		// 			console.log(e);
		// 		}
		// 	});

		// });
	
		// Import One product
		$("#dkn-products-tbody").on("click", ".dkn-product-add", function(){
			
			$.ajax({
				url: ajaxurl,
				data: {
					num: $(this).attr('name'),
					action: 'dokan_csv_selective_upload_handlers',
					request: 'add_product'
				},
				dataType: 'json',
				method: 'POST',
				success: function (response) {
					console.log(response);
					$(".dkn-products-notices-message").html(response.message);
					$(".dkn-products-notices-product-used").html(response.products_used);
					$("#dkn-products-tbody").html(response.products_rows);
				},
				error: function (e) {
					console.log(e);
				}
			});

		});

	});
})( jQuery );