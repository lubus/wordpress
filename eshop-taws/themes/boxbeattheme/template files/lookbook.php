<?php
/**
 * Template Name: LookBook
 *
 * Description: A page template that provides a key component of BoxBeat as a CMS
 * by meeting the need for a carefully crafted introductory page. The front page template
 * in Twenty Twelve consists of a page content area for adding text, images, video --
 * anything you'd like -- followed by front-page-only widgets in one or two columns.
 *
 * @package BoxBeat
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>
	<div id="primary" class="site-content">
		<div id="content" role="main">
			<div>
<div id="main" role="main">
      
      <?php dynamic_sidebar('lookbook') ?>
    </div>
</div>
		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar( 'front' ); ?>
<?php get_footer(); ?>

<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "ur-3ba2a932-9c6a-6b31-7f95-b6ed68f6dea", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>

<script type="text/javascript">
    //$(function(){
      //SyntaxHighlighter.all();
    //});
    jQuery(document).ready(function(){
      jQuery('.flexslider').flexslider({
        animation: "slide",
        controlNav: "thumbnails",
        start: function(slider){
          jQuery('body').removeClass('loading');
        }
      });
    });
  </script>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/thumb-slider.css"/>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/thumb-slider.js"></script>