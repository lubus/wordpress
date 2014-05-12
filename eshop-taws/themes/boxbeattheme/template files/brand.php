<?php /*Template Name:BrandPage Page */ ?>

<?php get_header(); ?>

<div class="BrandContent">
		

    <?php dynamic_sidebar('brand') ?>
<!--    <section class="news-letter">
    		<span>
    		<h3>phive rivers newsletters</h3>
    	<?php //dynamic_sidebar('subscriber') ?>
        </span>
    </section>-->
</div>

<?php get_footer(); ?>

  <script type="text/javascript">
//    jQuery(function(){
//      SyntaxHighlighter.all();
//    });
    jQuery(window).load(function(){
      jQuery('.flexslider').flexslider({
        animation: "slide",
        start: function(slider){
          jQuery('body').removeClass('loading');
        }
      });
    });
  </script>
<script src="<?php echo get_template_directory_uri(); ?>/js/flexslider.js" type="text/javascript"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.jcarousel.min.js" type="text/javascript"></script>
  <script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#mycarousel1').jcarousel();
	});
</script>

<!--        <div class="flexslider">
          <ul class="slides">
            <li>
  	    	    <img src="http://speed.eshopbox.com/phiveriverc6069967bc60284e457a9fe095ba49/banner-1.jpg" />
  	    		</li>
  	    		<li>
  	    	    <img src="http://speed.eshopbox.com/phiveriverc6069967bc60284e457a9fe095ba49/banner-1.jpg" />
  	    		</li>
  	    		<li>
  	    	    <img src="http://speed.eshopbox.com/phiveriverc6069967bc60284e457a9fe095ba49/banner-1.jpg" />
  	    		</li>
  	    		<li>
  	    	    <img src="http://speed.eshopbox.com/phiveriverc6069967bc60284e457a9fe095ba49/banner-1.jpg" />
  	    		</li>
          </ul>
        </div>-->