<?php
/**
 * EshopBox Template Functions
 *
 * Functions used in the template files to output content - in most cases hooked in via the template actions. All functions are pluggable.
 *
 * @author 		WooThemes
 * @category 	Core
 * @package 	EshopBox/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/** Template pages ********************************************************/

if ( ! function_exists( 'eshopbox_content' ) ) {

	/**
	 * Output EshopBox content.
	 *
	 * This function is only used in the optional 'eshopbox.php' template
	 * which people can add to their themes to add basic eshopbox support
	 * without hooks or modifying core templates.
	 *
	 * @access public
	 * @return void
	 */
	function eshopbox_content() {

		if ( is_singular( 'product' ) ) {

			while ( have_posts() ) : the_post();

				eshopbox_get_template_part( 'content', 'single-product' );

			endwhile;

		} else { ?>

			<?php if ( apply_filters( 'eshopbox_show_page_title', true ) ) : ?>

				<h1 class="page-title"><?php eshopbox_page_title(); ?></h1>

			<?php endif; ?>

			<?php do_action( 'eshopbox_archive_description' ); ?>

			<?php if ( have_posts() ) : ?>

				<?php do_action('eshopbox_before_shop_loop'); ?>

				<?php eshopbox_product_loop_start(); ?>

					<?php eshopbox_product_subcategories(); ?>

					<?php while ( have_posts() ) : the_post(); ?>

						<?php eshopbox_get_template_part( 'content', 'product' ); ?>

					<?php endwhile; // end of the loop. ?>

				<?php eshopbox_product_loop_end(); ?>

				<?php do_action('eshopbox_after_shop_loop'); ?>

			<?php elseif ( ! eshopbox_product_subcategories( array( 'before' => eshopbox_product_loop_start( false ), 'after' => eshopbox_product_loop_end( false ) ) ) ) : ?>

				<?php eshopbox_get_template( 'loop/no-products-found.php' ); ?>

			<?php endif;

		}
	}
}

if ( ! function_exists( 'eshopbox_single_product_content' ) ) {

	/**
	 * eshopbox_single_product_content function.
	 *
	 * @access public
	 * @return void
	 * @deprecated 1.6
	 */
	function eshopbox_single_product_content() {
		_deprecated_function( __FUNCTION__, '1.6' );
		eshopbox_content();
	}
}
if ( ! function_exists( 'eshopbox_archive_product_content' ) ) {

	/**
	 * eshopbox_archive_product_content function.
	 *
	 * @access public
	 * @return void
	 * @deprecated 1.6
	 */
	function eshopbox_archive_product_content() {
		_deprecated_function( __FUNCTION__, '1.6' );
		eshopbox_content();
	}
}
if ( ! function_exists( 'eshopbox_product_taxonomy_content' ) ) {

	/**
	 * eshopbox_product_taxonomy_content function.
	 *
	 * @access public
	 * @return void
	 * @deprecated 1.6
	 */
	function eshopbox_product_taxonomy_content() {
		_deprecated_function( __FUNCTION__, '1.6' );
		eshopbox_content();
	}
}

/** Global ****************************************************************/

if ( ! function_exists( 'eshopbox_output_content_wrapper' ) ) {

	/**
	 * Output the start of the page wrapper.
	 *
	 * @access public
	 * @return void
	 */
	function eshopbox_output_content_wrapper() {
		eshopbox_get_template( 'shop/wrapper-start.php' );
	}
}
if ( ! function_exists( 'eshopbox_output_content_wrapper_end' ) ) {

	/**
	 * Output the end of the page wrapper.
	 *
	 * @access public
	 * @return void
	 */
	function eshopbox_output_content_wrapper_end() {
		eshopbox_get_template( 'shop/wrapper-end.php' );
	}
}

if ( ! function_exists( 'eshopbox_show_messages' ) ) {

	/**
	 * Show messages on the frontend.
	 *
	 * @access public
	 * @return void
	 */
	function eshopbox_show_messages() {
		global $eshopbox;

		if ( $eshopbox->error_count() > 0  )
			eshopbox_get_template( 'shop/errors.php', array(
					'errors' => $eshopbox->get_errors()
				) );


		if ( $eshopbox->message_count() > 0  )
			eshopbox_get_template( 'shop/messages.php', array(
					'messages' => $eshopbox->get_messages()
				) );

		$eshopbox->clear_messages();
	}
}

if ( ! function_exists( 'eshopbox_get_sidebar' ) ) {

	/**
	 * Get the shop sidebar template.
	 *
	 * @access public
	 * @return void
	 */
	function eshopbox_get_sidebar() {
		eshopbox_get_template( 'shop/sidebar.php' );
	}
}

if ( ! function_exists( 'eshopbox_demo_store' ) ) {

	/**
	 * Adds a demo store banner to the site if enabled
	 *
	 * @access public
	 * @return void
	 */
	function eshopbox_demo_store() {
		if ( get_option( 'eshopbox_demo_store' ) == 'no' )
			return;

		$notice = get_option( 'eshopbox_demo_store_notice' );
		if ( empty( $notice ) )
			$notice = __( 'This is a demo store for testing purposes &mdash; no orders shall be fulfilled.', 'eshopbox' );

		echo apply_filters( 'eshopbox_demo_store', '<p class="demo_store">' . $notice . '</p>'  );
	}
}

/** Loop ******************************************************************/

if ( ! function_exists( 'eshopbox_page_title' ) ) {

	/**
	 * eshopbox_page_title function.
	 *
	 * @access public
	 * @return void
	 */
	function eshopbox_page_title() {

		if ( is_search() ) {
			$page_title = sprintf( __( 'Search Results: &ldquo;%s&rdquo;', 'eshopbox' ), get_search_query() );

			if ( get_query_var( 'paged' ) )
				$page_title .= sprintf( __( '&nbsp;&ndash; Page %s', 'eshopbox' ), get_query_var( 'paged' ) );

		} elseif ( is_tax() ) {

			$page_title = single_term_title( "", false );

		} else {

			$shop_page_id = eshopbox_get_page_id( 'shop' );
			$page_title   = get_the_title( $shop_page_id );

		}

	    echo apply_filters( 'eshopbox_page_title', $page_title );
	}
}

if ( ! function_exists( 'eshopbox_product_loop_start' ) ) {

	/**
	 * Output the start of a product loop. By default this is a UL
	 *
	 * @access public
	 * @return void
	 */
	function eshopbox_product_loop_start( $echo = true ) {
		ob_start();
		eshopbox_get_template( 'loop/loop-start.php' );
		if ( $echo )
			echo ob_get_clean();
		else
			return ob_get_clean();
	}
}
if ( ! function_exists( 'eshopbox_product_loop_end' ) ) {

	/**
	 * Output the end of a product loop. By default this is a UL
	 *
	 * @access public
	 * @return void
	 */
	function eshopbox_product_loop_end( $echo = true ) {
		ob_start();

		eshopbox_get_template( 'loop/loop-end.php' );

		if ( $echo )
			echo ob_get_clean();
		else
			return ob_get_clean();
	}
}
if ( ! function_exists( 'eshopbox_taxonomy_archive_description' ) ) {

	/**
	 * Show an archive description on taxonomy archives
	 *
	 * @access public
	 * @subpackage	Archives
	 * @return void
	 */
	function eshopbox_taxonomy_archive_description() {
		if ( is_tax( array( 'product_cat', 'product_tag' ) ) && get_query_var( 'paged' ) == 0 ) {
			$description = apply_filters( 'the_content', term_description() );
			if ( $description ) {
				echo '<div class="term-description">' . $description . '</div>';
			}
		}
	}
}
if ( ! function_exists( 'eshopbox_product_archive_description' ) ) {

	/**
	 * Show a shop page description on product archives
	 *
	 * @access public
	 * @subpackage	Archives
	 * @return void
	 */
	function eshopbox_product_archive_description() {
		if ( is_post_type_archive( 'product' ) && get_query_var( 'paged' ) == 0 ) {
			$shop_page   = get_post( eshopbox_get_page_id( 'shop' ) );
			$description = apply_filters( 'the_content', $shop_page->post_content );
			if ( $description ) {
				echo '<div class="page-description">' . $description . '</div>';
			}
		}
	}
}

if ( ! function_exists( 'eshopbox_template_loop_add_to_cart' ) ) {

	/**
	 * Get the add to cart template for the loop.
	 *
	 * @access public
	 * @subpackage	Loop
	 * @return void
	 */
	function eshopbox_template_loop_add_to_cart() {
		eshopbox_get_template( 'loop/add-to-cart.php' );
	}
}
if ( ! function_exists( 'eshopbox_template_loop_product_thumbnail' ) ) {

	/**
	 * Get the product thumbnail for the loop.
	 *
	 * @access public
	 * @subpackage	Loop
	 * @return void
	 */
	function eshopbox_template_loop_product_thumbnail() {
		echo eshopbox_get_product_thumbnail();
	}
}
if ( ! function_exists( 'eshopbox_template_loop_price' ) ) {

	/**
	 * Get the product price for the loop.
	 *
	 * @access public
	 * @subpackage	Loop
	 * @return void
	 */
	function eshopbox_template_loop_price() {
		eshopbox_get_template( 'loop/price.php' );
	}
}
if ( ! function_exists( 'eshopbox_template_loop_rating' ) ) {

	/**
	 * Display the average rating in the loop
	 *
	 * @access public
	 * @subpackage	Loop
	 * @return void
	 */
	function eshopbox_template_loop_rating() {
		eshopbox_get_template( 'loop/rating.php' );
	}
}
if ( ! function_exists( 'eshopbox_show_product_loop_sale_flash' ) ) {

	/**
	 * Get the sale flash for the loop.
	 *
	 * @access public
	 * @subpackage	Loop
	 * @return void
	 */
	function eshopbox_show_product_loop_sale_flash() {
		eshopbox_get_template( 'loop/sale-flash.php' );
	}
}
if ( ! function_exists( 'eshopbox_reset_loop' ) ) {

	/**
	 * Reset the loop's index and columns when we're done outputting a product loop.
	 *
	 * @access public
	 * @subpackage	Loop
	 * @return void
	 */
	function eshopbox_reset_loop() {
		global $eshopbox_loop;
		// Reset loop/columns globals when starting a new loop
		$eshopbox_loop['loop'] = $eshopbox_loop['column'] = '';
	}
}

add_filter( 'loop_end', 'eshopbox_reset_loop' );


if ( ! function_exists( 'eshopbox_get_product_thumbnail' ) ) {

	/**
	 * Get the product thumbnail, or the placeholder if not set.
	 *
	 * @access public
	 * @subpackage	Loop
	 * @param string $size (default: 'shop_catalog')
	 * @param int $placeholder_width (default: 0)
	 * @param int $placeholder_height (default: 0)
	 * @return string
	 */
	function eshopbox_get_product_thumbnail( $size = 'shop_catalog', $placeholder_width = 0, $placeholder_height = 0  ) {
		global $post;

		if ( has_post_thumbnail() )
			return get_the_post_thumbnail( $post->ID, $size );
		elseif ( eshopbox_placeholder_img_src() )
			return eshopbox_placeholder_img( $size );
	}
}

if ( ! function_exists( 'eshopbox_result_count' ) ) {

	/**
	 * Output the result count text (Showing x - x of x results).
	 *
	 * @access public
	 * @subpackage	Loop
	 * @return void
	 */
	function eshopbox_result_count() {
		eshopbox_get_template( 'loop/result-count.php' );
	}
}

if ( ! function_exists( 'eshopbox_catalog_ordering' ) ) {

	/**
	 * Output the product sorting options.
	 *
	 * @access public
	 * @subpackage	Loop
	 * @return void
	 */
	function eshopbox_catalog_ordering() {
		global $eshopbox;

		$orderby = isset( $_GET['orderby'] ) ? eshopbox_clean( $_GET['orderby'] ) : apply_filters( 'eshopbox_default_catalog_orderby', get_option( 'eshopbox_default_catalog_orderby' ) );

		eshopbox_get_template( 'loop/orderby.php', array( 'orderby' => $orderby ) );
	}
}

if ( ! function_exists( 'eshopbox_pagination' ) ) {

	/**
	 * Output the pagination.
	 *
	 * @access public
	 * @subpackage	Loop
	 * @return void
	 */
	function eshopbox_pagination() {
		eshopbox_get_template( 'loop/pagination.php' );
	}
}

/** Single Product ********************************************************/

if ( ! function_exists( 'eshopbox_show_product_images' ) ) {

	/**
	 * Output the product image before the single product summary.
	 *
	 * @access public
	 * @subpackage	Product
	 * @return void
	 */
	function eshopbox_show_product_images() {
		eshopbox_get_template( 'single-product/product-image.php' );
	}
}
if ( ! function_exists( 'eshopbox_show_product_thumbnails' ) ) {

	/**
	 * Output the product thumbnails.
	 *
	 * @access public
	 * @subpackage	Product
	 * @return void
	 */
	function eshopbox_show_product_thumbnails() {
		eshopbox_get_template( 'single-product/product-thumbnails.php' );
	}
}
if ( ! function_exists( 'eshopbox_output_product_data_tabs' ) ) {

	/**
	 * Output the product tabs.
	 *
	 * @access public
	 * @subpackage	Product/Tabs
	 * @return void
	 */
	function eshopbox_output_product_data_tabs() {
		eshopbox_get_template( 'single-product/tabs/tabs.php' );
	}
}
if ( ! function_exists( 'eshopbox_template_single_title' ) ) {

	/**
	 * Output the product title.
	 *
	 * @access public
	 * @subpackage	Product
	 * @return void
	 */
	function eshopbox_template_single_title() {
		eshopbox_get_template( 'single-product/title.php' );
	}
}
if ( ! function_exists( 'eshopbox_template_single_price' ) ) {

	/**
	 * Output the product price.
	 *
	 * @access public
	 * @subpackage	Product
	 * @return void
	 */
	function eshopbox_template_single_price() {
		eshopbox_get_template( 'single-product/price.php' );
	}
}
if ( ! function_exists( 'eshopbox_template_single_excerpt' ) ) {

	/**
	 * Output the product short description (excerpt).
	 *
	 * @access public
	 * @subpackage	Product
	 * @return void
	 */
	function eshopbox_template_single_excerpt() {
		eshopbox_get_template( 'single-product/short-description.php' );
	}
}
if ( ! function_exists( 'eshopbox_template_single_meta' ) ) {

	/**
	 * Output the product meta.
	 *
	 * @access public
	 * @subpackage	Product
	 * @return void
	 */
	function eshopbox_template_single_meta() {
		eshopbox_get_template( 'single-product/meta.php' );
	}
}
if ( ! function_exists( 'eshopbox_template_single_sharing' ) ) {

	/**
	 * Output the product sharing.
	 *
	 * @access public
	 * @subpackage	Product
	 * @return void
	 */
	function eshopbox_template_single_sharing() {
		eshopbox_get_template( 'single-product/share.php' );
	}
}
if ( ! function_exists( 'eshopbox_show_product_sale_flash' ) ) {

	/**
	 * Output the product sale flash.
	 *
	 * @access public
	 * @subpackage	Product
	 * @return void
	 */
	function eshopbox_show_product_sale_flash() {
		eshopbox_get_template( 'single-product/sale-flash.php' );
	}
}

if ( ! function_exists( 'eshopbox_template_single_add_to_cart' ) ) {

	/**
	 * Trigger the single product add to cart action.
	 *
	 * @access public
	 * @subpackage	Product
	 * @return void
	 */
	function eshopbox_template_single_add_to_cart() {
		global $product;
		do_action( 'eshopbox_' . $product->product_type . '_add_to_cart'  );
	}
}
if ( ! function_exists( 'eshopbox_simple_add_to_cart' ) ) {

	/**
	 * Output the simple product add to cart area.
	 *
	 * @access public
	 * @subpackage	Product
	 * @return void
	 */
	function eshopbox_simple_add_to_cart() {
		eshopbox_get_template( 'single-product/add-to-cart/simple.php' );
	}
}
if ( ! function_exists( 'eshopbox_grouped_add_to_cart' ) ) {

	/**
	 * Output the grouped product add to cart area.
	 *
	 * @access public
	 * @subpackage	Product
	 * @return void
	 */
	function eshopbox_grouped_add_to_cart() {
		eshopbox_get_template( 'single-product/add-to-cart/grouped.php' );
	}
}
if ( ! function_exists( 'eshopbox_variable_add_to_cart' ) ) {

	/**
	 * Output the variable product add to cart area.
	 *
	 * @access public
	 * @subpackage	Product
	 * @return void
	 */
	function eshopbox_variable_add_to_cart() {
		global $product;

		// Enqueue variation scripts
		wp_enqueue_script( 'wc-add-to-cart-variation' );

		// Load the template
		eshopbox_get_template( 'single-product/add-to-cart/variable.php', array(
				'available_variations'  => $product->get_available_variations(),
				'attributes'   			=> $product->get_variation_attributes(),
				'selected_attributes' 	=> $product->get_variation_default_attributes()
			) );
	}
}
if ( ! function_exists( 'eshopbox_external_add_to_cart' ) ) {

	/**
	 * Output the external product add to cart area.
	 *
	 * @access public
	 * @subpackage	Product
	 * @return void
	 */
	function eshopbox_external_add_to_cart() {
		global $product;

		if ( ! $product->get_product_url() )
			return;

		eshopbox_get_template( 'single-product/add-to-cart/external.php', array(
				'product_url' => $product->get_product_url(),
				'button_text' => $product->get_button_text()
			) );
	}
}

if ( ! function_exists( 'eshopbox_quantity_input' ) ) {

	/**
	 * Output the quantity input for add to cart forms.
	 *
	 * @access public
	 * @subpackage	Product
	 * @return void
	 */
	function eshopbox_quantity_input( $args = array() ) {
		global $product;

		$defaults = array(
			'input_name'  	=> 'quantity',
			'input_value'  	=> '1',
			'max_value'  	=> apply_filters( 'eshopbox_quantity_input_max', '', $product ),
			'min_value'  	=> apply_filters( 'eshopbox_quantity_input_min', '', $product ),
			'step' 			=> apply_filters( 'eshopbox_quantity_input_step', '1', $product )
		);

		$args = apply_filters( 'eshopbox_quantity_input_args', wp_parse_args( $args, $defaults ), $product );

		eshopbox_get_template( 'single-product/add-to-cart/quantity.php', $args );
	}
}

if ( ! function_exists( 'eshopbox_product_description_tab' ) ) {

	/**
	 * Output the description tab content.
	 *
	 * @access public
	 * @subpackage	Product/Tabs
	 * @return void
	 */
	function eshopbox_product_description_tab() {
		eshopbox_get_template( 'single-product/tabs/description.php' );
	}
}
if ( ! function_exists( 'eshopbox_product_additional_information_tab' ) ) {

	/**
	 * Output the attributes tab content.
	 *
	 * @access public
	 * @subpackage	Product/Tabs
	 * @return void
	 */
	function eshopbox_product_additional_information_tab() {
		eshopbox_get_template( 'single-product/tabs/additional-information.php' );
	}
}
if ( ! function_exists( 'eshopbox_product_reviews_tab' ) ) {

	/**
	 * Output the reviews tab content.
	 *
	 * @access public
	 * @subpackage	Product/Tabs
	 * @return void
	 */
	function eshopbox_product_reviews_tab() {
		eshopbox_get_template( 'single-product/tabs/reviews.php' );
	}
}

if ( ! function_exists( 'eshopbox_default_product_tabs' ) ) {

	/**
	 * Add default product tabs to product pages.
	 *
	 * @access public
	 * @param mixed $tabs
	 * @return void
	 */
	function eshopbox_default_product_tabs( $tabs = array() ) {
		global $product, $post;

		// Description tab - shows product content
		if ( $post->post_content )
			$tabs['description'] = array(
				'title'    => __( 'Description', 'eshopbox' ),
				'priority' => 10,
				'callback' => 'eshopbox_product_description_tab'
			);

		// Additional information tab - shows attributes
		if ( $product->has_attributes() || ( get_option( 'eshopbox_enable_dimension_product_attributes' ) == 'yes' && ( $product->has_dimensions() || $product->has_weight() ) ) )
			$tabs['additional_information'] = array(
				'title'    => __( 'Additional Information', 'eshopbox' ),
				'priority' => 20,
				'callback' => 'eshopbox_product_additional_information_tab'
			);

		// Reviews tab - shows comments
		if ( comments_open() )
			$tabs['reviews'] = array(
				'title'    => sprintf( __( 'Reviews (%d)', 'eshopbox' ), get_comments_number( $post->ID ) ),
				'priority' => 30,
				'callback' => 'comments_template'
			);

		return $tabs;
	}
}

if ( ! function_exists( 'eshopbox_sort_product_tabs' ) ) {

	/**
	 * Sort tabs by priority
	 *
	 * @access public
	 * @return void
	 */
	function eshopbox_sort_product_tabs( $tabs = array() ) {

		// Re-order tabs by priority
		if ( ! function_exists( '_sort_priority_callback' ) ) {
			function _sort_priority_callback( $a, $b ) {
				if ( $a['priority'] == $b['priority'] )
			        return 0;
			    return ( $a['priority'] < $b['priority'] ) ? -1 : 1;
			}
		}

		uasort( $tabs, '_sort_priority_callback' );

		return $tabs;
	}
}

if ( ! function_exists( 'eshopbox_comments' ) ) {

	/**
	 * Output the Review comments template.
	 *
	 * @access public
	 * @subpackage	Product
	 * @return void
	 */
	function eshopbox_comments( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		eshopbox_get_template( 'single-product/review.php' );
	}
}

if ( ! function_exists( 'eshopbox_output_related_products' ) ) {

	/**
	 * Output the related products.
	 *
	 * @access public
	 * @subpackage	Product
	 * @return void
	 */
	function eshopbox_output_related_products() {
		eshopbox_related_products( 2, 2 );
	}
}

if ( ! function_exists( 'eshopbox_related_products' ) ) {

	/**
	 * Output the related products.
	 *
	 * @access public
	 * @param int $posts_per_page (default: 2)
	 * @param int $columns (default: 2)
	 * @param string $orderby (default: 'rand')
	 * @return void
	 */
	function eshopbox_related_products( $posts_per_page = 2, $columns = 2, $orderby = 'rand'  ) {
		eshopbox_get_template( 'single-product/related.php', array(
				'posts_per_page'  => $posts_per_page,
				'orderby'    => $orderby,
				'columns'    => $columns
			) );
	}
}

if ( ! function_exists( 'eshopbox_upsell_display' ) ) {

	/**
	 * Output product up sells.
	 *
	 * @access public
	 * @param int $posts_per_page (default: -1)
	 * @param int $columns (default: 2)
	 * @param string $orderby (default: 'rand')
	 * @return void
	 */
	function eshopbox_upsell_display( $posts_per_page = '-1', $columns = 2, $orderby = 'rand' ) {
		eshopbox_get_template( 'single-product/up-sells.php', array(
				'posts_per_page'  => $posts_per_page,
				'orderby'    => $orderby,
				'columns'    => $columns
			) );
	}
}

/** Cart ******************************************************************/

if ( ! function_exists( 'eshopbox_shipping_calculator' ) ) {

	/**
	 * Output the cart shipping calculator.
	 *
	 * @access public
	 * @subpackage	Cart
	 * @return void
	 */
	function eshopbox_shipping_calculator() {
		eshopbox_get_template( 'cart/shipping-calculator.php' );
	}
}

if ( ! function_exists( 'eshopbox_cart_totals' ) ) {

	/**
	 * Output the cart totals.
	 *
	 * @access public
	 * @subpackage	Cart
	 * @return void
	 */
	function eshopbox_cart_totals() {
		eshopbox_get_template( 'cart/totals.php' );
	}
}

if ( ! function_exists( 'eshopbox_cross_sell_display' ) ) {

	/**
	 * Output the cart cross-sells.
	 *
	 * @access public
	 * @subpackage	Cart
	 * @return void
	 */
	function eshopbox_cross_sell_display() {
		eshopbox_get_template( 'cart/cross-sells.php' );
	}
}

/** Mini-Cart *************************************************************/

if ( ! function_exists( 'eshopbox_mini_cart' ) ) {

	/**
	 * Output the Mini-cart - used by cart widget
	 *
	 * @access public
	 * @return void
	 */
	function eshopbox_mini_cart( $args = array() ) {

		$defaults = array(
			'list_class' => ''
		);

		$args = wp_parse_args( $args, $defaults );

		eshopbox_get_template( 'cart/mini-cart.php', $args );
	}
}

/** Login *****************************************************************/

if ( ! function_exists( 'eshopbox_login_form' ) ) {

	/**
	 * Output the EshopBox Login Form
	 *
	 * @access public
	 * @subpackage	Forms
	 * @return void
	 */
	function eshopbox_login_form( $args = array() ) {

		$defaults = array(
			'message'  => '',
			'redirect' => '',
			'hidden'   => false
		);

		$args = wp_parse_args( $args, $defaults  );

		eshopbox_get_template( 'shop/form-login.php', $args );
	}
}

if ( ! function_exists( 'eshopbox_checkout_login_form' ) ) {

	/**
	 * Output the EshopBox Checkout Login Form
	 *
	 * @access public
	 * @subpackage	Checkout
	 * @return void
	 */
	function eshopbox_checkout_login_form() {
		global $eshopbox;

		eshopbox_get_template( 'checkout/form-login.php', array( 'checkout' => $eshopbox->checkout() ) );
	}
}

if ( ! function_exists( 'eshopbox_breadcrumb' ) ) {

	/**
	 * Output the EshopBox Breadcrumb
	 *
	 * @access public
	 * @return void
	 */
	function eshopbox_breadcrumb( $args = array() ) {

		$defaults = apply_filters( 'eshopbox_breadcrumb_defaults', array(
			'delimiter'   => ' &#47; ',
			'wrap_before' => '<nav class="eshopbox-breadcrumb" itemprop="breadcrumb">',
			'wrap_after'  => '</nav>',
			'before'      => '',
			'after'       => '',
			'home'        => _x( 'Home', 'breadcrumb', 'eshopbox' ),
		) );

		$args = wp_parse_args( $args, $defaults );

		eshopbox_get_template( 'shop/breadcrumb.php', $args );
	}
}

if ( ! function_exists( 'eshopbox_order_review' ) ) {

	/**
	 * Output the Order review table for the checkout.
	 *
	 * @access public
	 * @subpackage	Checkout
	 * @return void
	 */
	function eshopbox_order_review() {
		global $eshopbox;

		eshopbox_get_template( 'checkout/review-order.php', array( 'checkout' => $eshopbox->checkout() ) );
	}
}

if ( ! function_exists( 'eshopbox_checkout_coupon_form' ) ) {

	/**
	 * Output the Coupon form for the checkout.
	 *
	 * @access public
	 * @subpackage	Checkout
	 * @return void
	 */
	function eshopbox_checkout_coupon_form() {
		global $eshopbox;

		eshopbox_get_template( 'checkout/form-coupon.php', array( 'checkout' => $eshopbox->checkout() ) );
	}
}

if ( ! function_exists( 'eshopbox_products_will_display' ) ) {

	/**
	 * Check if we will be showing products or not (and not subcats only)
	 *
	 * @access public
	 * @subpackage	Loop
	 * @return void
	 */
	function eshopbox_products_will_display() {
		global $eshopbox, $wpdb;

		if ( ! is_product_category() && ! is_product_tag() && ! is_shop() && ! is_product_taxonomy() )
			return false;

		if ( is_search() || is_filtered() || is_paged() )
			return true;

		if ( is_shop() && get_option( 'eshopbox_shop_page_display' ) != 'subcategories' )
			return true;

		$term = get_queried_object();

		if ( is_product_category() ) {
			switch ( get_eshopbox_term_meta( $term->term_id, 'display_type', true ) ) {
				case 'products' :
				case 'both' :
					return true;
				break;
				case '' :
					if ( get_option( 'eshopbox_category_archive_display' ) != 'subcategories' )
						return true;
				break;
			}
		}

		$parent_id 		= empty( $term->term_id ) ? 0 : $term->term_id;
		$has_children 	= $wpdb->get_col( $wpdb->prepare( "SELECT term_id FROM {$wpdb->term_taxonomy} WHERE parent = %d", $parent_id ) );

		if ( $has_children ) {
			// Check terms have products inside
			$children = array();
			foreach ( $has_children as $term ) {
				$children = array_merge( $children, get_term_children( $term, 'product_cat' ) );
				$children[] = $term;
			}
			$objects = get_objects_in_term( $children, 'product_cat' );

			if ( sizeof( $objects ) > 0 ) {
				return false;
			} else {
				return true;
			}
		} else {
			return true;
		}
	}
}

if ( ! function_exists( 'eshopbox_product_subcategories' ) ) {

	/**
	 * Display product sub categories as thumbnails.
	 *
	 * @access public
	 * @subpackage	Loop
	 * @return void
	 */
	function eshopbox_product_subcategories( $args = array() ) {
		global $eshopbox, $wp_query;

		$defaults = array(
			'before'  => '',
			'after'  => '',
			'force_display' => false
		);

		$args = wp_parse_args( $args, $defaults );

		extract( $args );

		// Main query only
		if ( ! is_main_query() && ! $force_display ) return;

		// Don't show when filtering, searching or when on page > 1 and ensure we're on a product archive
		if ( is_search() || is_filtered() || is_paged() || ( ! is_product_category() && ! is_shop() ) ) return;

		// Check categories are enabled
		if ( is_shop() && get_option( 'eshopbox_shop_page_display' ) == '' ) return;

		// Find the category + category parent, if applicable
		$term 			= get_queried_object();
		$parent_id 		= empty( $term->term_id ) ? 0 : $term->term_id;

		if ( is_product_category() ) {
			$display_type = get_eshopbox_term_meta( $term->term_id, 'display_type', true );

			switch ( $display_type ) {
				case 'products' :
					return;
				break;
				case '' :
					if ( get_option( 'eshopbox_category_archive_display' ) == '' )
						return;
				break;
			}
		}

		// NOTE: using child_of instead of parent - this is not ideal but due to a WP bug ( http://core.trac.boxbeat.org/ticket/15626 ) pad_counts won't work
		$args = array(
			'child_of'		=> $parent_id,
			'menu_order'	=> 'ASC',
			'hide_empty'	=> 1,
			'hierarchical'	=> 1,
			'taxonomy'		=> 'product_cat',
			'pad_counts'	=> 1
		);
		$product_categories = get_categories( apply_filters( 'eshopbox_product_subcategories_args', $args ) );

		$product_category_found = false;

		if ( $product_categories ) {

			foreach ( $product_categories as $category ) {

				if ( $category->parent != $parent_id )
					continue;

				if ( ! $product_category_found ) {
					// We found a category
					$product_category_found = true;
					echo $before;
				}

				eshopbox_get_template( 'content-product_cat.php', array(
					'category' => $category
				) );

			}

		}

		// If we are hiding products disable the loop and pagination
		if ( $product_category_found ) {
			if ( is_product_category() ) {
				$display_type = get_eshopbox_term_meta( $term->term_id, 'display_type', true );

				switch ( $display_type ) {
					case 'subcategories' :
						$wp_query->post_count = 0;
						$wp_query->max_num_pages = 0;
					break;
					case '' :
						if ( get_option( 'eshopbox_category_archive_display' ) == 'subcategories' ) {
							$wp_query->post_count = 0;
							$wp_query->max_num_pages = 0;
						}
					break;
				}
			}
			if ( is_shop() && get_option( 'eshopbox_shop_page_display' ) == 'subcategories' ) {
				$wp_query->post_count = 0;
				$wp_query->max_num_pages = 0;
			}

			echo $after;
			return true;
		}

	}
}

if ( ! function_exists( 'eshopbox_subcategory_thumbnail' ) ) {

	/**
	 * Show subcategory thumbnails.
	 *
	 * @access public
	 * @param mixed $category
	 * @subpackage	Loop
	 * @return void
	 */
	function eshopbox_subcategory_thumbnail( $category ) {
		global $eshopbox;

		$small_thumbnail_size  	= apply_filters( 'single_product_small_thumbnail_size', 'shop_catalog' );
		$dimensions    			= $eshopbox->get_image_size( $small_thumbnail_size );
		$thumbnail_id  			= get_eshopbox_term_meta( $category->term_id, 'thumbnail_id', true  );

		if ( $thumbnail_id ) {
			$image = wp_get_attachment_image_src( $thumbnail_id, $small_thumbnail_size  );
			$image = $image[0];
		} else {
			$image = eshopbox_placeholder_img_src();
		}

		if ( $image )
			echo '<img src="' . $image . '" alt="' . $category->name . '" width="' . $dimensions['width'] . '" height="' . $dimensions['height'] . '" />';
	}
}

if ( ! function_exists( 'eshopbox_order_details_table' ) ) {

	/**
	 * Displays order details in a table.
	 *
	 * @access public
	 * @param mixed $order_id
	 * @subpackage	Orders
	 * @return void
	 */
	function eshopbox_order_details_table( $order_id  ) {
		if ( ! $order_id ) return;

		eshopbox_get_template( 'order/order-details.php', array(
			'order_id' => $order_id
		) );
	}
}

/** Forms ****************************************************************/

if ( ! function_exists( 'eshopbox_form_field' ) ) {

	/**
	 * Outputs a checkout/address form field.
	 *
	 * @access public
	 * @subpackage	Forms
	 * @param mixed $key
	 * @param mixed $args
	 * @param string $value (default: null)
	 * @return void
	 */
	function eshopbox_form_field( $key, $args, $value = null ) {
		global $eshopbox;

		$defaults = array(
			'type'              => 'text',
			'label'             => '',
			'placeholder'       => '',
			'maxlength'         => false,
			'required'          => false,
			'class'             => array(),
			'label_class'       => array(),
			'return'            => false,
			'options'           => array(),
			'custom_attributes' => array(),
			'validate'          => array(),
			'default'		    => '',
		);

		$args = wp_parse_args( $args, $defaults  );

		if ( ( ! empty( $args['clear'] ) ) ) $after = '<div class="clear"></div>'; else $after = '';

		if ( $args['required'] ) {
			$args['class'][] = 'validate-required';
			$required = ' <abbr class="required" title="' . esc_attr__( 'required', 'eshopbox'  ) . '">*</abbr>';
		} else {
			$required = '';
		}

		$args['maxlength'] = ( $args['maxlength'] ) ? 'maxlength="' . absint( $args['maxlength'] ) . '"' : '';

		if ( is_null( $value ) )
			$value = $args['default'];

		// Custom attribute handling
		$custom_attributes = array();

		if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) )
			foreach ( $args['custom_attributes'] as $attribute => $attribute_value )
				$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';

		if ( ! empty( $args['validate'] ) )
			foreach( $args['validate'] as $validate )
				$args['class'][] = 'validate-' . $validate;

		switch ( $args['type'] ) {
		case "country" :

			if ( sizeof( $eshopbox->countries->get_allowed_countries() ) == 1 ) {

				$field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field">';

				if ( $args['label'] )
					$field .= '<label class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label']  . '</label>';

				$allowed_country_values = array_values( $eshopbox->countries->get_allowed_countries() );
				$field .= '<strong>' . current( $allowed_country_values ) . '</strong>';

				$allowed_country_keys = array_keys( $eshopbox->countries->get_allowed_countries() );
				$field .= '<input type="hidden" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" value="' . current( $allowed_country_keys ) . '" ' . implode( ' ', $custom_attributes ) . ' />';

				$field .= '</p>' . $after;

			} else {

				$field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field">
						<label for="' . esc_attr( $key ) . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label']. $required  . '</label>
						<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" class="country_to_state country_select" ' . implode( ' ', $custom_attributes ) . '>
							<option value="">'.__( 'Select a country&hellip;', 'eshopbox' ) .'</option>';

				foreach ( $eshopbox->countries->get_allowed_countries() as $ckey => $cvalue )
					$field .= '<option value="' . $ckey . '" '.selected( $value, $ckey, false ) .'>'.__( $cvalue, 'eshopbox' ) .'</option>';

				$field .= '</select>';

				$field .= '<noscript><input type="submit" name="eshopbox_checkout_update_totals" value="' . __( 'Update country', 'eshopbox' ) . '" /></noscript>';

				$field .= '</p>' . $after;

			}

			break;
		case "state" :

			/* Get Country */
			$country_key = $key == 'billing_state'? 'billing_country' : 'shipping_country';

			if ( isset( $_POST[ $country_key ] ) ) {
				$current_cc = eshopbox_clean( $_POST[ $country_key ] );
			} elseif ( is_user_logged_in() ) {
				$current_cc = get_user_meta( get_current_user_id() , $country_key, true );
				if ( ! $current_cc) {
					$current_cc = apply_filters('default_checkout_country', ($eshopbox->customer->get_country()) ? $eshopbox->customer->get_country() : $eshopbox->countries->get_base_country());
				}
			} elseif ( $country_key == 'billing_country' ) {
				$current_cc = apply_filters('default_checkout_country', ($eshopbox->customer->get_country()) ? $eshopbox->customer->get_country() : $eshopbox->countries->get_base_country());
			} else {
				$current_cc = apply_filters('default_checkout_country', ($eshopbox->customer->get_shipping_country()) ? $eshopbox->customer->get_shipping_country() : $eshopbox->countries->get_base_country());
			}

			$states = $eshopbox->countries->get_states( $current_cc );

			if ( is_array( $states ) && empty( $states ) ) {

				$field  = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field" style="display: none">';

				if ( $args['label'] )
					$field .= '<label for="' . esc_attr( $key ) . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label'] . $required . '</label>';
				$field .= '<input type="hidden" class="hidden" name="' . esc_attr( $key )  . '" id="' . esc_attr( $key ) . '" value="" ' . implode( ' ', $custom_attributes ) . ' placeholder="' . $args['placeholder'] . '" />';
				$field .= '</p>' . $after;

			} elseif ( is_array( $states ) ) {

				$field  = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field">';

				if ( $args['label'] )
					$field .= '<label for="' . esc_attr( $key ) . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label']. $required . '</label>';
				$field .= '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" class="state_select" ' . implode( ' ', $custom_attributes ) . ' placeholder="' . $args['placeholder'] . '">
					<option value="">'.__( 'Select a state&hellip;', 'eshopbox' ) .'</option>';

				foreach ( $states as $ckey => $cvalue )
					$field .= '<option value="' . $ckey . '" '.selected( $value, $ckey, false ) .'>'.__( $cvalue, 'eshopbox' ) .'</option>';

				$field .= '</select>';
				$field .= '</p>' . $after;

			} else {

				$field  = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field">';

				if ( $args['label'] )
					$field .= '<label for="' . esc_attr( $key ) . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label']. $required . '</label>';
				$field .= '<input type="text" class="input-text" value="' . $value . '"  placeholder="' . $args['placeholder'] . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" ' . implode( ' ', $custom_attributes ) . ' />';
				$field .= '</p>' . $after;

			}

			break;
		case "textarea" :

			$field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field">';

			if ( $args['label'] )
				$field .= '<label for="' . esc_attr( $key ) . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label']. $required  . '</label>';

			$field .= '<textarea name="' . esc_attr( $key ) . '" class="input-text" id="' . esc_attr( $key ) . '" placeholder="' . $args['placeholder'] . '" cols="5" rows="2" ' . implode( ' ', $custom_attributes ) . '>'. esc_textarea( $value  ) .'</textarea>
				</p>' . $after;

			break;
		case "checkbox" :

			$field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field">
					<input type="' . $args['type'] . '" class="input-checkbox" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" value="1" '.checked( $value, 1, false ) .' />
					<label for="' . esc_attr( $key ) . '" class="checkbox ' . implode( ' ', $args['label_class'] ) .'" ' . implode( ' ', $custom_attributes ) . '>' . $args['label'] . $required . '</label>
				</p>' . $after;

			break;
		case "password" :

			$field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field">';

			if ( $args['label'] )
				$field .= '<label for="' . esc_attr( $key ) . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label']. $required . '</label>';

			$field .= '<input type="password" class="input-text" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" placeholder="' . $args['placeholder'] . '" value="' . esc_attr( $value ) . '" ' . implode( ' ', $custom_attributes ) . ' />
				</p>' . $after;

			break;
		case "text" :

			$field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field">';

			if ( $args['label'] )
				$field .= '<label for="' . esc_attr( $key ) . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label'] . $required . '</label>';

			$field .= '<input type="text" class="input-text" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" placeholder="' . $args['placeholder'] . '" '.$args['maxlength'].' value="' . esc_attr( $value ) . '" ' . implode( ' ', $custom_attributes ) . ' />
				</p>' . $after;

			break;
		case "select" :

			$options = '';

			if ( ! empty( $args['options'] ) )
				foreach ( $args['options'] as $option_key => $option_text )
					$options .= '<option value="' . esc_attr( $option_key ) . '" '. selected( $value, $option_key, false ) . '>' . esc_attr( $option_text ) .'</option>';

				$field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field">';

				if ( $args['label'] )
					$field .= '<label for="' . esc_attr( $key ) . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label']. $required . '</label>';

				$field .= '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" class="select" ' . implode( ' ', $custom_attributes ) . '>
						' . $options . '
					</select>
				</p>' . $after;

			break;
		default :

			$field = apply_filters( 'eshopbox_form_field_' . $args['type'], '', $key, $args, $value );

			break;
		}

		if ( $args['return'] ) return $field; else echo $field;
	}
}

if ( ! function_exists( 'get_product_search_form' ) ) {

	/**
	 * Output Product search forms.
	 *
	 * @access public
	 * @subpackage	Forms
	 * @param bool $echo (default: true)
	 * @return void
	 */
	function get_product_search_form( $echo = true  ) {
		do_action( 'get_product_search_form'  );

		$search_form_template = locate_template( 'product-searchform.php' );
		if ( '' != $search_form_template  ) {
			require $search_form_template;
			return;
		}

		$form = '<form role="search" method="get" id="searchform" action="' . esc_url( home_url( '/'  ) ) . '">
			<div>
				<label class="screen-reader-text" for="s">' . __( 'Search for:', 'eshopbox' ) . '</label>
				<input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="' . __( 'Search for products', 'eshopbox' ) . '" />
				<input type="submit" id="searchsubmit" value="'. esc_attr__( 'Search', 'eshopbox' ) .'" />
				<input type="hidden" name="post_type" value="product" />
			</div>
		</form>';

		if ( $echo  )
			echo apply_filters( 'get_product_search_form', $form );
		else
			return apply_filters( 'get_product_search_form', $form );
	}
}
