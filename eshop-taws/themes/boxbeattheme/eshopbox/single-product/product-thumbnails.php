<?php
/**
 * Single Product Thumbnails
 *
 * @author 		WooThemes
 * @package 	EshopBox/Templates
 * @version     2.0.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product, $eshopbox;

$attachment_ids = $product->get_gallery_attachment_ids();

if ( $attachment_ids ) {
	?>
	<div class="thumbnails"><?php

		$loop = 0;
		$columns = apply_filters( 'eshopbox_product_thumbnails_columns', 3 );

		foreach ( $attachment_ids as $attachment_id ) {

			$classes = array( 'zoom' );

			if ( $loop == 0 || $loop % $columns == 0 )
				$classes[] = 'first';

			if ( ( $loop + 1 ) % $columns == 0 )
				$classes[] = 'last';

			//$image_link = wp_get_attachment_url( $attachment_id );
			$image_link      = wp_get_attachment_image_src( $attachment_id, apply_filters( 'single_product_large_thumbnail_size','large' ) );
                        if($_SERVER['REMOTE_ADDR'] == '203.92.41.2'){
                           // echo "<pre>jivi";print_r($image_link);echo "</pre>";
                        }

			if ( ! $image_link )
				continue;

			$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) );
			$image_class = esc_attr( implode( ' ', $classes ) );
			//$image_title = esc_attr( get_the_title( $attachment_id ) );
			$image_title = $post->post_title;
			$image_src_medium      = wp_get_attachment_image_src( $attachment_id, apply_filters( 'single_product_small_thumbnail_size','shop_single' ) );

			echo apply_filters( 'eshopbox_single_product_image_thumbnail_html', sprintf( '<a href="%s" class="%s" title="%s"  rel="'.$image_src_medium[0].'">%s</a>', $image_link[0], $image_class, $image_title, $image ), $attachment_id, $post->ID, $image_class );

			$loop++;
		}

	?></div>
	<?php
}
