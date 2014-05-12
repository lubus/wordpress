jQuery(document).ready(function($) {
	var $ImgUploadBtns = $('.upload_image_button');
	
	$ImgUploadBtns.each(function(){	
		var $varID = $(this).attr('rel');
		// Set up content to inset after variation Image		
		$.ajax({
	        url: "/eshop-phiverivers/applications/eshop_woothumbs/getvariationimages.php",
	        type: "GET",
	        data: { varID: $varID, abspath: vars.abspath },
			cache: false,
	        context: this,
	        success: function(data) {
	        	var $varID = $(this).attr('rel');
	            var $wooThumbs = '<h4>Additional Images</h4>'+data+'<a href="#" class="manage_wooThumbs">Add additional images</a>';
				$(this).after($wooThumbs);
				
				// Sort Images
				$( ".wooThumbs" ).sortable({
				    deactivate: function(en, ui) {
				        var $tableCol = $(ui.item).closest('.upload_image');						
						selectedImgs($tableCol);
				    },
				    placeholder: 'ui-state-highlight'
			  	});
	        }
	    });
	});
	
	var product_gallery_frame;
	
	$('.manage_wooThumbs').live( 'click', 'a', function( event ) {

		var $wooThumbs = $(this).siblings('.wooThumbs');
		var $image_gallery_ids = $(this).siblings('.variation_image_gallery');
	
		var $el = $(this);
		var attachment_ids = $image_gallery_ids.val();
	
		event.preventDefault();
	
		// Create the media frame.
		product_gallery_frame = wp.media.frames.downloadable_file = wp.media({
			// Set the title of the modal.
			title: 'Manage Variation Images',
			button: {
				text: 'Add to variation',
			},
			multiple: true
		});
	
		// When an image is selected, run a callback.
		product_gallery_frame.on( 'select', function() {
	
			var selection = product_gallery_frame.state().get('selection');
	
			selection.map( function( attachment ) {
	
				attachment = attachment.toJSON();
	
				if ( attachment.id ) {
					attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;
	
					$wooThumbs.append('\
						<li class="image" data-attachment_id="' + attachment.id + '">\
							<a href="#" class="delete" title="Delete image"><img src="' + attachment.url + '" /></a>\
						</li>');
				}
	
			} );
	
			$image_gallery_ids.val( attachment_ids );
		});
	
		// Finally, open the modal.
		product_gallery_frame.open();
		
		return false;
	});
	
	function selectedImgs($tableCol) {
		// Get all selected images
		var $selectedImgs = [];
		$tableCol.find('.wooThumbs .image').each(function(){
			$selectedImgs.push($(this).attr('data-attachment_id'));
		});
		// Update hidden input with chosen images
    	$tableCol.find('.variation_image_gallery').val($selectedImgs.join(','));
	}
	
	// Delete Image
	$('.wooThumbs .delete').live("mouseenter mouseleave click", function(event){
		
		if (event.type == 'click') {
			var $tableCol = $(this).closest('.upload_image');
			// Remove clicked image
			$(this).closest('li').remove();
			
			selectedImgs($tableCol);
	        return false;
	    }
	    
		if (event.type == 'mouseenter') {
	        $(this).find('img').animate({"opacity": 0.3}, 150);
	    }
	    if (event.type == 'mouseleave') {
	        $(this).find('img').animate({"opacity": 1}, 150);
	    }
		
	});	
	
});
