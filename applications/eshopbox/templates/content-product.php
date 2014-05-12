<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/eshopbox/content-product.php
 *
 * @author 		WooThemes
 * @package 	EshopBox/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $eshopbox_loop;

// Store loop count we're currently on
if ( empty( $eshopbox_loop['loop'] ) )
	$eshopbox_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $eshopbox_loop['columns'] ) )
	$eshopbox_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;

// Increase loop count
$eshopbox_loop['loop']++;

// Extra post classes
$classes = array();
if ( 0 == ( $eshopbox_loop['loop'] - 1 ) % $eshopbox_loop['columns'] || 1 == $eshopbox_loop['columns'] )
	$classes[] = 'first';
if ( 0 == $eshopbox_loop['loop'] % $eshopbox_loop['columns'] )
	$classes[] = 'last';
?>
<li <?php post_class( $classes ); ?>>

	<?php do_action( 'eshopbox_before_shop_loop_item' ); ?>

	<a href="<?php the_permalink(); ?>">

		<?php
			/**
			 * eshopbox_before_shop_loop_item_title hook
			 *
			 * @hooked eshopbox_show_product_loop_sale_flash - 10
			 * @hooked eshopbox_template_loop_product_thumbnail - 10
			 */
			do_action( 'eshopbox_before_shop_loop_item_title' );
		?>

		<h3><?php the_title(); ?></h3>

		<?php
			/**
			 * eshopbox_after_shop_loop_item_title hook
			 *
			 * @hooked eshopbox_template_loop_price - 10
			 */
			do_action( 'eshopbox_after_shop_loop_item_title' );
		?>

	</a>

	<?php do_action( 'eshopbox_after_shop_loop_item' ); ?>

</li>