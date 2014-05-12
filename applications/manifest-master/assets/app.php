<?php

?>

jQuery(document).ready(function($){


     var oo;
     oo = 'test';
     $(document.body).on('change', 'input[name="payment_method"]', function() {
          if(jQuery(this).val()=='cod'){
                       jQuery.ajax({
			type: "POST",
  			url:  '<?php echo WP_PLUGIN_URL ; ?>/ajaxvals.php',
  			data: "action=checkZipcode&zipcode="+jQuery('#billing_postcode').val(),
  			datatype:"json",
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
             } else {
            
           jQuery('#place_order').show();  
         }
     
    });
    jQuery('#billing_postcode').keyup(function(){
                          jQuery.ajax({
			type: "POST",
  			url:  'ajaxvals.php',
  			data: "action=checkZipcode&zipcode="+jQuery('#billing_postcode').val(),
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
        
    });
    
    
    
   //   jQuery('#place_order').hide();
  // alert(jQuery("input[name=payment_method]:checked").val()); 
if(jQuery("input[name=payment_method]:checked").val()=='cod'){
    
                   jQuery.ajax({
			type: "POST",
  			url:  'ajaxvals.php',
  			data: "action=checkZipcode&zipcode="+jQuery('#billing_postcode').val(),
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
   // alert('yee');

       $(window).load(function() {
       //    alert('fdf');
         //   jQuery('#place_order').hide(); 
        // this code will run after all other $(document).ready() scripts
        // have completely finished, AND all page elements are fully loaded.
    });
      
       
    //jQuery('.form-row place-order').hide();
    
   // return false;
} else {
   //s jQuery('#place_order').show();
}


$(document).ajaxComplete(function(event, xhr, settings) {
 //   alert(settings.url);
if(oo=='0'){
    jQuery('.payment_method_cod').html('We do not provide service to this pincode.');
    jQuery('#place_order').hide();
    return false;
} else {
    jQuery('.payment_method_cod').html('Pay with cash upon delivery.');
        jQuery('#place_order').show();
}


        

 });
	

	 });

           //alert('test'); 
           
    