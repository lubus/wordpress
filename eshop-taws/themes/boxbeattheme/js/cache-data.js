/*-----------------------------------------------------------------------------------*/
/* GENERAL SCRIPTS */
/*-----------------------------------------------------------------------------------*/
jQuery(document).ready(function(){
	jQuery.ajax
	({
	  type: "POST",
	  url: "/header-data.php?type=getcartdata",
	  dataType: 'html',
	  cache: false,
	  success: function(datahtml)
	  {
		if(datahtml !=''){
			jQuery(".forminicart_cart").html(datahtml);
		}
		jQuery(".forminicart_cart").css('visibility','visible');
	  }
	});
	
});