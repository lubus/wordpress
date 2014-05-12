<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/eshopbox/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	EshopBox/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php
	/**
	 * eshopbox_before_single_product hook
	 *
	 * @hooked eshopbox_show_messages - 10
	 */
	 do_action( 'eshopbox_before_single_product' );
?>

<div itemscope itemtype="http://schema.org/Product" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
		/**
		 * eshopbox_show_product_images hook
		 *
		 * @hooked eshopbox_show_product_sale_flash - 10
		 * @hooked eshopbox_show_product_images - 20
		 */
		do_action( 'eshopbox_before_single_product_summary' );
	?>

	<div class="summary entry-summary">

		<?php
			/**
			 * eshopbox_single_product_summary hook
			 *
			 * @hooked eshopbox_template_single_title - 5
			 * @hooked eshopbox_template_single_price - 10
			 * @hooked eshopbox_template_single_excerpt - 20
			 * @hooked eshopbox_template_single_add_to_cart - 30
			 * @hooked eshopbox_template_single_meta - 40
			 * @hooked eshopbox_template_single_sharing - 50
			 */
			do_action( 'eshopbox_single_product_summary' );
		?>

	</div><!-- .summary -->

	<?php
		/**
		 * eshopbox_after_single_product_summary hook
		 *
		 * @hooked eshopbox_output_product_data_tabs - 10
		 * @hooked eshopbox_output_related_products - 20
		 */
		do_action( 'eshopbox_after_single_product_summary' );
	?>

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'eshopbox_after_single_product' ); ?>