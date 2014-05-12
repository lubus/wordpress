jQuery(document).ready( function($) {
    $('.wpfp-link').live('click', function() {
        dhis = $(this);
        wpfp_do_js( dhis, 1 );
        // for favorite post listing page
        if (dhis.hasClass('remove-parent')) {
            dhis.parent("li").fadeOut();
        }
        return false;
    });
 jQuery('.csize').bind('click',function(){
        sizeRel =  jQuery(this).attr('rel');
        jQuery('#attribute_pa_size'+sizeRel).val(jQuery(this).val());
        
    });
    
    jQuery('.ccolor').bind('click',function(){
       sizeRel =  jQuery(this).attr('rel');
       jQuery('#attribute_pa_color'+sizeRel).val(jQuery(this).val());   
        
        
    });


 jQuery('.move_to_cart').bind('click',function(){
       ivaluerel =  jQuery(this).attr('rel');
       jQuery('#ivalue'+ivaluerel).val(ivaluerel);

    });

 jQuery('.wpfp-span').click(function()
    {
        var wishlist=jQuery('#idwishlist').html();
        jQuery('#idwishlist').html(parseInt(wishlist)+1);
    })
});

function wpfp_do_js( dhis, doAjax ) {
    loadingImg = dhis.prev();
    loadingImg.show();
    beforeImg = dhis.prev().prev();
    beforeImg.hide();
    url = document.location.href.split('#')[0];
    params = dhis.attr('href').replace('?', '') + '&ajax=1';
    if ( doAjax ) {
        jQuery.get(url, params, function(data) {
                dhis.parent().html(data);
                if(typeof wpfp_after_ajax == 'function') {
                    wpfp_after_ajax( dhis ); // use this like a wp action.
                }
                loadingImg.hide();
            }
        );
    }
}
