jQuery(document).ready(function(){   
    //jQuery('#attribute_pa_size').val('');
	
    jQuery('.forminicart_cart').mouseenter(function(){     
        var cartcount=jQuery('.minicart_count').html();
        if(cartcount !=0){
	jQuery("#cartdropdown").css('visibility',  'visible');
	jQuery("#cartdropdown").show();
        jQuery("#cartdropdown").css('opacity','1');
 		closeclick();
        }
    });
    jQuery('.forminicart_cart').mouseleave(function(){
	jQuery("#cartdropdown").css('visibility',  'hidden');
	jQuery("#cartdropdown").hide();
        jQuery("#cartdropdown").css('opacity','0');
    
    });
    jQuery('#cartdropdown').mouseenter(function()
    {
        jQuery("#cartdropdown").css('visibility',  'visible');
	jQuery("#cartdropdown").show();
        jQuery("#cartdropdown").css('opacity',  '1');
	closeclick();
    });
    
    
jQuery('.single_add_to_cart_button').click(function()
    {   
	jQuery(this).html('Processing.....');
   /*     var quant=jQuery('.input-text').val();
        if(!quant){ var quant=1;}
        var proid=jQuery('.ajax_pro_id').val();
        var var_id=jQuery('[name=variation_id]').val();
        var protype=jQuery('.ajax_pro_type').val();
        if(protype == 'variable'){
        if(var_id=='')
         return false;
        }  
        var path=jQuery('.ajax_pro_path').val();
     
        jQuery.ajax({
                    type: 'GET',
                    url: path,
                    data: 'customajaxaddtocart=true&proid='+proid+'&varid='+var_id+'&protype='+protype+'&quantity='+quant,
                    dataType: "html",
                    async:true,
                    success: function(data)
                                {                                 
                                    var datasplit=data.split('**');     
                                    var minicart=datasplit[0];
                                    var cart_count=datasplit[1];
                                    var title=datasplit[2];
                                    var size=datasplit[3];
                                    var qty=datasplit[4];
                                    var price=datasplit[5];
                                    var add=datasplit[6];
                                    if(add == '1')
                                    {
                                        jQuery('.forminicart_cart').html(minicart);
                                        jQuery('.minicart_count').html(cart_count);
                                        jQuery("#cartdropdown").show();
                                        jQuery("#cartdropdown").css('visibility', 'visible');
                                        jQuery("#cartdropdown").css('display',  'block');
                                        jQuery("#cartdropdown").delay(6000).fadeOut(500);     
                                    }
                                    closeclick();
                                }
                                  
                                    
                               
        });
        //var cartcount=jQuery('.cartcount').html();

        return false;*/
    });
	//addtobaglist();
});


function closeclick()
{
    jQuery('.removeblock').click(function()
    {
        var i=jQuery(this).attr('id');
        //var path=jQuery('.ajax_pro_path').val();
	var path='http://phiverivers.com/eshop-phiverivers/applications/dropdown-cart-widget/widget-update-cart.php';
        var cart_key=jQuery('.proloop_'+i).attr('id');	
        jQuery.ajax(
        {
            type:'GET',
            url:path,
            data:'removecart=true&remcart_key='+cart_key,
            success:function(data)
            {
                var datasplit=data.split('******');
                jQuery('.forminicart_cart').html(datasplit[0]);
                jQuery('.minicart_count').html(datasplit[1]);
                jQuery('.amount').html(datasplit[2]);
                closeclick();
                return false;  
            }
        });
    });
}
function cartrefresh(){
                var path=jQuery('.ajax_pro_path').val();
		//var path='http://factoryrush.com/eshop-frontent/applications/dropdown-cart-widget/widget-update-cart.php';
	   jQuery.ajax({
                    type: 'GET',
                    url: path,
                    data: 'cartrefresh=yes',
                    dataType: "html",
                    async:true,
                    success: function(data)
                                {
                                    jQuery('.single_add_to_cart_button').removeClass('loading');
                                    var datasplit=data.split('**');
                                  //  alert(data+"&&&&&&&&&&&&&"+datasplit[1]);
                                    var minicart=datasplit[0];
                                    var cart_count=datasplit[1];
                                    //var title=datasplit[2];
                                   // var size=datasplit[3];
                                   // var qty=datasplit[4];
                                   // var price=datasplit[5];
                                   // var add=datasplit[6];
                                    if(cart_count=='')cart_count=0;
                                        jQuery('.forminicart').html(minicart);
                                        jQuery('.countblock').html(cart_count);
                                        jQuery('.cart_count_inner').html(cart_count);
                                       // jQuery('.detailcartpopup_protitle').html('<h3>'+title+'</h3>');
                                        //jQuery('.detailcartpopup_prosize').html('<h2>Size : '+size+'/  Qty :'+qty+'</h2>');
                                        //jQuery('.detailcartpopup_proprice').html('<h4> INR '+price+'</h4>');
                                        //jQuery('#detailcartpopup').show();
                                        //Cancel the link behavior
                                        var id = jQuery(this).attr('href');
                                      
                                   closeclick();
                                    return false;
                                }
        });


}
