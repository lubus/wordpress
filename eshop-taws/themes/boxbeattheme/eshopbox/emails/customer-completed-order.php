<?php
/**
 * Customer completed order email
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php do_action('eshopbox_email_header', $email_heading); 


?>
<p><?php 
$customer_name=$order->billing_first_name;
$site_url=get_bloginfo('siteurl');
$website=home_url();
$support_email = get_option( 'support_email', '' );
$support_phone = get_option( 'support_phone', '' );
$orderno=$order->get_order_number();
$trackingprovider=$order->order_custom_fields['_tracking_provider'][0];
if(empty($trackingprovider)){
    $trackingprovider=  strtoupper($order->order_custom_fields['_custom_tracking_provider'][0]);
}
$tracking_no=$order->order_custom_fields['_tracking_number'][0];
if(!empty($order->order_custom_fields['_date_shipped'][0])){
$shipped_date=date('D,d-m-Y',$order->order_custom_fields['_date_shipped'][0]); 
}
//  echo '<pre>';print_r($order);
_e( 'Hi ', 'eshopbox' );echo $customer_name;?></p>
<p><?php printf( __( "Your order ".$orderno." placed on %s has been completed. Your order was shipped on ".$shipped_date." via ".$trackingprovider." with tracking number ".$tracking_no.". Our shipping provider has been confirmed for the delivery.", 'eshopbox' ), get_option( 'blogname' ) ); ?></p>
<p><?php _e("Get in touch with us if you do not have received your item yet or received a defective item. We will be happy to help you.", 'eshopbox')?></p>

<p><?php echo _e("Your order details are given below for your reference:",'eshopbox'); //do_action('eshopbox_email_before_order_table', $order, false); ?></p>

<h2><?php 

echo __( 'Order:', 'eshopbox' ) . ' ' . $order->get_order_number(); ?></h2>

<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
	<thead>
		<tr>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Product', 'eshopbox' ); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Quantity', 'eshopbox' ); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Price', 'eshopbox' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php echo $order->email_order_items_table( true, false, true ); ?>
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
        
        <p><?php _e( "If you need any assistance or have any questions, feel free to contact us by using the web form at ".$website."/contact-us/ or call us at ".$support_phone.".", 'eshopbox' ); ?></p>
     
        <p><?php _e("We hope you enjoyed shopping at ".$website,'eshopbox')?></p>    
<?php do_action('eshopbox_email_footer'); ?>