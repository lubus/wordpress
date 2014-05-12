<?php
/**
 * Abondoned cart email template
 *
 * @author WooThemes
 * @package  Eshop_email_guru/Templates/Emails
 * @version 1.2
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php 
global $eshopbox,$order;?>
<!--header start-->
<?php eshopbox_get_template('emails/email-header.php');?>
<!--header ends-->

<p><?php


	// Get/prep product data
	//$_product = $order->get_product_from_item( $item );

//$user_name=$_SESSION['username'];
$site_url=get_bloginfo('siteurl');
$site_url=explode('http://',$site_url);
$user=get_user_by('id',$_SESSION['userid']);
$user_name=$user->data->display_name;
if($site_url[1] != '')
    $site_url=$site_url[1];
_e('Hi ','eshopbox');echo $user_name.',';  ?></p>
<p><?php _e('Thanks for your recent visit to '.$site_url.'. We noticed that some amazing products are lying in your shopping bag.','eshopbox');?></p>
<p><?php _e('We\'re hoping you haven\'t faced any hiccups during the process.','eshopbox');?></p>
<p><?php _e('If you need help in completing the order, please do not hesitate to give us a call on '.$support_phone.' or email us at '.$support_email.'. We\'re here to help you find the most stylish & comfortable shoes to suit your lifestyle.','eshopbox');?></p>
<p><?php _e('Also, If you have faced any problem with online payment, you can make use of our Cash-on-Delivery option, where you can pay for your order in cash once it has been delivered to you (selected PIN codes only)..','eshopbox');?></p>
<?php do_action('eshopbox_email_before_order_table', $order, false); ?>
<?php //do_action( 'eshopbox_email_footer' ); ?>




<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
	<thead>
		<tr>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Product', 'eshopbox' ); ?></th>
			<!--<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php //_e( 'Color', 'eshopbox' ); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php //_e( 'Size', 'eshopbox' ); ?></th>-->
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Quantity', 'eshopbox' ); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Price', 'eshopbox' ); ?></th>
		</tr>
	</thead>
	<tbody>
<!--cart table start-->
<?php
$currentid=$_SESSION[userid];
$site_title=get_option( 'site_title', '' );
$cartinfo=get_userdata( $currentid );
$totalprice=0;
//
$cartinfo=get_user_meta($currentid,'_eshopbox_persistent_cart', 'true');

foreach($cartinfo as $cart=>$items)
{
   
    if (is_array($items))
    {
        foreach ($items as $item)
        {        
            $product_id=$item['product_id'];
            $object_color = get_the_terms($product_id, 'pa_color');
            if(is_array($object_color)==1)
                $color = array_shift(eshopbox_get_product_terms($product_id, 'pa_color', 'names'));
            $pricesubtotal = $item['line_subtotal']+$item['line_tax'];
            
            $variation_id=$item['variation_id'];
            $var_detail=get_post_meta($variation_id);         
            $item_color=$var_detail['attribute_pa_color'][0];
            //echo 'dfgvfdgdf<pre>'; print_r($color);echo '</pre>';
            $item_size=$var_detail['attribute_pa_size'][0];
            if($item_size ==''){$item_size=$item['variation']['Size'];}
            $item_quantity=$item['quantity'];
             $item_price= round($pricesubtotal,2);
            $product_name=get_the_title($product_id);
            $image=get_the_post_thumbnail($variation_id, 'thumbnail');
            $image_path=get_permalink($product_id);
            $tempprice=$item_price *$item_quantity ;
            // Get/prep product data
            $image='<a href='.$image_path.'>'.$image.'</a>';
            $subtotal = round($pricesubtotal,2);
            if($subtotal == 0)
            {
            $subtotal = 'Free';
            }

            ?>
            <tr>
            <td style="text-align:left; vertical-align:center; border: 1px solid #eee; word-wrap:break-word;"><?php

            // Show title/image etc
            //echo 	apply_filters( 'eshopbox_order_product_image', $image, $_product, $show_image);

            // Product name
          
            echo $image;
              echo 	'<br/>'.$product_name;
            if(!empty($color)){
                echo '<br/>Color : '.$color;
            }
             if($item_size != ''){
                echo '<br/>Size : '.$item_size;
            }

            // SKU
            //echo 	($show_sku && $_product->get_sku()) ? ' (#' . $_product->get_sku() . ')' : '';
            // Variation


            ?></td>
            
            <!--<td style="text-align:left; vertical-align:middle; border: 1px solid #eee;">
           
            </td>
            <td style="text-align:left; vertical-align:middle; border: 1px solid #eee;">
          
            </td>-->
            <td style="text-align:left; vertical-align:middle; border: 1px solid #eee;"><?php echo $item_quantity ;?></td>
            <td style="text-align:left; vertical-align:middle; border: 1px solid #eee;"><?php echo "INR ".$subtotal; ?></td>
            </tr>


            <?php
            $totalprice+=$tempprice;
 }
    }?>

		
	<?php 
    
}

?>
<!--cart table ends-->

</tbody>
	<tfoot><tr>
<td scope="row" colspan="2" style="text-align:left; border: 1px solid #eee;"><?php _e('Cart Total:','eshopbox');?></td>
<th scope="row" colspan="1" style="text-align:left; border: 1px solid #eee; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo "INR ".$totalprice; ?></th>

</tr>
            </tfoot>
</table>
<p><?php  _e('Happy shopping with '.$site_title.' Online!  Enjoy the most appealing and responsive online shopping experience','eshopbox');?></p>
<!--footer start-->
<?php eshopbox_get_template('emails/email-footer.php');?>
<!--footer ends-->