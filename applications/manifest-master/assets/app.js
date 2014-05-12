/*Author : Vaibhav Sharma
 *Email: vaibhavign@gmail.com 
*/

jQuery(document).ready(function($){
    var pluginPaths = ajax_object.ajax_url;
    var oo;
    var pincodes;
    
    // on change of the payment method
    $(document.body).on('change', 'input[name="payment_method"]', function() {
         if(jQuery(this).val()=='cod'){
             if(jQuery('#shiptobilling-checkbox').attr('checked')=='checked'){
                 pincodes = jQuery('#billing_postcode').val();
             } else {
                 pincodes = jQuery('#shipping_postcode').val();
             }
                  checkzipscode(pincodes);  
            } else {

          jQuery('#place_order').show();  
        }
   });   


 // on billing postcode key up   
 jQuery('#billing_postcode').keyup(function(){
     if(jQuery('#shiptobilling-checkbox').attr('checked')!='checked'){
          return false;
     } 
     pincodes = jQuery('#billing_postcode').val();
     checkzipscode(pincodes);           

});

// on shipping postcode key up
jQuery('#shipping_postcode').keyup(function(){
    pincodes = jQuery('#shipping_postcode').val();
    checkzipscode(pincodes);           

});


// on document ready check for pincode
if(jQuery("input[name=payment_method]:checked").val()=='cod'){
    if(jQuery('#shiptobilling-checkbox').attr('checked')=='checked'){
        pincodes = jQuery('#billing_postcode').val();
    } else {
        pincodes = jQuery('#shipping_postcode').val();
    }
    checkzipscode(pincodes);      
} else {
   jQuery('#place_order').show();
}


jQuery(document).ajaxComplete(function(event, xhr, settings){
    if(oo=='0'){
       jQuery('.payment_method_cod').html('We do not provide service to this pincode.');
       jQuery('#place_order').hide();
       return false;
    } else {
       jQuery('.payment_method_cod').html('Pay with cash upon delivery.');
           jQuery('#place_order').show();
    }
});
// core ajax function to check zipcode
function checkzipscode(pincodes) {
   jQuery.ajax({
            type: "POST",
            url:  pluginPaths+'/ajaxvals.php',
            data: "action=checkZipcode&zipcode="+pincodes,
            datatype:"html",
            beforeSend : function () {
                jQuery("#nocodblock").html('').show();
            },
            success: function(data) {
                    if(data==0){
                         oo=0;
                        jQuery('.payment_method_cod').html('We do not provide service to this pincode.');
                        jQuery('#place_order').hide();
                    } else {
                         oo=1;
                     jQuery('.payment_method_cod').html('Pay with cash upon delivery.');
                     jQuery('#place_order').show();   
                    }
            }
         }); 
      }

});