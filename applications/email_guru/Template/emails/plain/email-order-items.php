<?php
/**
 * Email Order Items (plain)
 *
 * @author 		Eshopbox
 * @package 	Eshop_email_guru/Templates/Emails
 * 
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $eshopbox;

foreach ( $items as $item ) :

	// Get/prep product data
	$_product 	= $order->get_product_from_item( $item );
	$item_meta 	= new WC_Order_Item_Meta( $item['item_meta'] );

	// Title, sku, qty, price
	echo apply_filters( 'eshopbox_order_product_title', $item['name'], $_product );
	echo $show_sku && $_product->get_sku() ? ' (#' . $_product->get_sku() . ')' : '';

	// Variation
	echo $item_meta->meta ? "\n" . nl2br( $item_meta->display( true, true ) ) : '';

	// Quantity
	echo "\n" . sprintf( __( 'Quantity: %s', 'eshopbox' ), $item['qty'] );

	// Cost
	echo "\n" . sprintf( __( 'Cost: %s', 'eshopbox' ), $order->get_formatted_line_subtotal( $item ) );

	// Download URLs
	if ( $show_download_links && $_product->exists() && $_product->is_downloadable() )
		echo "\n" . implode( ', ', $order->get_downloadable_file_urls( $item['product_id'], $item['variation_id'], $item ) );

	// Note
	if ( $show_purchase_note && $purchase_note = get_post_meta( $_product->id, '_purchase_note', true ) )
		echo "\n" . nl2br( $purchase_note );

	echo "\n\n";

endforeach;