<?php
/**
 * Abondoned cart email template
 *
 * @author WooThemes
 * @package  Eshop_email_guru/Templates/Emails
 * @version 1.2
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<!--header start-->
<?php woocommerce_get_template( 'emails/email-header.php', array( 'email_heading' => $email_heading ) );?>
<!--header ends-->

<p><?php
$support_email = get_option( 'support_email', '' );
$support_phone = get_option( 'support_phone', '' );
$website=get_bloginfo('siteurl');
$site_title = get_option( 'site_title', '' );
$user=get_user_by('id',$_SESSION['userid']);
$user_name=$user->data->display_name;
$site_url=get_bloginfo('siteurl');
$site_addr=$site_url;
$site_url=explode('http://',$site_url);
if($site_url[1] != '')
    $site_url=$site_url[1];
 $site_anchor='<a href='.$site_addr.'>'.$site_url.'</a>';
_e('Hi ','woocommerce');echo $user_name.",";  ?></p>
<p><?php _e('Thanks for your recent visit to '.$site_anchor.'. We noticed that some amazing product(s) are lying in your shopping bag.','woocommerce');?></p>
<p><?php _e('We\'re hoping you haven\'t faced any hiccups during the process.','woocommerce');?></p>
<p><?php _e('If you need help in completing the order, please do not hesitate to give us a call on <b>'.$support_phone.'</b> or email us at <b>'.$support_email.'</b>. We are here to help you find the most innovative & quality products to suit your style','woocommerce');?></p>
<p><?php _e('Also, If you have faced any problem with online payment, you can make use of our Cash-on-Delivery option, where you can pay for your order in cash once it has been delivered to you (selected PIN codes only).','woocommerce');?></p>
<?php do_action('woocommerce_email_before_order_table', $order, false); ?>
<table cellspacing="0" cellpadding="6" style="width: 100%; border-top: 1px solid #888; border-bottom: 1px solid #888; font:normal 12px Arial;" border="1" bordercolor="#eee">
	<thead>
		<tr>
			<th scope="col" style="text-align:center; border: 1px solid #eee;" style="font:normal 12px Arial; color:#555; text-align:center;"><?php _e( 'Product', 'woocommerce' ); ?></th>
			<th scope="col" style="text-align:center; border: 1px solid #eee;" style="font:normal 12px Arial; color:#555; text-align:center;"><?php _e( 'Name', 'woocommerce' ); ?></th>
			<th scope="col" style="text-align:center; border: 1px solid #eee;" style="font:normal 12px Arial; color:#555; text-align:center;"><?php _e( 'Color', 'woocommerce' ); ?></th>
			<th scope="col" style="text-align:center; border: 1px solid #eee;" style="font:normal 12px Arial; color:#555; text-align:center;"><?php _e( 'Size', 'woocommerce' ); ?></th>
			<th scope="col" style="text-align:center; border: 1px solid #eee;" style="font:normal 12px Arial; color:#555; text-align:center;"><?php _e( 'Qty', 'woocommerce' ); ?></th>
			<th scope="col" style="text-align:center; border: 1px solid #eee;" style="font:normal 12px Arial; color:#555; text-align:center;"><?php _e( 'Price', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
                <!--cart table start-->
                <?php
                    $currentid=$_SESSION[userid];
                    $site_title=get_option( 'site_title', '' );
                    $cartinfo=get_userdata( $currentid );
                    $totalprice=0;
                    $cartinfo=get_user_meta($currentid,'_woocommerce_persistent_cart', 'true');
                    foreach($cartinfo as $cart=>$items)
                    {
                        if (is_array($items))
                        {
                            foreach ($items as $item)
                            {                             
                                $pricesubtotal = $item['line_subtotal']+$item['line_tax'];
                                $product_id=$item['product_id'];
                                $variation_id=$item['variation_id'];
                                $item_color=$item['variation']['pa_color'];
                                if($item_color ==''){$item_color=$item['variation']['Color'];}
                                $item_size=$item['variation']['pa_size'];
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
                                <td style="text-align:center; vertical-align:middle; border: 1px solid #eee; word-wrap:break-word;"><?php
                                // Show title/image etc
                                //echo 	apply_filters( 'woocommerce_order_product_image', $image, $_product, $show_image);
                                echo $image;
                                // SKU
                                //echo 	($show_sku && $_product->get_sku()) ? ' (#' . $_product->get_sku() . ')' : '';
                                // Variation
                                ?></td>
                                <td style="text-align:center; vertical-align:middle; border: 1px solid #eee;">
                                <?php  // Product name
                                echo $product_name;?>
                                </td>
                                <td style="text-align:center; vertical-align:middle; border: 1px solid #eee;">
                                <?php echo $item_color;?>
                                </td>
                               <td style="text-align:center; vertical-align:middle; border: 1px solid #eee;">
                                <?php echo $item_size; ?>
                                </td>
                                <td style="text-align:center; vertical-align:middle; border: 1px solid #eee;"><?php echo $item_quantity ;?></td>
                                <td style="text-align:center; vertical-align:middle; border: 1px solid #eee;"><?php echo "INR ".$subtotal; ?></td>
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
        <td colspan="2" style="text-align:left; border: 1px solid #eee;"></td>
<td scope="row" colspan="2" style="text-align:left; border: 1px solid #eee;"><?php _e('Cart Total:','woocommerce');?></td>
<th scope="row" colspan="2" style="text-align:left; border: 1px solid #eee; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo "INR ".$totalprice; ?></th>

</tr>
            </tfoot>
</table>
<div align="right" style="margin:15px 0; margin-top: 20px">
<a href="<?php echo get_template_directory_uri() ?>/cart" target="_blank" title="Cart"><img src="http://speed.eshopbox.com/c60eb21d37943dba85bf2184c56afe72c8f616f0/Button.png" width="168" height="33" /></a>
</div>
<p><?php _e('Happy shopping with '.$site_title.' Online!  Enjoy the most appealing and responsive online shopping experience.','woocommerce');?></p>
<!--footer start-->
<?php woocommerce_get_template('emails/email-footer.php');?>
<!--footer ends-->