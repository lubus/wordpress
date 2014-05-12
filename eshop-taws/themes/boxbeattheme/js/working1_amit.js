// JavaScript Document

// hover and click  function

jQuery(document).ready(function(){
								
		jQuery("ul.ul_cls a, ul.tags_ul a ").hover(
			function() {
				 attr_val = jQuery(this).attr('rel');
				 jQuery('div.section2 a:not(:first)').hide();				 
				 jQuery('div a.'+attr_val).show();  
			}
		);
		
		jQuery("div.close_menu a").click(
			function() { 
				jQuery('div.dropdown_cover').slideUp('slow');
			}
		);	
		
		jQuery("li.womens_cls a").click(
			function() { 				
				 jQuery(this).toggleClass( "fixed" );
				jQuery('div.dropdown_cover').slideToggle('slow');
				return false;
			}
		);	
		   
});



								
     								
								
								

