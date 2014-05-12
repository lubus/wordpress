<?php
/**
 * order  refund email
 *
 * @author 		Eshopbox
 * @package 	 Eshop_email_guru/Templates/Emails
 * @version     2.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

echo $email_heading . "\n\n";

echo __( "Your order has been received and is now being processed. Your order details are shown below for your reference:", 'eshopbox' ) . "\n\n";

echo "****************************************************\n\n";

do_action( 'eshopbox_email_before_order_table', $order, false );

echo sprintf( __( 'Order number: %s', 'eshopbox'), $order->get_order_number() ) . "\n";
echo sprintf( __( 'Order date: %s', 'eshopbox'), date_i18n( eshopbox_date_format(), strtotime( $order->order_date ) ) ) . "\n";

do_action( 'eshopbox_email_order_meta', $order, false, true );

echo "\n" . $order->email_order_items_table( $order->is_download_permitted(), true, ($order->status=='processing') ? true : false, '', '', true );

echo "----------\n\n";

if ( $totals = $order->get_order_item_totals() ) {
	foreach ( $totals as $total ) {
		echo $total['label'] . "\t " . $total['value'] . "\n";
	}
}

echo "\n****************************************************\n\n";

do_action( 'eshopbox_email_after_order_table', $order, false, true );

echo __( 'Your details', 'eshopbox' ) . "\n\n";

if ( $order->billing_email )
	echo __( 'Email:', 'eshopbox' ); echo $order->billing_email. "\n";

if ( $order->billing_phone )
	echo __( 'Tel:', 'eshopbox' ); ?> <?php echo $order->billing_phone. "\n";

eshopbox_get_template( 'emails/plain/email-addresses.php', array( 'order' => $order ) );

echo "\n****************************************************\n\n";

echo apply_filters( 'eshopbox_email_footer_text', get_option( 'eshopbox_email_footer_text' ) );