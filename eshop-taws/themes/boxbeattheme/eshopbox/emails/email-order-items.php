<?php
/**
 * Email Order Items
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.0.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $eshopbox;

foreach ($items as $item) :

	// Get/prep product data
	$_product = $order->get_product_from_item( $item );
	$item_meta = new WC_Order_Item_Meta( $item['item_meta'] );
       // echo '<pre>';print_R($_product->variation_data['attribute_pa_size']);echo '</pre>';
       $object_color 	= get_the_terms( $_product->id, 'pa_color');
       if(is_array($object_color)==1)
                $color = array_shift(eshopbox_get_product_terms($_product->id, 'pa_color', 'names'));
        //echo '<pre>';echo $color;
        $size=$_product->variation_data['attribute_pa_size'];
	$image = ($show_image) ? '<img src="'. current(wp_get_attachment_image_src( get_post_thumbnail_id( $_product->id ), 'thumbnail')) .'" alt="Product Image" height="'.$image_size[1].'" width="'.$image_size[0].'" style="vertical-align:middle; margin-right: 10px;" />' : '';

	?>
	<tr>
		<td style="text-align:left; vertical-align:middle; border: 1px solid #eee;"><?php

			// Show title/image etc
			echo 	apply_filters( 'eshopbox_order_product_image', $image, $_product, $show_image);

			// Product name
			echo 	apply_filters( 'eshopbox_order_product_title', $item['name'], $_product );

			// SKU
			echo 	($show_sku && $_product->get_sku()) ? ' (#' . $_product->get_sku() . ')' : '';

			// File URLs
			if ( $show_download_links && $_product->exists() && $_product->is_downloadable() ) {

				$download_file_urls = $order->get_downloadable_file_urls( $item['product_id'], $item['variation_id'], $item );

				$i = 0;

				foreach ( $download_file_urls as $file_url => $download_file_url ) {
					echo '<br/><small>';

					if ( count( $download_file_urls ) > 1 ) {
						echo sprintf( __('Download %d:', 'eshopbox' ), $i + 1 );
					} elseif ( $i == 0 )
						echo __( 'Download:', 'eshopbox' );

					echo ' <a href="' . $download_file_url . '" target="_blank">' . basename( $file_url ) . '</a></small>';

					$i++;
				}
			}
                        if($color)
				echo "<br>Color : ".$color;
			if($size)
				echo "<br>Size: ".$size;
			// Variation
			echo 	($item_meta->meta) ? '<br/><small>' . nl2br( $item_meta->display( true, true ) ) . '</small>' : '';

		?></td>
		<td style="text-align:left; vertical-align:middle; border: 1px solid #eee;"><?php echo $item['qty'] ;?></td>
		<td style="text-align:left; vertical-align:middle; border: 1px solid #eee;"><?php echo $order->get_formatted_line_subtotal( $item ); ?></td>
	</tr>

	<?php if ($show_purchase_note && $purchase_note = get_post_meta( $_product->id, '_purchase_note', true)) : ?>
		<tr>
			<td colspan="3" style="text-align:left; vertical-align:middle; border: 1px solid #eee;"><?php echo apply_filters('the_content', $purchase_note); ?></td>
		</tr>
	<?php endif; ?>

<?php endforeach; ?>