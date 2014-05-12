<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/eshopbox/archive-product.php
 *
 * @author 		WooThemes
 * @package 	EshopBox/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header('shop'); ?>

	<?php
		/**
		 * eshopbox_before_main_content hook
		 *
		 * @hooked eshopbox_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked eshopbox_breadcrumb - 20
		 */
		do_action('eshopbox_before_main_content'); ?>
		<?php if ( apply_filters( 'eshopbox_show_page_title', true ) ) : ?>
        <div class="cover_title">
		<div class="left_1">	<h1><?php eshopbox_page_title(); ?> </h1></div>
         <div class="right_1">   <?php
				/**
				 * eshopbox_before_shop_loop hook
				 *
				 * @hooked eshopbox_result_count - 20
				 * @hooked eshopbox_catalog_ordering - 30
				 */
				do_action( 'eshopbox_before_shop_loop' );
			?></div>
</div>
		<?php endif; ?>
		<?php do_action( 'eshopbox_archive_description' ); ?>

		<?php if ( have_posts() ) : ?>

			

			<?php eshopbox_product_loop_start(); ?>

				<?php eshopbox_product_subcategories(); ?>

				<?php while ( have_posts() ) : the_post(); ?>


					<?php 
						
						eshopbox_get_template_part( 'content', 'product' ); ?>

				<?php endwhile; // end of the loop. ?>

			<?php eshopbox_product_loop_end(); ?>

			<?php
				/**
				 * eshopbox_after_shop_loop hook
				 *
				 * @hooked eshopbox_pagination - 10
				 */
				do_action( 'eshopbox_after_shop_loop' );
			?>

		<?php elseif ( ! eshopbox_product_subcategories( array( 'before' => eshopbox_product_loop_start( false ), 'after' => eshopbox_product_loop_end( false ) ) ) ) : ?>

			<?php eshopbox_get_template( 'loop/no-products-found.php' ); ?>

		<?php endif; ?>

	<?php
		/**
		 * eshopbox_after_main_content hook
		 *
		 * @hooked eshopbox_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action('eshopbox_after_main_content');
	?>

	<?php
		/**
		 * eshopbox_sidebar hook
		 *
		 * @hooked eshopbox_get_sidebar - 10
		 */
		do_action('eshopbox_sidebar');
	?>

<?php get_footer('shop'); ?>
