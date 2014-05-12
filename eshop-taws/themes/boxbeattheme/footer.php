<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package BoxBeat
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>
<div class="clear"></div>
</div><!-- #main .wrapper -->
    </div><!-- #page -->
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/general.js"></script>
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/cache-data.js"></script>
	<footer id="colophon" role="contentinfo">    
    <?php if(is_page ( 1733 )) { ?>
    <div class="checkout_footercover">
    <div class="checkout_footer">
    <ul>
    <li class="first_li">
    <h2>SAFE SHOPPING GUARANTEED</h2>
    <p>All transactions on this website is <br/>
safe and secure.</p>
    
    </li>
    <li class="mid_li">
    <h2>ALL PAYMENT OPTIONS</h2>
    <p>Multiple payment options available <br/>
including Cash on delivey</p>
    
    </li>
    <li class="last_li">
    <img src="http://speed.eshopbox.com/bli003520cc6069967bc60284e457a9fe095ba49/paymentmethod_icon.png" alt=""  />
    </li>
    
     </ul>
    
    </div>
    
    </div>
    
    <?php } ?>
    
	<div class="footer_contentcover">
    <div class="footer_content">
    <?php if(is_page(6)) { ?>
    <div class="checkout_footercover">
    <div class="checkout_footer">
    <ul>
    <li class="first_li">
    <h2>SAFE SHOPPING GUARANTEED</h2>
    <p>All transactions on this website is <br>
safe and secure.</p>
    
    </li>
    <li class="mid_li">
    <h2>ALL PAYMENT OPTIONS</h2>
    <p>Multiple payment options available <br>
including Cash on delivey</p>
    
    </li>
    <li class="last_li">
    <img src="http://speed.eshopbox.com/bli003520cc6069967bc60284e457a9fe095ba49/paymentmethod_icon.png" alt="">
    </li>
    
     </ul>
    
    </div>
    
    </div>
    <?php } ?>
    
    <div class="support_block">
    <ul>
    <li class="one_sup">
    <h4><span class="span_1"></span>NEED HELP</h4>
    <p>Contact : <span>0124-2212209</span><br/>
    Email : <span>support@phiverivers.com</span>
    </p>
    </li>
    <li class="two_sup"><h4><span class="span_2"></span>CASH ON DELIVERY</h4>
    <p>
    Available in all major locations<br/> 
(350+ cities) across India.
    </p>
    
    </li>
    <li class="three_sup">
    <h4><span class="span_3"></span>FREE SHIPPING</h4>
    <p>Available in all major locations <br/>
(350+ cities) across India.</p>
    </li>
    
    </ul>
    <div class="clear"></div>
    </div>
    
    
    <div class="footer_contenttop">
<div class="center_footer_contenttop">    
    <?php dynamic_sidebar ('sidebar-4'); ?>
    
    <div class="newsletter_dv">
   <a href="https://www.facebook.com/PhiveRiversIN?ref=br_tf" class="face_cls" target="_blank"></a>
    <a href="https://twitter.com/phive_rivers" class="twitter_cls" target="_blank"></a>
     <a href="http://www.pinterest.com/phiverivers/" class="pinterest_cls" target="_blank"></a>

    <?php dynamic_sidebar ('sidebar-5'); ?>
    
    </div>
    <div class="clear"></div>
    </div>
    <div class="clear"></div>
    </div>
    <div class="footer_contentbottom">
      <div class="center_footer_contenttop">
      <div class="footer_conleft">
      &copy; 2013 Phive Rivers . Powered by Powered by <a href="http://eshopbox.com/" target="_blank"></a>
      </div>
      <div class="footer_conright">
      <ul>

      <li> POLICIES : <a href="<?php echo get_permalink( 71 ); ?>" target="_blank">terms of use</a></li>
      <li><a href="<?php echo get_permalink( 72 ); ?>" target="_blank">Privacy</a></li>
      <li class="last"><a href="<?php echo get_permalink( 316 ); ?>" target="_blank">Disclaimer</a></li>
      
      </ul>
      
      </div>
      <div class="clear"></div>
         </div> 
    
    </div>
    
    <div class="footer_contentbot1">
    <div class="center_footer_contenttop">
    <div class="footer_contentbot1left">
    PAYMENT METHOD
    <span></span>
    </div>
    <div class="footer_contentbot1right">
    SHIPPING THROUGH
    <span></span>
    </div>
    <div class="clear"></div>
    </div>
    </div>
    
    </div>
    
    
    
    </div>
    
    
    	
	</footer><!-- #colophon -->
<script type="text/javascript" src="<?php echo bloginfo('template_url'); ?>/js/magiczoomplus.js"></script>
<link href="<?php echo bloginfo('template_url'); ?>/css/magiczoomplus.css" rel='stylesheet' type='text/css' />
<?php if (is_single()) { ?>				
<script src="<?php echo get_template_directory_uri() ?>/js/jquery.jcarousel.min.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#mycarousel').jcarousel();
	});
</script>	
<?php } ?>

<script type="text/javascript">



jQuery(document).ready(function(){

jQuery('#shiptobilling-checkbox').change(function(){

if(this.checked)

jQuery('.ordershippingblock').fadeIn('slow');


else

jQuery('.ordershippingblock').fadeOut('slow');

});
});
</script>
<script type="text/javascript">

jQuery(document).ready(function(){

 jQuery(".faq-section").click(function () {

    if(false == jQuery(this).children('div').is(':visible')) {

        jQuery('.faq-section div').slideUp(300);

		jQuery(this).removeClass('open');

    }

    jQuery(this).children('div').slideToggle(300);

		if(jQuery(this).hasClass('open')){

			jQuery(this).removeClass('open');							 

		}else{

			 jQuery(this).addClass('open');

		}

	});

 jQuery('.faq-section div').hide();

  });

</script>
<script src="<?php echo get_template_directory_uri() ?>/js/working1_amit.js"></script>

<?php wp_footer(); ?>
</body>
</html>
