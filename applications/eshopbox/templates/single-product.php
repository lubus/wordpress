<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/eshopbox/single-product.php
 *
 * @author 		WooThemes
 * @package 	EshopBox/Templates
 * @version     1.6.4
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
		do_action('eshopbox_before_main_content');
	?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php eshopbox_get_template_part( 'content', 'single-product' ); ?>

		<?php endwhile; // end of the loop. ?>

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