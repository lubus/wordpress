/*-----------------------------------------------------------------------------------*/
/* GENERAL SCRIPTS */
/*-----------------------------------------------------------------------------------*/
jQuery(document).ready(function(){

		jQuery('ul.one').hide();
		jQuery("ul li.country_block a").click(function(){
			jQuery('ul ul.one').slideToggle('fast');
		});
		jQuery('ul ul.one').mouseleave(function(){
			jQuery(this).slideUp('fast');
		});
		jQuery('li a.countrylist').click(function(){
			country_code = jQuery(this).attr('id');
			jQuery('#countryn').val(country_code);
			jQuery('form#user_country').submit();
		});

	jQuery('#countryn').change(function(){
		jQuery('form#user_country').submit();
	});
	swatchclick();
    thumbnailclick();
	jQuery('.more').click(function(){
		jQuery('.second_featured_prod').slideToggle();
		jQuery( this ).toggleClass( "less_up" );
		jQuery( this ).html(jQuery('.more').text() == 'View Less' ? 'View More' : 'View Less' );
	});
});


function swatchclick(){//alert("swatch");
     jQuery('.swatch-wrapper').click(function(){ //alert("swatch clicked");
	var mainvalue1='';
	mainvalue1 = jQuery(this).attr('rel');
        if(jQuery(this).attr('class')!='select-option swatch-wrapper selected' && jQuery(this).attr('class')!='select-option swatch-wrapper'){
            var select_col = mainvalue1.split('*=*');
            var selectedcolor =mainvalue1.split('***');
            var img_path1 = mainvalue1.split('@@@');
            var post_id = mainvalue1.split('***');
            var post_name = mainvalue1.split('***');
            var post_name1 = post_name['3'].split('*=*');
            var post_id = post_id['2'];
            var spl_price = mainvalue1.split('==');
            var post_price = spl_price['4'];
            var post_title = spl_price['3'];
            var select_coll = select_col['1'].split('====');
			var size_expl = mainvalue1.split('^^'); //alert(size_expl);
			var sizes = size_expl['1']; //alert(sizes);

            jQuery('.product_'+post_id).children('a').removeClass('selected_swatch');
            jQuery(this).children('a').addClass('selected_swatch');

                    var ii = 0;
                    var htmlvalue='';

                    for(ii=0;ii < img_path1.length;ii++){
                    var imagedetail1 = img_path1[ii].split('===');//alert(imagedetail1[1]);
                    if(imagedetail1[1] == selectedcolor['1'])
						{  // alert(imagedetail1[0]);
							if(imagedetail1[0]!=''){
								htmlvalue ='<img class="attachment-shop_catalog wp-post-image" src="'+imagedetail1[0]+'"></span>';
								jQuery('#thumb_'+post_id).attr("href","/product/"+post_name1[0]+"/?imageid=" + select_coll['0']+"");
								//jQuery('#title_'+post_id).attr("href","/product/"+post_name1[0]+"/?imageid=" + select_coll['0']+"");
							}
						}
                    }

			if(sizes == ''){
				jQuery('#size_'+post_id).html("<span></span><span>Out of Stock</span><div class='listing_sizearrow' id='size_"+post_id+"></div>");
			}else{
				jQuery('#size_'+post_id).html('<span></span><span>Available Sizes : '+sizes+'</span><div class="listing_sizearrow" id="size_'+post_id+'"></div>');
			}
            jQuery('.post-'+post_id+' a#thumb_'+post_id).html(htmlvalue);
            //jQuery('.bottom_cat').show();
            //hoooverimage();
            return false;
        }   
    })

 }

//code to change image through swatches on listing page//
function hoooverimage(){
	jQuery(".outer_border").mouseover(function()
	{
	var idval_img=jQuery(this).attr('id');//alert(idval_img);
	jQuery("#"+idval_img+'_one').hide();
	jQuery("#"+idval_img+'_two').css("display", "block");
	jQuery("#"+idval_img+'_wishlist').css("display", "block");
	}).mouseout(function(){
	var idval_imgg=jQuery(this).attr('id');
        jQuery("#"+idval_imgg+'_one').css("display", "block");
	jQuery("#"+idval_imgg+'_two').hide();
	jQuery("#"+idval_imgg+'_wishlist').hide();
	});
}
/*function thumbnailclick(){ //alert("thumb");
        jQuery('div.thumbnails img.attachment-shop_thumbnail').click(function(){ //alert("click");
        jQuery('.attachment-shop_thumbnail').parent().removeClass('selected');
        jQuery(this).parent().addClass('selected');
        var idselect=jQuery(this).parent().attr('id');
        var imagename=jQuery(this).attr('src');
        var imagenamelarge=jQuery(this).attr('rel');
        var imagesize=jQuery('#imagesize').val();//alert(imagesize);
        var imagesizethumb=jQuery('#imagesize_thumb').val();//alert(imagesizethumb);
        var imagesizeenlarge=jQuery('#imagesize_enlarge').val(); //alert(imagesizeenlarge);
	var imagenameNew =  imagename.replace(imagesizethumb,imagesize);//alert(imagenameNew);
	var largeimagename =  imagename.replace('-'+imagesizethumb,'');//alert(largeimagename);
        jQuery('.attachment-shop_single').attr('src',imagenameNew);//alert(imagename);
        jQuery('.attachment-shop_single').attr('data-o_src',largeimagename);
        jQuery('.woocommerce-main-image').attr('href',largeimagename);
        jQuery('#cloud-zoom-big').css('background-image','url('+largeimagename+')');//alert(largeimagename);
        jQuery('#mainimage').attr('rel',largeimagename);
        jQuery('#mainimage').addClass(idselect);
        jQuery('.addnewzoomclass >img').attr('src',imagenamelarge);
        return false;
    })
}*/

function thumbnailclick(){ //alert("called");
     jQuery('.attachment-shop_thumbnail').click(function(){ //alert("alert test"); return false;
        var imgname = jQuery(this).parent().attr('rel');//alert(imgname);
        var zoomimgname = jQuery(this).parent().attr('href');//alert("test"+zoomimgname);
        jQuery('.attachment-shop_thumbnail').parent().removeClass('selected');
        jQuery(this).parent().addClass('selected');
        jQuery('.attachment-shop_single').attr('src',imgname);       
        jQuery('.attachment-shop_single').attr('alt',zoomimgname);
	jQuery('.zoomWindow').css('background-image','url('+zoomimgname+')');//alert(largeimagename);
        jQuery('.MagicZoomBigImageCont img').attr('src',zoomimgname);
        jQuery('.MagicThumb-expanded img').attr('src',zoomimgname);//MagicZoomPlusHint*/
        return false;
    })
}

jQuery("#pa_size").change(function(){ 
             if(jQuery('#pa_size').val() != ""){
                jQuery('#size_alert_msg').hide();
             }   
   });
