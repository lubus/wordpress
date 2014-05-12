<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/eshopbox/wp-load.php' );
global $eshopbox,$wp_query;
if($_GET['customajaxaddtocart']== 'true'){
$variation_id=$_GET['varid'];
$pro_type=$_GET['protype'];
$size=$_GET['size'];
$quant=$_GET['quantity'];
$variation[$taxonomy_name]=$size;
$product=get_post($_GET['proid']);
$post_meta=get_post_meta($_GET['proid']);
$pro_price=$post_meta['_price'][0];
$title=$product->post_title;

if($pro_type !='simple'){
        $add= $eshopbox->cart->add_to_cart( $_GET['proid'],$quant,$variation_id,$variation,null );
    }
else
    {
        $add= $eshopbox->cart->add_to_cart( $_GET['proid'],$quant);
    }
$cart_count =$eshopbox->cart->cart_contents_count;
$widgetobj=new eshopbox_Widget_DropdownCart();
$args=Array('name =>" First Front Page Widget Area",id => "sidebar-2", description => "Appears when using the optional Front Pagetemplate with a page set as Static Front Page",
                class => "",before_widget => "",after_widget =>"",before_title => "",after_title =>"",
                widget_id => "dropdown_shopping_cart-2",widget_name => "eshopbox Drop Down Shopping Cart"');
$instance=Array
('title => "My Bag"');
 $cart_count =$eshopbox->cart->cart_contents_count;
$widgetui=$widgetobj->widget($args, $instance);
echo $widgetui.'**'.$cart_count.'**'.$title.'**'.$size.'**'.$quant.'**'.$pro_price.'**'.$add; 

//$var_array=
//$widgetobj=new eshopbox_Widget_DropdownCart();
//$args=Array('name =>" First Front Page Widget Area",id => "sidebar-2", description => "Appears when using the optional Front Pagetemplate with a page set as Static Front Page",
//                class => "",before_widget => "",after_widget =>"",before_title => "",after_title =>"",
//                widget_id => "dropdown_shopping_cart-2",widget_name => "eshopbox Drop Down Shopping Cart"');
//$instance=Array
//('title => " "');
//$cart_count =$eshopbox->cart->cart_contents_count;
//$widgetui=$widgetobj->widget($args, $instance);
//echo $widgetui."******".$cart_count."******".$add;
}
if($_GET['removecart']=='true')
{
    global $eshopbox;
    $cart_count =$eshopbox->cart->cart_contents_count;
    if($cart_count!=1)
    {
        $quantity =0;
        $remove=$eshopbox->cart->set_quantity($_GET['remcart_key'],0);
    }
    else
    {
    $eshopbox->cart->empty_cart();
    }
    $widgetobj=new eshopbox_Widget_DropdownCart();
    $args=Array('name =>" First Front Page Widget Area",id => "sidebar-2", description => "Appears when using the optional Front Pagetemplate with a page set as Static Front Page",
        class => "",before_widget => "",after_widget =>"",before_title => "",after_title =>"",
        widget_id => "dropdown_shopping_cart-2",widget_name => "eshopbox Drop Down Shopping Cart"');
    $instance=Array
    ('title => "My Bag"');
    $widgetui=$widgetobj->widget($args, $instance);
    $cart_count =$eshopbox->cart->cart_contents_count;
    $carttotal=$eshopbox->cart->get_cart_total();
    echo $widgetui."******".$cart_count.'******'.$carttotal;
}
if($_GET['cartrefresh']=='yes'){
	$widgetobj=new eshopbox_Widget_DropdownCart();
	       $args=Array('name =>" First Front Page Widget Area",id => "sidebar-2", description => "Appears when using the optional Front Pagetemplate with a page set as Static Front Page",
		        class => "",before_widget => "",after_widget =>"",before_title => "",after_title =>"",
		        widget_id => "dropdown_shopping_cart-2",widget_name => "eshopbox Drop Down Shopping Cart"');
        $instance=Array('title => "My Bag"');
        $widgetui=$widgetobj->widget($args, $instance);
        $cart_count =$eshopbox->cart->cart_contents_count;
        echo $widgetui."**".$cart_count;
}
   ?>
