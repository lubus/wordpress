<?php
/**
 * Customer processing order email
 *
 * @author 		WooThemes
 * @package 	EshopBox/Templates/Emails
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php do_action('eshopbox_email_header', $email_heading); ?>

<p><?php _e( "Your order has been received and is now being processed. Your order details are shown below for your reference:", 'eshopbox' ); ?></p>

<?php do_action('eshopbox_email_before_order_table', $order, false); ?>

<h2><?php echo __( 'Order:', 'eshopbox' ) . ' ' . $order->get_order_number(); ?></h2>

<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
	<thead>
		<tr>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Product', 'eshopbox' ); ?></th>
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
						<td style="text-align:left; border: 1px solid #eee; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['value']; ?></td>
					</tr><?php
				}
			}
		?>
	</tfoot>
</table>

<?php do_action('eshopbox_email_after_order_table', $order, false); ?>

<?php do_action( 'eshopbox_email_order_meta', $order, false ); ?>

<h2><?php _e( 'Customer details', 'eshopbox' ); ?></h2>

<?php if ($order->billing_email) : ?>
	<p><strong><?php _e( 'Email:', 'eshopbox' ); ?></strong> <?php echo $order->billing_email; ?></p>
<?php endif; ?>
<?php if ($order->billing_phone) : ?>
	<p><strong><?php _e( 'Tel:', 'eshopbox' ); ?></strong> <?php echo $order->billing_phone; ?></p>
<?php endif; ?>

<?php eshopbox_get_template('emails/email-addresses.php', array( 'order' => $order )); ?>

<?php do_action('eshopbox_email_footer'); ?>