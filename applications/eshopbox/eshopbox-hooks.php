<?php
/**
 * EshopBox Hooks
 *
 * Action/filter hooks used for EshopBox functions/templates
 *
 * @author 		WooThemes
 * @category 	Core
 * @package 	EshopBox/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/** Template Hooks ********************************************************/

if ( ! is_admin() || defined('DOING_AJAX') ) {

	/**
	 * Content Wrappers
	 *
	 * @see eshopbox_output_content_wrapper()
	 * @see eshopbox_output_content_wrapper_end()
	 */
	add_action( 'eshopbox_before_main_content', 'eshopbox_output_content_wrapper', 10 );
	add_action( 'eshopbox_after_main_content', '
		_end', 10 );

	/**
	 * Sale flashes
	 *
	 * @see eshopbox_show_product_loop_sale_flash()
	 * @see eshopbox_show_product_sale_flash()
	 */
	add_action( 'eshopbox_before_shop_loop_item_title', 'eshopbox_show_product_loop_sale_flash', 10 );
	add_action( 'eshopbox_before_single_product_summary', 'eshopbox_show_product_sale_flash', 10 );

	/**
	 * Breadcrumbs
	 *
	 * @see eshopbox_breadcrumb()
	 */
	add_action( 'eshopbox_before_main_content', 'eshopbox_breadcrumb', 20, 0 );

	/**
	 * Sidebar
	 *
	 * @see eshopbox_get_sidebar()
	 */
	add_action( 'eshopbox_sidebar', 'eshopbox_get_sidebar', 10 );

	/**
	 * Archive descriptions
	 *
	 * @see eshopbox_taxonomy_archive_description()
	 * @see eshopbox_product_archive_description()
	 */
	add_action( 'eshopbox_archive_description', 'eshopbox_taxonomy_archive_description', 10 );
	add_action( 'eshopbox_archive_description', 'eshopbox_product_archive_description', 10 );

	/**
	 * Products Loop
	 *
	 * @see eshopbox_show_messages()
	 * @see eshopbox_result_count()
	 * @see eshopbox_catalog_ordering()
	 */
	add_action( 'eshopbox_before_shop_loop', 'eshopbox_show_messages', 10 );
	add_action( 'eshopbox_before_shop_loop', 'eshopbox_result_count', 20 );
	add_action( 'eshopbox_before_shop_loop', 'eshopbox_catalog_ordering', 30 );

	/**
	 * Product Loop Items
	 *
	 * @see eshopbox_show_messages()
	 * @see eshopbox_template_loop_add_to_cart()
	 * @see eshopbox_template_loop_product_thumbnail()
	 * @see eshopbox_template_loop_price()
	 * @see eshopbox_template_loop_rating()
	 */
	add_action( 'eshopbox_after_shop_loop_item', 'eshopbox_template_loop_add_to_cart', 10 );
	add_action( 'eshopbox_before_shop_loop_item_title', 'eshopbox_template_loop_product_thumbnail', 10 );
	add_action( 'eshopbox_after_shop_loop_item_title', 'eshopbox_template_loop_price', 10 );
	add_action( 'eshopbox_after_shop_loop_item_title', 'eshopbox_template_loop_rating', 5 );

	/**
	 * Subcategories
	 *
	 * @see eshopbox_subcategory_thumbnail()
	 */
	add_action( 'eshopbox_before_subcategory_title', 'eshopbox_subcategory_thumbnail', 10 );

	/**
	 * Before Single Products
	 *
	 * @see eshopbox_show_messages()
	 */
	add_action( 'eshopbox_before_single_product', 'eshopbox_show_messages', 10 );

	/**
	 * Before Single Products Summary Div
	 *
	 * @see eshopbox_show_product_images()
	 * @see eshopbox_show_product_thumbnails()
	 */
	add_action( 'eshopbox_before_single_product_summary', 'eshopbox_show_product_images', 20 );
	add_action( 'eshopbox_product_thumbnails', 'eshopbox_show_product_thumbnails', 20 );

	/**
	 * After Single Products Summary Div
	 *
	 * @see eshopbox_output_product_data_tabs()
	 * @see eshopbox_upsell_display()
	 * @see eshopbox_output_related_products()
	 */
	add_action( 'eshopbox_after_single_product_summary', 'eshopbox_output_product_data_tabs', 10 );
	add_action( 'eshopbox_after_single_product_summary', 'eshopbox_upsell_display', 15 );
	add_action( 'eshopbox_after_single_product_summary', 'eshopbox_output_related_products', 20 );

	/**
	 * Product Summary Box
	 *
	 * @see eshopbox_template_single_title()
	 * @see eshopbox_template_single_price()
	 * @see eshopbox_template_single_excerpt()
	 * @see eshopbox_template_single_meta()
	 * @see eshopbox_template_single_sharing()
	 */
	add_action( 'eshopbox_single_product_summary', 'eshopbox_template_single_title', 5 );
	add_action( 'eshopbox_single_product_summary', 'eshopbox_template_single_price', 10 );
	add_action( 'eshopbox_single_product_summary', 'eshopbox_template_single_excerpt', 20 );
	add_action( 'eshopbox_single_product_summary', 'eshopbox_template_single_meta', 40 );
	add_action( 'eshopbox_single_product_summary', 'eshopbox_template_single_sharing', 50 );


	/**
	 * Product Add to cart
	 *
	 * @see eshopbox_template_single_add_to_cart()
	 * @see eshopbox_simple_add_to_cart()
	 * @see eshopbox_grouped_add_to_cart()
	 * @see eshopbox_variable_add_to_cart()
	 * @see eshopbox_external_add_to_cart()
	 */
	add_action( 'eshopbox_single_product_summary', 'eshopbox_template_single_add_to_cart', 30 );
	add_action( 'eshopbox_simple_add_to_cart', 'eshopbox_simple_add_to_cart', 30 );
	add_action( 'eshopbox_grouped_add_to_cart', 'eshopbox_grouped_add_to_cart', 30 );
	add_action( 'eshopbox_variable_add_to_cart', 'eshopbox_variable_add_to_cart', 30 );
	add_action( 'eshopbox_external_add_to_cart', 'eshopbox_external_add_to_cart', 30 );

	/**
	 * Pagination after shop loops
	 *
	 * @see eshopbox_pagination()
	 */
	add_action( 'eshopbox_after_shop_loop', 'eshopbox_pagination', 10 );

	/**
	 * Product page tabs
	 */
	add_filter( 'eshopbox_product_tabs', 'eshopbox_default_product_tabs' );
	add_filter( 'eshopbox_product_tabs', 'eshopbox_sort_product_tabs', 99 );

	/**
	 * Checkout
	 *
	 * @see eshopbox_checkout_login_form()
	 * @see eshopbox_checkout_coupon_form()
	 * @see eshopbox_order_review()
	 */
	add_action( 'eshopbox_before_checkout_form', 'eshopbox_checkout_login_form', 10 );
	add_action( 'eshopbox_before_checkout_form', 'eshopbox_checkout_coupon_form', 10 );
	add_action( 'eshopbox_checkout_order_review', 'eshopbox_order_review', 10 );

	/**
	 * Cart
	 *
	 * @see eshopbox_cross_sell_display()
	 */
	add_action( 'eshopbox_cart_collaterals', 'eshopbox_cross_sell_display' );

	/**
	 * Footer
	 *
	 * @see eshopbox_demo_store()
	 */
	add_action( 'wp_footer', 'eshopbox_demo_store' );

	/**
	 * Order details
	 *
	 * @see eshopbox_order_details_table()
	 * @see eshopbox_order_details_table()
	 */
	add_action( 'eshopbox_view_order', 'eshopbox_order_details_table', 10 );
	add_action( 'eshopbox_thankyou', 'eshopbox_order_details_table', 10 );
}

/** Store Event Hooks *****************************************************/

/**
 * Shop Page Handling and Support
 *
 * @see eshopbox_template_redirect()
 * @see eshopbox_nav_menu_item_classes()
 * @see eshopbox_list_pages()
 */
add_action( 'template_redirect', 'eshopbox_template_redirect' );
add_filter( 'wp_nav_menu_objects',  'eshopbox_nav_menu_item_classes', 2, 20 );
add_filter( 'wp_list_pages', 'eshopbox_list_pages' );

/**
 * Logout link
 *
 * @see eshopbox_nav_menu_items()
 */
add_filter( 'wp_nav_menu_objects', 'eshopbox_nav_menu_items', 10, 2 );

/**
 * Clear the cart
 *
 * @see eshopbox_empty_cart()
 * @see eshopbox_clear_cart_after_payment()
 */
if ( get_option( 'eshopbox_clear_cart_on_logout' ) == 'yes' )
	add_action( 'wp_logout', 'eshopbox_empty_cart' );
add_action( 'get_header', 'eshopbox_clear_cart_after_payment' );

/**
 * Disable admin bar
 *
 * @see eshopbox_disable_admin_bar()
 */
add_filter( 'show_admin_bar', 'eshopbox_disable_admin_bar', 10, 1 );

/**
 * Cart Actions
 *
 * @see eshopbox_update_cart_action()
 * @see eshopbox_add_to_cart_action()
 * @see eshopbox_load_persistent_cart()
 */
add_action( 'init', 'eshopbox_update_cart_action' );
add_action( 'init', 'eshopbox_add_to_cart_action' );
add_action( 'wp_login', 'eshopbox_load_persistent_cart', 1, 2 );

/**
 * Checkout Actions
 *
 * @see eshopbox_checkout_action()
 * @see eshopbox_pay_action()
 */
add_action( 'init', 'eshopbox_checkout_action', 20 );
add_action( 'init', 'eshopbox_pay_action', 20 );

/**
 * Login and Registration
 *
 * @see eshopbox_process_login()
 * @see eshopbox_process_registration()
 */
add_action( 'init', 'eshopbox_process_login' );
add_action( 'init', 'eshopbox_process_registration' );

/**
 * Product Downloads
 *
 * @see eshopbox_download_product()
 */
add_action('init', 'eshopbox_download_product');

/**
 * Analytics
 *
 * @see eshopbox_ecommerce_tracking_piwik()
 */
add_action( 'eshopbox_thankyou', 'eshopbox_ecommerce_tracking_piwik' );

/**
 * RSS Feeds
 *
 * @see eshopbox_products_rss_feed()
 */
add_action( 'wp_head', 'eshopbox_products_rss_feed' );

/**
 * Order actions
 *
 * @see eshopbox_cancel_order()
 * @see eshopbox_order_again()
 */
add_action( 'init', 'eshopbox_cancel_order' );
add_action( 'init', 'eshopbox_order_again' );

/**
 * Star Ratings
 *
 * @see eshopbox_add_comment_rating()
 * @see eshopbox_check_comment_rating()
 */
add_action( 'comment_post', 'eshopbox_add_comment_rating', 1 );
add_filter( 'preprocess_comment', 'eshopbox_check_comment_rating', 0 );

/**
 * Filters
 */
add_filter( 'eshopbox_short_description', 'wptexturize'        );
add_filter( 'eshopbox_short_description', 'convert_smilies'    );
add_filter( 'eshopbox_short_description', 'convert_chars'      );
add_filter( 'eshopbox_short_description', 'wpautop'            );
add_filter( 'eshopbox_short_description', 'shortcode_unautop'  );
add_filter( 'eshopbox_short_description', 'prepend_attachment' );
add_filter( 'eshopbox_short_description', 'do_shortcode', 11 ); // AFTER wpautop()