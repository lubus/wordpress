<?php
/*
Plugin Name:Dropdown Cart Widget
Description: Subtly modifies the Cart Widget and makes it dropdown - nice in the header see :)
Version: 1.0
Author: Boxbeat Technologies Pvt Ltd
Author URI: http://theboxbeat.com/
Text Domain: eshopbox
Domain Path: /i18n/languages/
*/ 
include_once($_SERVER['DOCUMENT_ROOT'].'/eshopbox/wp-load.php' );
class eshopbox_Widget_DropdownCart extends WP_Widget {

	/** Variables to setup the widget. */
	var $woo_widget_cssclass;
	var $woo_widget_description;
	var $woo_widget_idbase;
	var $woo_widget_name;

	/** constructor */
	function eshopbox_Widget_DropdownCart() {

		/* Widget variable settings. */
		$this->woo_widget_cssclass 		= 'widget_dropdown_cart';
		$this->woo_widget_description 	= __( 'Display the users Shopping Cart in the header/sidebar.', 'woothemes' );
		$this->woo_widget_idbase 		= 'widget_dropdown_cart';
		$this->woo_widget_name 			= __('eshopbox Drop Down Shopping Cart', 'woothemes' );

		/* Widget settings. */
		$widget_ops = array( 'classname' => $this->woo_widget_cssclass, 'description' => $this->woo_widget_description );

		/* Create the widget. */
		$this->WP_Widget('dropdown_shopping_cart', $this->woo_widget_name, $widget_ops);
	}

	/** @see WP_Widget */
	function widget( $args, $instance ) {
		global $eshopbox;

               $cartcount= $eshopbox->cart->cart_contents_count;
               if($cartcount>0){
                   $carticon='carticon_full';}
               else{
                   $carticon='carticon';
               }
		//if (is_cart()) return;
               $minicart;
               if($cartcount>3){
                   $tempclass='scroller';
               }
		extract($args);
		$title = $instance['title'];
		$title = apply_filters('widget_title', $title, $instance, $this->id_base);

		$minicart= $before_widget;
		if ( $title )$minicart.= $before_title . $title . $after_title;
//                if (is_cart())
//                {
//                    $cartlink='<a ><span class="countblock">'.$eshopbox->cart->cart_contents_count.'</span><span class="'.$carticon.'"></span></a>';
//                }
               // else
               // {
                    $cartlink='<a href="/cart/"><span class="countblock">Shopping Bag ( <span class="minicart_count">'.$eshopbox->cart->cart_contents_count.'</span> ) </span><span class="'.$carticon.'">'.$eshopbox->cart->get_cart_total().'</span></a>';
               // }
                $minicart.= $minicart_listart= $cartlink."<div id='cartdropdown'>";

		
		$minicart.= '<div class="cartdropdown_container">';
//                $minicart.='<div class="cartdd_catblock">
//                                    <div class="cartdd_catcontent">Stuff in your cart can sell out before you buy it. Check out before Factory Rush mob grab it all !!	</div>
//                                    <div class="cartdd_caticon"></div>
//                                </div>';
                //$minicart.='<div class="ddarrow"></div>';
               // $minicart.='<div class="cartdd_topblock">Your Cart / <span class="cart_count_inner">'.$eshopbox->cart->cart_contents_count.' Item</span></div>    ';
                $minicart.='<div class="cartdd_bottomblock">';               
                $minicart.='<ul class="'.$tempclass.'">';
                if (sizeof($eshopbox->cart->cart_contents)>0) :

              $total=sizeof($eshopbox->cart->cart_contents);
                //echo "<pre>";print_r($eshopbox->cart->cart_contents);exit;
              $eshopbox->cart->cart_contents =  ($eshopbox->cart->cart_contents);
		$i = 0;
                $k=1;
                $path=plugins_url().'/eshopbox-drop-down-cart-widget/widget-update-cart.php';
              $cart_reverse_content= array_reverse($eshopbox->cart->cart_contents);
		foreach ($cart_reverse_content as $cart_item_key => $cart_item) :               
                        $i++;                        
			$_product = $cart_item['data'];
                        
			if ($_product->exists() && $cart_item['quantity']>0) :
				$len=strlen($_product->get_title());
				if($len>15)
					$title=substr($_product->get_title(),0,15)."....";
				else
					$title=$_product->get_title();				
                                $minicart.= '<li class="proloop_'.$i.'" id="'.$cart_item_key.'">';
                                    $minicart.='<div class="imageblock">';
                                        $minicart.= '<a href="'.get_permalink($cart_item['product_id']).'">';
                                        if (has_post_thumbnail($cart_item['product_id'])) :
                                                $minicart.= get_the_post_thumbnail($cart_item['product_id'], 'shop_thumbnail');
                                        else :
                                                $minicart.= '<img src="'.$eshopbox->plugin_url(). '/assets/images/placeholder.png" alt="Placeholder" width="'.$eshopbox->get_image_size('shop_thumbnail_image_width').'" height="'.$eshopbox->get_image_size('shop_thumbnail_image_height').'" />';
                                        endif;
                                        $minicart.= '</a>';
                                        $current_cat=  wp_get_post_terms( $cart_item['product_id'], 'product_cat' );
                                        foreach($current_cat as $temp=>$key){
                                            if($key->parent=='0'){
                                                                         $current_cat=$key->slug;
                                            }
                                        }
                                        $taxonomy_name='pa_'.$current_cat.'-size';
                                        $product_price = get_option('eshopbox_tax_display_cart') == 'excl' ? $_product->get_price_excluding_tax() : $_product->get_price_including_tax();
                                        $product_price_net=$product_price*$cart_item['quantity'];
                                        $shipping_cost = get_option( 'eshopbox_flat_rate_settings' );
                                     //   echo "hellll<pre>";print_r($shipping_cost);echo "</pre>";
                                        $minicart.='</div>
                                        <div class="contentblock">
                                        	<div class="title">';
                                                            $minicart.= '<a href="'.get_permalink($cart_item['product_id']).'">';
                                                            $minicart.= $_product->get_title();
                                                            $minicart.= '</a>';
                                                            $shipping_cost_net=$shipping_cost['cost']*$cart_item['quantity'];

//                                                  <div class="size_qtyblock">
//                                                  <div class="ddsizeblock">Size : '.$cart_item['variation'][$taxonomy_name].' </div>
//                                                  <div class="ddqtyblock">Qty :'.$cart_item['quantity'].'</div> </div>
                                                            $minicart.='</div>
                                            <div class="skucode">Style: '. $_product->get_sku().'</div>
                                            
                                           
                                            <div class="priceblock"> INR '.$product_price_net.'</div>
											<div class="removeblock" id="'.$i.'"><a style="cursor:pointer;" title="Remove this Item">x</a></div>
                                        </div>';
                                        
				$minicart.= '</li>';
			endif;
                        $k++;
		endforeach;
	else:
		$minicart.= '<li class="empty_bag">'.__('Your Shopping Cart is currently empty', 'woothemes').'</li>';
	endif;
	$minicart.= '</ul>';
	$minicart.= '</div>';
	$minicart.= '</div>';
	if (sizeof($eshopbox->cart->cart_contents)>0) :

                $minicart.=' <div class="cartdd_viewdetail"><a href="/cart/">View Shopping Bag</a></div>';
	endif;
                $minicart.=  '</div>' ;
		$minicart.=  '</div>';		
		$minicart.=  $after_widget;                
                echo $minicart;
	}

	/** @see WP_Widget->update */
	function update( $new_instance, $old_instance ) {
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		return $instance;
	}

	/** @see WP_Widget->form */
	function form( $instance ) {
	?>
	<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'woothemes') ?></label>
	<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" value="<?php if (isset ( $instance['title'])) {echo esc_attr( $instance['title'] );} ?>" /></p>
	<?php
	}

} // class eshopbox_Widget_DropdownCart



//
//function custom_mini_cart_header_code()
//{
//    global $class;
//global $cartlink;
//global $minicart_listart;
//global $minicart_liend;
////session_start();
////echo $_SESSION['cartvalue']."====";
////global $wp_query;
//
////$this_post=$wp_query->post->post_name;
//
//
//    if($_GET['customajaxaddtocart']>0)
//    {
//        $class="style='visibility:visible'";
//        //$_SESSION['cartvalue']=1;
//
//    }
//    else
//    {
//        $class="style='visibility:hidden'";
//        //$class='style="display:none;"';
//        //$_SESSION['cartvalue']=0;
//    }
//
////
//    global $eshopbox;
//     $cartcount=$eshopbox->cart->cart_contents_count;
//
//     if( $cartcount ==0)
//     {
//         $cartlink='<a class="mybag">SHOPPING BAG <span class="cartcount">'.$cartcount.'</span></a>';
//     }
//     else
//     {
//		$cartlink='<a href="/cart"  class="mybag">SHOPPING BAG <span class="cartcount">'.$cartcount.'</span></a>';
//     }
//     $minicart_listart= $cartlink."<div id='minicart' ".$class.">";
//
//}
   // add_action('wp_head','custom_mini_cart_header_code');


// REGISTER THE WIDGET
	function woocomm_dropdowncart_load_widgets() {

  		register_widget('eshopbox_Widget_DropdownCart');

	}
	add_action('widgets_init', 'woocomm_dropdowncart_load_widgets');

	// Load the CSS
	add_action('wp_print_styles', 'dropdowncart_stylesheet',0);
	// Load the jScript
       
	add_action('wp_enqueue_scripts', 'dropdowncart_scripts',0);
       
	/**
	 * Load the CSS
	 **/

	function dropdowncart_stylesheet() {

		// Respects SSL, Style.css is relative to the current file
	       $dropdowncart_stylesheet_url  = plugins_url('widget-flyout-cart-style.css', __FILE__);
		   $theme_stylesheet_file		 = get_stylesheet_directory() . '/eshopbox-drop-down-cart-widget/widget-flyout-cart-style.css';
		   $theme_stylesheet_url		 = get_stylesheet_directory_uri() . '/eshopbox-drop-down-cart-widget/widget-flyout-cart-style.css';
		   $css = file_exists($theme_stylesheet_file) ? $theme_stylesheet_url : $dropdowncart_stylesheet_url;
		   wp_register_style('dropdowncart_stylesheets', $css);
	       wp_enqueue_style( 'dropdowncart_stylesheets');



	} // END dropdowncart_stylesheet

	/**
	 * Load the jscript
	 **/
	function dropdowncart_scripts() {

		// Respects SSL, Style.css is relative to the current file
	       $dropdowncart_jscript_url  = plugins_url('widget-flyout-cart-script.js', __FILE__);
	       $dropdowncart_jscript_file = WP_PLUGIN_DIR . '/dropdown-cart-widget/widget-flyout-cart-script.js';
		   if ( file_exists( $dropdowncart_jscript_file ) ) :

		   // register your script location, dependencies and version
		   wp_register_script('dropdown_cart',$dropdowncart_jscript_url,array('jquery'),'','');
		  // enqueue the script
                   
		  wp_enqueue_script('dropdown_cart');

		  endif;

	} // END dropdowncart_scripts
function custom_detail_page_code_ajaxaddtocart(){
    global $product;
    $current_cat=  wp_get_post_terms( $product->id, 'product_cat' );
    foreach($current_cat as $temp=>$key){
        if($key->parent=='0'){
             $current_cat=$key->slug;
        }
    }
    $pro_type=$product->product_type;
    $ajaxproduct_id=$product->id; 
    $path=plugin_dir_url( __FILE__ ).'widget-update-cart.php';
    echo "<input type='hidden' class='ajax_pro_id' value='".$ajaxproduct_id."'/>";
    echo "<input type='hidden' class='ajax_pro_path' value='".$path."'/>";
    echo "<input type='hidden' class='ajax_pro_type' value='".$pro_type."'/>";
    echo "<input type='hidden' class='current_parent_cat' value='".$current_cat."'/>";
}
add_action('eshopbox_before_add_to_cart_button','custom_detail_page_code_ajaxaddtocart');
