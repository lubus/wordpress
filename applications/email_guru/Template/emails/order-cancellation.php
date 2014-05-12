<?php
$support_email = get_option( 'support_email', '' );
$support_phone = get_option( 'support_phone', '' );
$website=get_bloginfo('siteurl');
$site_title = get_option( 'site_title', '' );
$customer_name=$order->billing_first_name.' '.$order->billing_last_name;
$site_url=get_bloginfo('siteurl');
$site_url=explode('http://',$site_url);
if($site_url[1] != '')
$site_url=$site_url[1];
$store_name=get_bloginfo('name');
?>

<?php eshopbox_get_template('emails/email-header.php');?>

<p style="font-weight:bold;">
<?php _e( 'Hi ', 'eshopbox' );echo $customer_name;?></p>
<p>
<?php _e( "We are sorry but after several attempts, we were unable to deliver your $store_name shipment to your delivery address. Therefore we have cancelled the order. Our shipping provider also tried calling you several times at your phone number but was unable to reach you. We apologize for the inconvenience this may have caused you.", 'eshopbox' ); ?></p>
<p><?php _e( "If you like, you can place a new order on our website.", 'eshopbox' ); ?></p>
<?php do_action('eshopbox_email_before_order_table', $order, false); ?>

<h2 style="font-family: Arial; color:#000000; font-size: 14px; font-weight: bold;"><?php echo __( 'Order:', 'eshopbox' ) . ' ' . $order->get_order_number(); ?></h2>

<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
	<thead>
		<tr>
			<th scope="col" style="text-align:left; border: 1px solid #eee;font-weight:normal;"><?php _e( 'Product', 'eshopbox' ); ?></th>
<!--			<th scope="col" style="text-align:left; border: 1px solid #eee;font-weight:normal;"><?php // _e( 'Color', 'eshopbox' ); ?></th>-->
<!--			<th scope="col" style="text-align:left; border: 1px solid #eee;font-weight:normal;"><?php //_e( 'Size', 'eshopbox' ); ?></th>-->
			<th scope="col" style="text-align:left; border: 1px solid #eee;font-weight:normal;"><?php _e( 'Quantity', 'eshopbox' ); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;font-weight:normal;"><?php _e( 'Price', 'eshopbox' ); ?></th>
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

<h2 style="font-family: Arial; color:#000000; font-size: 14px; font-weight: bold;"><?php _e( 'Customer details', 'eshopbox' ); ?></h2>

<?php if ($order->billing_email) : ?>
	<p><strong><?php _e( 'Email:', 'eshopbox' ); ?></strong> <?php echo $order->billing_email; ?></p>
<?php endif; ?>
<?php if ($order->billing_phone) : ?>
	<p><strong><?php _e( 'Mobile No:', 'eshopbox' ); ?></strong> <?php echo $order->billing_phone; ?></p>
<?php endif; ?>

<?php eshopbox_get_template('emails/email-addresses.php', array( 'order' => $order )); ?>
<p><?php _e( "For any query/request, please contact us at $support_email or call us at our customer service helpline $support_phone.
", 'eshopbox' ); ?></p>

<?php eshopbox_get_template('emails/email-footer.php');?>
