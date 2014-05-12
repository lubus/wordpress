jQuery(document).ready(function($) {

	var $product_thumbs	= $( vars.thumbnails_container );	
	var $orig_thumbs = $product_thumbs.html();
	$variation_form = $('form.variations_form');
	$prevVariationId = 0;

	// Load in new thumbs
	$('.single_variation_wrap').on( 'show_variation', function( event ) {
		var $currentTarget = event.currentTarget;
		var $variationId = $($currentTarget).find('input[name=variation_id]').val();
		
		if($prevVariationId != $variationId) {
			$product_thumbs.animate({'opacity':0}, { 
				duration : parseInt(vars.transition), 
				complete: function(){
					$.get(vars.plugin_url+"/getthumbs.php", { varid: $variationId, abspath: vars.abspath, plugin_path: vars.plugin_path }, function(data){
						$product_thumbs.html(data).animate({'opacity':1}, { duration : parseInt(vars.transition) });
						$variation_form.trigger( 'eshop_woothumbs_callback' );
						$prevVariationId = $variationId;
					});
				}
			});
		}

	});
	
	// Reset to default images
	function resetImages(){
		$product_thumbs.html($orig_thumbs);
		$variation_form.trigger( 'eshop_woothumbs_callback' );
		$prevVariationId = 0;
	}
	
	$('.reset_variations').click(resetImages());
	$('.swatch-wrapper a').on('click', function(){
		var $parent = $(this).parent();
		if($parent.hasClass('selected')) {
			resetImages();
		}
	});
	$('.variations_form select').on('change', function(){
		if($(this).val() == "") {
			resetImages();
		}
	});

}); 