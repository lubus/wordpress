<?php
/**
 * Customer new  order email template (when payment mode is cod)
 *
 * @author 		WooThemes
 * @package 	 Eshop_email_guru/Templates/Emails
 * @version     1.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php do_action('eshopbox_email_header', $email_heading); ?>
<p><?php
$support_email = get_option( 'support_email', '' );
$support_phone = get_option( 'support_phone', '' );
$orderdetail=$order-> order_custom_fields;
$bilphnno=$orderdetail[_billing_phone];
$bilphnno= $bilphnno[0];
$bilphnno="+91".$bilphnno;
$customer_name=$order->billing_first_name;
$site_url=get_bloginfo('siteurl');
$site_url=explode('http://',$site_url);
$website=home_url();
if($site_url[1] != '')
    $site_url=$site_url[1];
_e( 'Hi ', 'eshopbox' );echo $customer_name;?></p>
<p>
    <p><?php _e('Thank you for placing an order on '.$site_url.'.','eshopbox')?></p>
<p><?php _e('To confirm the order, please verify your mobile no. first. The verification code has been sent to your mobile no. you used to place the order. Just enter the verification code on the next step of the order placing on website. ', 'eshopbox' )?></p>
<p><?php _e('In case you didn’t received your mobile verification code yet, please call on '.$support_phone.' to confirm this order.','eshopbox')?></p>
<?php do_action('eshopbox_email_before_order_table', $order, false); ?>

<h2 style="font-family: Arial; color:#000000; font-size: 14px; font-weight: normal;"><?php echo __( 'Order:', 'eshopbox' ) . ' ' . $order->get_order_number(); ?></h2>

<table cellspacing="0" cellpadding="6" style="width: 100%; border-collapse:collapse;  border: 1px solid #eee; font-size:12px;">
	<thead>
		<tr>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Product', 'eshopbox' ); ?></th>
<!--			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php //_e( 'Color', 'eshopbox' ); ?></th>-->
			<!--<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php // _e( 'Size', 'eshopbox' ); ?></th>-->
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Quantity', 'eshopbox' ); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Price', 'eshopbox' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php echo $order->email_order_items_table( $order->is_download_permitted(), true, ($order->status=='processing') ? true : false ); ?>
	</tbody>
	<tfoot>
		<?php
			if ( $totals = $order->get_order_item_totals() ) {
				$i = 0;
				foreach ( $totals as $total ) {
					$i++;
					?><tr>
						<th scope="row" colspan="2" style="text-align:left; <?php if ( $i == 1 ) echo 'border-top-width:0px;'; ?>"><?php echo $total['label']; ?></th>
						<td colspan="1" style="text-align:right; <?php if ( $i == 1 ) echo 'border-top-width:0px;'; ?>"><?php echo $total['value']; ?></td>
					</tr><?php
				}
			}
		?>
	</tfoot>
</table>

<?php do_action('eshopbox_email_after_order_table', $order, false); ?>

<?php do_action( 'eshopbox_email_order_meta', $order, false ); ?>

<h2 style="font-family: Arial; color:#000000; font-size: 14px; font-weight: normal;"><?php _e( 'Customer details', 'eshopbox' ); ?></h2>

<?php if ($order->billing_email) : ?>
	<p><strong><?php _e( 'Email:', 'eshopbox' ); ?></strong> <?php echo $order->billing_email; ?></p>
<?php endif; ?>
<?php if ($order->billing_phone) : ?>
	<p><strong><?php _e( 'Tel:', 'eshopbox' ); ?></strong> <?php echo $order->billing_phone; ?></p>
<?php endif; ?>

<?php eshopbox_get_template('emails/email-addresses.php', array( 'order' => $order )); ?>

<p><?php _e( "If you need any assistance or have any questions, feel free to contact us by using the web form at ".$website."/contact-us or call us at ".$support_phone.".", 'eshopbox' ); ?></p>
<?php do_action('eshopbox_email_footer'); ?>