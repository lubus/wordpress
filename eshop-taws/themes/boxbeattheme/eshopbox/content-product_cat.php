<?php
/**
 * The template for displaying product category thumbnails within loops.
 *
 * Override this template by copying it to yourtheme/eshopbox/content-product_cat.php
 *
 * @author 		WooThemes
 * @package 	EshopBox/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $eshopbox_loop;

// Store loop count we're currently on
if ( empty( $eshopbox_loop['loop'] ) )
	$eshopbox_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $eshopbox_loop['columns'] ) )
	$eshopbox_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Increase loop count
$eshopbox_loop['loop']++;
?>
<li class="product-category product<?php
    if ( ( $eshopbox_loop['loop'] - 1 ) % $eshopbox_loop['columns'] == 0 || $eshopbox_loop['columns'] == 1)
        echo ' first';
	if ( $eshopbox_loop['loop'] % $eshopbox_loop['columns'] == 0 )
		echo ' last';
	?>">

	<?php do_action( 'eshopbox_before_subcategory', $category ); ?>

	<a href="<?php echo get_term_link( $category->slug, 'product_cat' ); ?>">

		<?php
			/**
			 * eshopbox_before_subcategory_title hook
			 *
			 * @hooked eshopbox_subcategory_thumbnail - 10
			 */
			do_action( 'eshopbox_before_subcategory_title', $category );
		?>

		<h3>
			<?php
				echo $category->name;

				if ( $category->count > 0 )
					echo apply_filters( 'eshopbox_subcategory_count_html', ' <mark class="count">(' . $category->count . ')</mark>', $category );
			?>
		</h3>

		<?php
			/**
			 * eshopbox_after_subcategory_title hook
			 */
			do_action( 'eshopbox_after_subcategory_title', $category );
		?>

	</a>

	<?php do_action( 'eshopbox_after_subcategory', $category ); ?>

</li>