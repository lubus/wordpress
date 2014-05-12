<?php

/**
 * Order shipment email
 *
 * @author 		Eshopbox
 * @package 	Eshop_email_guru/Templates/Emails
 * 
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$support_email = get_option( 'support_email', '' );
$support_phone = get_option( 'support_phone', '' );
$website=get_bloginfo('siteurl');
$site_title = get_option( 'site_title', '' );
$website=home_url();
$myorder=$website.'/track-an-order/';
$customer_name=$order->billing_first_name.' '.$order->billing_last_name;
$tracking_provider=$_SESSION['tracking_provider'];
$tracking_number=$_SESSION['tracking_number'];
$store_name=get_bloginfo('name');
?>
<?php eshopbox_get_template('emails/email-header.php',$email_heading);?>
<p><?php
_e( 'Hi ', 'eshopbox' );echo $customer_name;?></p>
<p><?php _e( "
We would like to inform you that your shipment from $store_name is out for delivery. Since you have opted to pay with cash on delivery. ", 'eshopbox' ); ?></p>
<p><?php _e( "Shipment Details :", 'eshopbox' ); ?></p>


<?php do_action('eshopbox_email_before_order_table', $order, false); ?>
<h2 style="font-family: Arial; color:#000000; font-size: 14px; font-weight: normal;"><?php echo __( 'Order:', 'eshopbox' ) . ' ' . $order->get_order_number(); ?></h2>
<p><?php _e( "Shipment ID :", 'eshopbox' );echo $tracking_number; ?></p>
<p><?php _e( "Courier Service Provider :", 'eshopbox' );echo $tracking_provider; ?></p>
<p><?php _e( "You can track your shipment any time by moving to : ", 'eshopbox' ); ?><a href='<?php echo $myorder;?>'>my order</a></p>
<p><?php _e( "Items in Shipment", 'eshopbox' );?></p>



<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
	<thead>
		<tr>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Product', 'eshopbox' ); ?></th>
<!--			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php// _e( 'Color', 'eshopbox' ); ?></th>-->
<!--			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php //_e( 'Size', 'eshopbox' ); ?></th>-->
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
						<th scope="row" colspan="2" style="text-align:left; border: 1px solid #eee; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['label']; ?></th>
						<td colspan="1" style="text-align:right; border: 1px solid #eee; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['value']; ?></td>
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
<p><?php _e( "For any query/request, please contact us at $support_email or call us at our customer service helpline $support_phone.
", 'eshopbox' ); ?></p>

<?php eshopbox_get_template('emails/email-footer.php');?>
