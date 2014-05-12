<?php
/**
 * Single Product Image
 *
 * @author 		WooThemes
 * @package 	EshopBox/Templates
 * @version     2.0.14
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $eshopbox, $product;

?>
	<?php do_action( 'eshopbox_product_thumbnails' ); ?>
<div class="images">
<div id="mainimage">
	<?php
		if ( has_post_thumbnail() ) {

			//$image_title 		= esc_attr( get_the_title( get_post_thumbnail_id() ) );
			$image_link  		= wp_get_attachment_url( get_post_thumbnail_id() );
			$image_link      = wp_get_attachment_image_src( get_post_thumbnail_id(), apply_filters( 'single_product_large_thumbnail_size','large' ) );
			$image       		= get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
				'title' => $image_title
				) );
			$attachment_count   = count( $product->get_gallery_attachment_ids() );

			if ( $attachment_count > 0 ) {
				$gallery = '[product-gallery]';
			} else {
				$gallery = '';
			}

			echo apply_filters( 'eshopbox_single_product_image_html', sprintf( '<div id="zoomWindow" style="position: absolute; left:400px; z-index:1; top:0px;"></div> <a href="%s" itemprop="image" class="eshopbox-main-image zoom MagicZoomPlus"  title="%s"  rel="prettyPhoto   expand-align: image; zoom-fade: true; zoom-fade-in-speed: 1000; zoom-fade-out-speed: 500; disable-expand: true; click-to-activate: true; zoom-height:588; zoom-width:470; zoom-position: #zoomWindow;  smoothing-speed: 20'  . $gallery . '">%s</a>', $image_link[0], $image_title, $image ), $post->ID );

		} else {

			echo apply_filters( 'eshopbox_single_product_image_html', sprintf( '<img src="%s" alt="Placeholder" />', eshopbox_placeholder_img_src() ), $post->ID );

		}
	?>
</div>


</div>
