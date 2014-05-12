<?php
/**
 * Abondoned order email template
 *
 * @author 		Eshopbox
 * @package 	 Eshop_email_guru/Templates/Emails
 * @version     1.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php eshopbox_get_template('emails/email-header.php', $email_heading );?>

<p><?php
$customer_name=$order->billing_first_name.' '.$order->billing_last_name;
$site_title=get_option( 'site_title', '' );
$site_url=get_bloginfo('siteurl');
$site_url=explode('http://',$site_url);
if($site_url[1] != '')
    $site_url=$site_url[1];
_e( 'Hi ', 'eshopbox' );echo $customer_name;?></p>
<p><?php _e( "Thanks for your recent visit to $site_url. We noticed that the transaction for order ", 'eshopbox' ); echo $order->get_order_number(); _e( " is pending.", 'eshopbox' ); ?></p>
<p><?php _e( "We would request you to complete your payment to get it in process.", 'eshopbox' ); ?></p>
<p><?php _e( "If you need help completing your payment, please do not hesitate to give us a call on '.$support_phone.' or email us at '.$support_email.'. We're here to help you find the most stylish & comfortable shoes to suit your lifestyle.", 'eshopbox' ); ?></p>
<p><?php _e( "Also, If you are facing any problem with online payment, you can make use of our Cash-on-Delivery option, where you can pay for your order in cash once it has been delivered to you (selected PIN codes only).", 'eshopbox' ); ?></p>
<?php do_action('eshopbox_email_before_order_table', $order, false); ?>
<h2 style="font-family: Arial; color:#000000; font-size: 14px; font-weight: normal;"><?php echo __( 'Order:', 'eshopbox' ) . ' ' . $order->get_order_number(); ?></h2>
<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
	<thead>
		<tr>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Product', 'eshopbox' ); ?></th>
<!--			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php //_e( 'Color', 'eshopbox' ); ?></th>-->
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
 <p><?php _e( "Happy shopping with $site_title Online!  Enjoy the most appealing and responsive online shopping experience.", 'eshopbox' ); ?></p>
<p><?php _e( "For any query/request, please contact us at '.$support_email.' or call us at our customer service helpline '.$support_phone.'.
", 'eshopbox' ); ?></p>
<!--email footer start-->
<?php eshopbox_get_template('emails/email-footer.php');?>
<!--email footer end-->