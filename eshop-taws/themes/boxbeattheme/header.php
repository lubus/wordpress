<?php //header("Cache-Control: no-cache, must-revalidate"); header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>
<!DOCTYPE html> 
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?> class="js svg">
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/bliss-fav.png" />
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/style_add.css" />
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/brand.css" />
<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/fav.ico" />
<?php wp_head(); ?>
 <style>
	#snowfall {
    height: auto;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    z-index: 1;
}
</style>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-47305231-1', 'phiverivers.com');
  ga('send', 'pageview');

</script>
</head>
<?php
	//if (!empty($_POST) && $_POST['countryn']!='')  changeMyCountry($_POST);
	//$countryarray=get_my_country_array(COUNTRY_IN_SESSION);
?>
<body <?php body_class(); ?>>

<div id="page" class="hfeed site">
	<header id="masthead" class="site-header" role="banner">
    <div class="header_cover" id="mainheader">
    <div class="header_site">
    <div class="extra_covdiv">
    <div class="logo_div">
    <?php $header_image = get_header_image();
		if ( ! empty( $header_image ) ) : ?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( $header_image ); ?>" class="header-image" alt="" /></a>
		<?php endif; ?>
    </div>
	

<div class="cart_itemcover ">

<div class="ship_order">
<ul>
<li class="first_li">FREE SHIPPING ON ALL ORDERS</li>
<li class="link_1"><a href="<?php echo get_permalink( 28 ); ?>" target="_blank">CUSTOMER SERVICE</a></li>
<li class="link_2"><a href="<?php echo get_permalink( 59 ); ?>" target="_blank">ORDER TRACKING</a></li>
</ul>
</div>
<!--<div class="currency-menu">
    	<div class="selector-list">
        	<ul>
            	<li class="country_block">
                <a href="#" class="default-country default_<?php echo $countryarray->country_code == '' ? 'IN' : $countryarray->country_code; ?>"><?php echo $countryarray->country_name == '' ? 'INDIA' : $countryarray->country_name; ?> <?php echo $countryarray->country_currency == '' ? 'INR' : $countryarray->country_currency; ?></a>
                <ul class="one">
				<?php
				/*$countryName = eshop_country_currency_pop();
				foreach ($countryName as $countryArr) {
					echo '<li class="'.$countryArr->country_code.'"><a class="countrylist" id="'.$countryArr->country_code.'" href="#">'.$countryArr->country_name.'</a></li>';
				}*/
				?>
            </ul>
                </li>
            </ul>
        </div>
    </div>-->
<div class="cart_item">
	<div class="expandable">
	<form id="user_country" name="user_country" action="" method="post">
		<input type="hidden" name="countryn" id="countryn">
		<!--<div class="select-parent">
			<select name="countryn" id="countryn">
				<?php
				$countryName = eshop_country_currency_pop();
				foreach ($countryName as $countryArr) {
				?>
					<option <?php if (COUNTRY_IN_SESSION == $countryArr->country_code) {
						echo 'selected="selected"';
					} ?> value="<?php echo $countryArr->country_code; ?>"><?php echo $countryArr->country_name . ' (' . $countryArr->country_currency . ')'; ?></option>
				<?php } ?>
			</select>
			<i class="icon-solid_arrow_down"></i>
		</div>-->
	</form>
</div>
<div class="cart_count pull-right forminicart_cart" style="visibility:hidden">
    <?php   dynamic_sidebar('minicart');  ?>
</div> <!-- extra div added -->
</div>

<!--<div class="tracking_div">INR 0,00</div>-->

</div>
</div>

<div class="clear"></div>
</div>
		
</div>
        
       <div class="header_navigation">		        
        <nav id="site-navigation" class="main-navigation" role="navigation">
        		<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?>
                
                
        		</nav>
                <?php dynamic_sidebar ('drop_downmenu') ?>
                
                
</div> 
        </div>
        
     
    
    <?php //dynamic_sidebar ('nav-hover'); ?> 
        
	</header><!-- #masthead -->

	<?php if(!is_front_page() && !is_archive ()) {?>
	<div id="main" class="wrapper">
    <?php } ?>
	<?php if(is_front_page() || is_archive() )   {?>
	<div class="wrapper_home">
    <?php } ?>