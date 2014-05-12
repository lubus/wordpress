<?php
/**
 * Cross-sells
 *
 * @author 		WooThemes
 * @package 	eshopbox/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $eshopbox_loop, $eshopbox, $product;

$crosssells = $eshopbox->cart->get_cross_sells();

if ( sizeof( $crosssells ) == 0 ) return;

$meta_query = $eshopbox->query->get_meta_query();

$args = array(
	'post_type'           => 'product',
	'ignore_sticky_posts' => 1,
	'posts_per_page'      => apply_filters( 'eshopbox_cross_sells_total', 2 ),
	'no_found_rows'       => 1,
	'orderby'             => 'rand',
	'post__in'            => $crosssells,
	'meta_query'          => $meta_query
);

$products = new WP_Query( $args );

$eshopbox_loop['columns'] 	= apply_filters( 'eshopbox_cross_sells_columns', 2 );

if ( $products->have_posts() ) : ?>

	<div class="cross-sells">

		<h2><?php _e( 'You may be interested in&hellip;', 'eshopbox' ) ?></h2>

		<?php eshopbox_product_loop_start(); ?>

			<?php while ( $products->have_posts() ) : $products->the_post(); ?>

				<?php eshopbox_get_template_part( 'content', 'product' ); ?>

			<?php endwhile; // end of the loop. ?>

		<?php eshopbox_product_loop_end(); ?>

	</div>

<?php endif;

wp_reset_query();