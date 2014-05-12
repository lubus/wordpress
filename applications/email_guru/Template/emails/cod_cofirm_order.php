<?php
/**
 * Customer  order confirmation email template in cod
 *
 * @author 		Eshopbox
 * @package 	 Eshop_email_guru/Templates/Emails
 * 
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>


<?php eshopbox_get_template('emails/email-header.php', $email_heading );?>

<p style="font-weight:bold;"><?php
$orderdetail=$order-> order_custom_fields;
$bilphnno=$orderdetail[_billing_phone];
$bilphnno= $bilphnno[0];
$bilphnno="+91".$bilphnno;
$customer_name=$order->billing_first_name;
$website=home_url();
$site_url=get_bloginfo('siteurl');
$site_url=explode('http://',$site_url);
if($site_url[1] != '')
    $site_url=$site_url[1];
$myorder=$website.'/track-an-order/';
_e( 'Hi ', 'eshopbox' );echo $customer_name;?></p>
<p>
<p><?php _e('Thank You for shopping at '.$site_url, 'eshopbox' ); ?></p>
<p><?php _e('Your order will be shipped from our warehouse soon. Once your order has been shipped, we will send you an email with the expected delivery time.', 'eshopbox' ); ?></p>

<?php do_action('eshopbox_email_before_order_table', $order, false); ?>

<h2 style="font-family: Arial; color:#000000; font-size: 14px; font-weight: normal;"><?php echo __( 'Order:', 'eshopbox' ) . ' ' . $order->get_order_number(); ?></h2>

<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
	<thead>
		<tr>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Product', 'eshopbox' ); ?></th>
<!--			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php // _e( 'Color', 'eshopbox' ); ?></th>-->
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
<p><?php _e( "You can ", 'eshopbox' ); ?><a href='<?php echo $myorder;?>'>track</a><?php _e( " or manage your order any time.", 'eshopbox' ); ?></p>

<p><?php _e( "For any query/request, please contact us at $support_email or call us at our customer service helpline $support_phone.", 'eshopbox' ); ?></p>
<?php eshopbox_get_template('emails/email-footer.php');?>

