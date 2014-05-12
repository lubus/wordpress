<?php 
//filter to add store specific constants to the general setting tab of wordpress
add_filter('admin_init', 'my_general_settings_register_fields');
function my_general_settings_register_fields()
{
    //support email-the support email address of the store
    register_setting('general', 'support_email', 'esc_attr');
    add_settings_field('support_email', '<label for="support_email">'.__('Store Support Email' , 'support_email' ).'</label>' , 'my_general_settings_fields_html_support_email', 'general');

    //support phone- the support contact no of the store
    register_setting('general', 'support_phone', 'esc_attr');
    add_settings_field('support_phone', '<label for="support_phone">'.__('Store Support Contact No' , 'support_phone' ).'</label>' , 'my_general_settings_fields_html_support_phone', 'general');

     //support team- the support team name  of the store
    register_setting('general', 'support_team', 'esc_attr');
    add_settings_field('support_team', '<label for="support_team">'.__('Store Support Team Name' , 'support_team' ).'</label>' , 'my_general_settings_fields_html_store_team', 'general');

    //Store facebook url
    register_setting('general', 'store_fb', 'esc_attr');
    add_settings_field('store_fb', '<label for="store_fb">'.__('Store Facebook Url' , 'store_fb' ).'</label>' , 'my_general_settings_fields_html_store_fb', 'general');

    //Store twitter url
    register_setting('general', 'store_twitter', 'esc_attr');
    add_settings_field('store_twitter', '<label for="store_twitter">'.__('Store Twitter url' , 'store_twitter' ).'</label>' , 'my_general_settings_fields_html_store_twitter', 'general');

    //Store pininterest url
    register_setting('general', 'store_pinterest', 'esc_attr');
    add_settings_field('store_pinterest', '<label for="store_pinterest">'.__('Store Pinterest Url' , 'store_pinterest' ).'</label>' , 'my_general_settings_fields_html_store_pinterest', 'general');
}

function my_general_settings_fields_html_support_email()
{
    $support_email = get_option( 'support_email', '' );
    echo '<input type="text" id="support_email" name="support_email" value="' . $support_email . '" />';

}
function my_general_settings_fields_html_support_phone()
{
     $support_phone = get_option( 'support_phone', '' );
    echo '<input type="text" id="support_phone" name="support_phone" value="' . $support_phone . '" />';
}
function my_general_settings_fields_html_store_team()
{
    $support_team = get_option( 'support_team', '' );
    echo '<input type="text" id="support_team" name="support_team" value="' . $support_team . '" />';
}

function my_general_settings_fields_html_store_fb()
{
    $store_fb = get_option( 'store_fb', '' );
    echo '<input type="text" id="store_fb" name="store_fb" value="' . $store_fb . '" />';
}

function my_general_settings_fields_html_store_twitter()
{
     $store_twitter = get_option( 'store_twitter', '' );
    echo '<input type="text" id="store_twitter" name="store_twitter" value="' . $store_twitter . '" />';
}
function my_general_settings_fields_html_store_pinterest()
{
    $store_pinterest = get_option( 'store_pinterest', '' );
    echo '<input type="text" id="store_pinterest" name="store_pinterest" value="' . $store_pinterest . '" />';
}


//eshop custom function to change breadcrumb saperator
/*function eshop_changed_breadcrumb_defaults($defaults)
{
$defaults['delimiter'] = " &gt; ";
////whatever delimiter you want
return $defaults;
}*/
//add_filter( 'eshopbox_breadcrumb_defaults', 'eshop_changed_breadcrumb_defaults');

//remove_action( 'eshopbox_before_main_content', 'eshopbox_breadcrumb', 20, 0 );
//remove_action( 'eshopbox_before_main_content1', 'eshopbox_breadcrumb', 20, 0 );


add_filter( 'single_add_to_cart_text', 'yoursite_single_cart_text', 10, 1 );
function yoursite_single_cart_text( $button_text ) {
	$button_text = 'Add to Shopping Bag';
	return $button_text;
}


// tabs code

/**
	 * eshopbox custom tabs Start
	 *
	 * @see eshopbox_eshop_product_tabs()
	 */

if ( ! function_exists( 'eshopbox_product_description_tab' ) ) {
/**
* Output the description tab content.
*
* @access public
* @subpackage	Product/Tabs
* @return void
*/
	function eshopbox_product_description_tab() {
	eshopbox_get_template( 'single-product/tabs/description.php' );
	}
}
if ( ! function_exists( 'eshopbox_product_returns_tab' ) ) {
/**
* Output the description tab content.
*
* @access public
* @subpackage	Product/Tabs
* @return void
*/
	function eshopbox_product_returns_tab() {
	eshopbox_get_template( 'single-product/tabs/returns.php' );
	}
}
if ( ! function_exists( 'eshopbox_product_shipping_tab' ) ) {
/**
* Output the description tab content.
*
* @access public
* @subpackage	Product/Tabs
* @return void
*/
	function eshopbox_product_shipping_tab() {
	eshopbox_get_template( 'single-product/tabs/care_info.php' );
	}
}

if ( ! function_exists( 'eshopbox_eshop_product_tabs' ) ) {
/**
* Add default product tabs to product pages.s
*
* @access public
* @param mixed $tabs
* @return void
*/
function eshopbox_eshop_product_tabs( $tabs = array() ) {
global $product, $post;
// Description tab - shows product content
if ( $post->post_content )
$tabs['description'] = array(
'title'    => __( 'Description', 'eshopbox' ),
'priority' => 5,
'callback' => 'eshopbox_product_description_tab'
);
// Returns tab - shows product content
if ( $post->post_content )
$tabs['testing'] = array(
'title'    => __( 'DELIVERY & RETURNS', 'eshopbox' ),
'priority' => 15,
'callback' => 'eshopbox_product_returns_tab'
);
// Shipping tab - shows product content
if ( $post->post_content )
$tabs['shipping'] = array(
'title'    => __( 'CARE INFO', 'eshopbox' ),
'priority' => 10,
'callback' => 'eshopbox_product_shipping_tab'
);
return $tabs;
}
}
remove_action( 'eshopbox_product_tabs', 'eshopbox_default_product_tabs' );
add_action( 'eshopbox_product_tabs', 'eshopbox_eshop_product_tabs' );

/**
	 * eshopbox custom tabs End
	 *
	 * @see eshopbox_eshop_product_tabs()
*/

//-- Get dynamic sider bage content-------
function get_dynamic_sidebar($index = 1)
{
	$sidebar_contents = "";
	ob_start();
	dynamic_sidebar($index);
	$sidebar_contents = ob_get_clean();
	return $sidebar_contents;
}

// related products

function eshopbox_output_related_products() {
eshopbox_related_products(7,7); // Display 4 products in rows of 2
}


add_filter( 'eshopbox_billing_fields', 'wc_npr_filter_billing', 20, 1 );
add_filter( 'eshopbox_shipping_fields', 'wc_npr_filter_shipping', 20, 1 );
function wc_npr_filter_billing( $address ) {
	 $address['billing_address_1']['type']='textarea';
	 $address['billing_address_1']['placeholder']='Street Address'; 
	 $address['billing_email']['placeholder']='Email Address';
	 $address['billing_phone']['placeholder']='Mobile Number';
	 $address['shipping_email']['placeholder']='Email Address';
	 $address['shipping_phone']['placeholder']='Mobile Number';
return $address;
}
function wc_npr_filter_shipping( $address ) {
	 $address['shipping_email']['placeholder']='Email Address';
	 $address['shipping_address_1']['type']='textarea';
	 $address['shipping_phone']['placeholder']='Mobile Number';
	 $address['shipping_email']['label']='Email Address';
	 $address['shipping_phone']['label']='Phone';
return $address;
}





/*remove add to cart button or select option button*/
function remove_loop_button(){
remove_action( 'eshopbox_after_shop_loop_item', 'eshopbox_template_loop_add_to_cart', 10 );
}
add_action('init','remove_loop_button');





?>