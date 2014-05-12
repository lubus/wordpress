<?php
$support_email = get_option( 'support_email', '' );
$support_phone = get_option( 'support_phone', '' );
$website=get_bloginfo('siteurl');
$site_title = get_option( 'site_title', '' );
?>
<!--header content starts-->
<?php eshopbox_get_template('emails/email-header.php');?>
<!--header content ends-->
<p style="font-weight:bold;"><?php

 $customer_name=$order->billing_first_name;
$site_url=get_bloginfo('siteurl');
$site_url=explode('http://',$site_url);
if($site_url[1] != '')
    $site_url=$site_url[1];
 $store_name=get_bloginfo('name');
 $orderno=$order->get_order_number();

_e( 'Hi ', 'eshopbox' );echo $customer_name;?></p>
<p><?php _e( "Your order ".$orderno." has been successfully cancelled and will not be dispatched by ".$store_name.".", 'eshopbox' ); ?></p>

<p><?php _e( "Your order details are given below for your reference.", 'eshopbox' ); ?></p>
<h2 style="font-family: Arial; color:#000000; font-size: 14px; font-weight: bold;"><?php echo __( 'Order:', 'eshopbox' ) . ' ' . $order->get_order_number(); ?></h2>

<table cellspacing="0" cellpadding="6" style="width: 100%; border-collapse:collapse;  border: 1px solid #eee; font-size:12px;">
	<thead>
		<tr>
			<th scope="col" style="text-align:left; border: 1px solid #eee;font-weight:normal;"><?php _e( 'Product', 'eshopbox' ); ?></th>
<!--			<th scope="col" style="text-align:left; border: 1px solid #eee;font-weight:normal;"><?php// _e( 'Color', 'eshopbox' ); ?></th>-->
			<!--<th scope="col" style="text-align:left; border: 1px solid #eee;font-weight:normal;"><?php //_e( 'Size', 'eshopbox' ); ?></th>-->
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
						<th scope="row" colspan="2" style="text-align:left; <?php if ( $i == 1 ) echo 'border-top-width: 0px;'; ?>"><?php echo $total['label']; ?></th>
						<td colspan="1" style="text-align:right; <?php if ( $i == 1 ) echo 'border-top-width: 0px;'; ?>"><?php echo $total['value']; ?></td>
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
<p><?php _e( "If you need any assistance or have any questions, feel free to contact us by using the web form at ".$website."/contact-us or call us at ".$support_phone.".", 'eshopbox' ); ?></p>
<p><?php _e("We hope you enjoyed shopping at ".$site_url.".",'eshopbox')?>
<!--email footer start-->
<?php eshopbox_get_template('emails/email-footer.php');?>
<!--email footer end-->