<?php
/**
 * EshopBox Admin
 *
 * Main admin file which loads all settings panels and sets up admin menus.
 *
 * @author 		WooThemes
 * @category 	Admin
 * @package 	EshopBox/Admin
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Functions for the product post type
 */
include_once( 'post-types/product.php' );

/**
 * Functions for the shop_coupon post type
 */
include_once( 'post-types/shop_coupon.php' );

/**
 * Functions for the shop_order post type
 */
include_once( 'post-types/shop_order.php' );

/**
 * Hooks in admin
 */
include_once( 'eshopbox-admin-hooks.php' );

/**
 * Functions in admin
 */
include_once( 'eshopbox-admin-functions.php' );

/**
 * Functions for handling taxonomies
 */
include_once( 'eshopbox-admin-taxonomies.php' );

/**
 * Welcome Page
 */
include_once( 'includes/welcome.php' );

/**
 * Setup the Admin menu in BoxBeat
 *
 * @access public
 * @return void
 */
function eshopbox_admin_menu() {
    global $menu, $eshopbox;

    if ( current_user_can( 'manage_eshopbox' ) )
    $menu[] = array( '', 'read', 'separator-eshopbox', '', 'wp-menu-separator eshopbox' );

    $main_page = add_menu_page( __( 'EshopBox', 'eshopbox' ), __( 'EshopBox', 'eshopbox' ), 'manage_eshopbox', 'eshopbox' , 'eshopbox_settings_page', null, '55.5' );

    $reports_page = add_submenu_page( 'eshopbox', __( 'Reports', 'eshopbox' ),  __( 'Reports', 'eshopbox' ) , 'view_eshopbox_reports', 'eshopbox_reports', 'eshopbox_reports_page' );

    add_submenu_page( 'edit.php?post_type=product', __( 'Attributes', 'eshopbox' ), __( 'Attributes', 'eshopbox' ), 'manage_product_terms', 'eshopbox_attributes', 'eshopbox_attributes_page');

    add_action( 'load-' . $main_page, 'eshopbox_admin_help_tab' );
    add_action( 'load-' . $reports_page, 'eshopbox_admin_help_tab' );

    $wc_screen_id = strtolower( __( 'EshopBox', 'eshopbox' ) );

    $print_css_on = apply_filters( 'eshopbox_screen_ids', array( 'toplevel_page_' . $wc_screen_id, $wc_screen_id . '_page_eshopbox_settings', $wc_screen_id . '_page_eshopbox_reports', 'toplevel_page_eshopbox', 'eshopbox_page_eshopbox_settings', 'eshopbox_page_eshopbox_reports', 'eshopbox_page_eshopbox_status', 'product_page_eshopbox_attributes', 'edit-tags.php', 'edit.php', 'index.php', 'post-new.php', 'post.php' ) );

    foreach ( $print_css_on as $page )
    	add_action( 'admin_print_styles-'. $page, 'eshopbox_admin_css' );
}

add_action('admin_menu', 'eshopbox_admin_menu', 9);

/**
 * Setup the Admin menu in BoxBeat - later priority so they appear last
 *
 * @access public
 * @return void
 */
function eshopbox_admin_menu_after() {
	$settings_page = add_submenu_page( 'eshopbox', __( 'EshopBox Settings', 'eshopbox' ),  __( 'Settings', 'eshopbox' ) , 'manage_eshopbox', 'eshopbox_settings', 'eshopbox_settings_page');
	$status_page = add_submenu_page( 'eshopbox', __( 'EshopBox Status', 'eshopbox' ),  __( 'System Status', 'eshopbox' ) , 'manage_eshopbox', 'eshopbox_status', 'eshopbox_status_page');

	add_action( 'load-' . $settings_page, 'eshopbox_settings_page_init' );
}

add_action('admin_menu', 'eshopbox_admin_menu_after', 50);

/**
 * Loads gateways and shipping methods into memory for use within settings.
 *
 * @access public
 * @return void
 */
function eshopbox_settings_page_init() {
	$GLOBALS['eshopbox']->payment_gateways();
	$GLOBALS['eshopbox']->shipping();
}

/**
 * Highlights the correct top level admin menu item for post type add screens.
 *
 * @access public
 * @return void
 */
function eshopbox_admin_menu_highlight() {
	global $menu, $submenu, $parent_file, $submenu_file, $self, $post_type, $taxonomy;

	$to_highlight_types = array( 'shop_order', 'shop_coupon' );

	if ( isset( $post_type ) ) {
		if ( in_array( $post_type, $to_highlight_types ) ) {
			$submenu_file = 'edit.php?post_type=' . esc_attr( $post_type );
			$parent_file  = 'eshopbox';
		}

		if ( 'product' == $post_type ) {
			$screen = get_current_screen();

			if ( $screen->base == 'edit-tags' && taxonomy_is_product_attribute( $taxonomy ) ) {
				$submenu_file = 'eshopbox_attributes';
				$parent_file  = 'edit.php?post_type=' . esc_attr( $post_type );
			}
		}
	}

	if ( isset( $submenu['eshopbox'] ) && isset( $submenu['eshopbox'][2] ) ) {
		$submenu['eshopbox'][0] = $submenu['eshopbox'][2];
		unset( $submenu['eshopbox'][2] );
	}

	// Sort out Orders menu when on the top level
	if ( ! current_user_can( 'manage_eshopbox' ) ) {
		foreach ( $menu as $key => $menu_item ) {
			if ( strpos( $menu_item[0], _x('Orders', 'Admin menu name', 'eshopbox') ) === 0 ) {

				$menu_name = _x('Orders', 'Admin menu name', 'eshopbox');
				$menu_name_count = '';
				if ( $order_count = eshopbox_processing_order_count() ) {
					$menu_name_count = " <span class='awaiting-mod update-plugins count-$order_count'><span class='processing-count'>" . number_format_i18n( $order_count ) . "</span></span>" ;
				}

				$menu[$key][0] = $menu_name . $menu_name_count;
				$submenu['edit.php?post_type=shop_order'][5][0] = $menu_name;
				break;
			}
		}
	}
}

add_action( 'admin_head', 'eshopbox_admin_menu_highlight' );


/**
 * eshopbox_admin_notices_styles function.
 *
 * @access public
 * @return void
 */
function eshopbox_admin_notices_styles() {

	if ( get_option( '_wc_needs_update' ) == 1 || get_option( '_wc_needs_pages' ) == 1 ) {
		wp_enqueue_style( 'eshopbox-activation', plugins_url(  '/assets/css/activation.css', dirname( __FILE__ ) ) );
		add_action( 'admin_notices', 'eshopbox_admin_install_notices' );
	}

	$template = get_option( 'template' );

	if ( ! current_theme_supports( 'eshopbox' ) && ! in_array( $template, array( 'twentythirteen', 'twentyeleven', 'twentytwelve', 'twentyten' ) ) ) {

		if ( ! empty( $_GET['hide_eshopbox_theme_support_check'] ) ) {
			update_option( 'eshopbox_theme_support_check', $template );
			return;
		}

		if ( get_option( 'eshopbox_theme_support_check' ) !== $template ) {
			wp_enqueue_style( 'eshopbox-activation', plugins_url(  '/assets/css/activation.css', dirname( __FILE__ ) ) );
			add_action( 'admin_notices', 'eshopbox_theme_check_notice' );
		}

	}

}

add_action( 'admin_print_styles', 'eshopbox_admin_notices_styles' );


/**
 * eshopbox_theme_check_notice function.
 *
 * @access public
 * @return void
 */
function eshopbox_theme_check_notice() {
	include( 'includes/notice-theme-support.php' );
}


/**
 * eshopbox_admin_install_notices function.
 *
 * @access public
 * @return void
 */
function eshopbox_admin_install_notices() {
	global $eshopbox;

	// If we need to update, include a message with the update button
	if ( get_option( '_wc_needs_update' ) == 1 ) {
		include( 'includes/notice-update.php' );
	}

	// If we have just installed, show a message with the install pages button
	elseif ( get_option( '_wc_needs_pages' ) == 1 ) {
		include( 'includes/notice-install.php' );
	}
}

/**
 * Include some admin files conditonally.
 *
 * @access public
 * @return void
 */
function eshopbox_admin_init() {
	global $pagenow, $typenow, $post;

	ob_start();

	// Install - Add pages button
	if ( ! empty( $_GET['install_eshopbox_pages'] ) ) {

		require_once( 'eshopbox-admin-install.php' );
		eshopbox_create_pages();

		// We no longer need to install pages
		delete_option( '_wc_needs_pages' );
		delete_transient( '_wc_activation_redirect' );

		// What's new redirect
		wp_safe_redirect( admin_url( 'index.php?page=wc-about&wc-installed=true' ) );
		exit;

	// Skip button
	} elseif ( ! empty( $_GET['skip_install_eshopbox_pages'] ) ) {

		// We no longer need to install pages
		delete_option( '_wc_needs_pages' );
		delete_transient( '_wc_activation_redirect' );

		// Flush rules after install
		flush_rewrite_rules();

		// What's new redirect
		wp_safe_redirect( admin_url( 'index.php?page=wc-about' ) );
		exit;

	// Update button
	} elseif ( ! empty( $_GET['do_update_eshopbox'] ) ) {

		include_once( 'eshopbox-admin-update.php' );
		do_update_eshopbox();

		// Update complete
		delete_option( '_wc_needs_pages' );
		delete_option( '_wc_needs_update' );
		delete_transient( '_wc_activation_redirect' );

		// What's new redirect
		wp_safe_redirect( admin_url( 'index.php?page=wc-about&wc-updated=true' ) );
		exit;
	}

	// Includes
	if ( $typenow == 'post' && isset( $_GET['post'] ) && ! empty( $_GET['post'] ) ) {
		$typenow = $post->post_type;
	} elseif ( empty( $typenow ) && ! empty( $_GET['post'] ) ) {
	    $post = get_post( $_GET['post'] );
	    $typenow = $post->post_type;
	}

	if ( $pagenow == 'index.php' ) {

		include_once( 'eshopbox-admin-dashboard.php' );

	} elseif ( $pagenow == 'admin.php' && isset( $_GET['import'] ) ) {

		include_once( 'eshopbox-admin-import.php' );

	} elseif ( $pagenow == 'post-new.php' || $pagenow == 'post.php' || $pagenow == 'edit.php' ) {

		include_once( 'post-types/writepanels/writepanels-init.php' );

		if ( in_array( $typenow, array( 'product', 'shop_coupon', 'shop_order' ) ) )
			add_action('admin_print_styles', 'eshopbox_admin_help_tab');

	} elseif ( $pagenow == 'users.php' || $pagenow == 'user-edit.php' || $pagenow == 'profile.php' ) {

		include_once( 'eshopbox-admin-users.php' );

	}

	// Register importers
	if ( defined( 'WP_LOAD_IMPORTERS' ) ) {
		include_once( 'importers/importers-init.php' );
	}
}

add_action('admin_init', 'eshopbox_admin_init');


/**
 * Include and display the settings page.
 *
 * @access public
 * @return void
 */
function eshopbox_settings_page() {
	include_once( 'eshopbox-admin-settings.php' );
	eshopbox_settings();
}

/**
 * Include and display the reports page.
 *
 * @access public
 * @return void
 */
function eshopbox_reports_page() {
	include_once( 'eshopbox-admin-reports.php' );
	eshopbox_reports();
}

/**
 * Include and display the attibutes page.
 *
 * @access public
 * @return void
 */
function eshopbox_attributes_page() {
	include_once( 'eshopbox-admin-attributes.php' );
	eshopbox_attributes();
}

/**
 * Include and display the status page.
 *
 * @access public
 * @return void
 */
function eshopbox_status_page() {
	include_once( 'eshopbox-admin-status.php' );
	eshopbox_status();
}


/**
 * Include and add help tabs to BoxBeat admin.
 *
 * @access public
 * @return void
 */
function eshopbox_admin_help_tab() {
	include_once( 'eshopbox-admin-content.php' );
	eshopbox_admin_help_tab_content();
}


/**
 * Include admin scripts and styles.
 *
 * @access public
 * @return void
 */
function eshopbox_admin_scripts() {
	global $eshopbox, $pagenow, $post, $wp_query;

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	// Register scripts
	wp_register_script( 'eshopbox_admin', $eshopbox->plugin_url() . '/assets/js/admin/eshopbox_admin' . $suffix . '.js', array( 'jquery', 'jquery-blockui', 'jquery-placeholder', 'jquery-ui-widget', 'jquery-ui-core', 'jquery-tiptip' ), $eshopbox->version );

	wp_register_script( 'jquery-blockui', $eshopbox->plugin_url() . '/assets/js/jquery-blockui/jquery.blockUI' . $suffix . '.js', array( 'jquery' ), '2.60', true );

	wp_register_script( 'jquery-placeholder', $eshopbox->plugin_url() . '/assets/js/jquery-placeholder/jquery.placeholder' . $suffix . '.js', array( 'jquery' ), $eshopbox->version, true );

	wp_register_script( 'jquery-tiptip', $eshopbox->plugin_url() . '/assets/js/jquery-tiptip/jquery.tipTip' . $suffix . '.js', array( 'jquery' ), $eshopbox->version, true );

	wp_register_script( 'eshopbox_writepanel', $eshopbox->plugin_url() . '/assets/js/admin/write-panels'.$suffix.'.js', array('jquery', 'jquery-ui-datepicker', 'jquery-ui-sortable'), $eshopbox->version );

	wp_register_script( 'ajax-chosen', $eshopbox->plugin_url() . '/assets/js/chosen/ajax-chosen.jquery'.$suffix.'.js', array('jquery', 'chosen'), $eshopbox->version );

	wp_register_script( 'chosen', $eshopbox->plugin_url() . '/assets/js/chosen/chosen.jquery'.$suffix.'.js', array('jquery'), $eshopbox->version );

	// Get admin screen id
    $screen       = get_current_screen();
    $wc_screen_id = strtolower( __( 'EshopBox', 'eshopbox' ) );

    // EshopBox admin pages
    if ( in_array( $screen->id, apply_filters( 'eshopbox_screen_ids', array( 'toplevel_page_' . $wc_screen_id, $wc_screen_id . '_page_eshopbox_settings', $wc_screen_id . '_page_eshopbox_reports', 'toplevel_page_eshopbox', 'eshopbox_page_eshopbox_settings', 'eshopbox_page_eshopbox_reports', 'edit-shop_order', 'edit-shop_coupon', 'shop_coupon', 'shop_order', 'edit-product', 'product' ) ) ) ) {

    	wp_enqueue_script( 'eshopbox_admin' );
    	wp_enqueue_script( 'farbtastic' );
    	wp_enqueue_script( 'ajax-chosen' );
    	wp_enqueue_script( 'chosen' );
    	wp_enqueue_script( 'jquery-ui-sortable' );
    	wp_enqueue_script( 'jquery-ui-autocomplete' );

    }

    // Edit product category pages
    if ( in_array( $screen->id, array('edit-product_cat') ) )
		wp_enqueue_media();

	// Product/Coupon/Orders
	if ( in_array( $screen->id, array( 'shop_coupon', 'shop_order', 'product' ) ) ) {

		wp_enqueue_script( 'eshopbox_writepanel' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_media();
		wp_enqueue_script( 'ajax-chosen' );
		wp_enqueue_script( 'chosen' );
		wp_enqueue_script( 'plupload-all' );

		$eshopbox_witepanel_params = array(
			'remove_item_notice' 			=> __( 'Are you sure you want to remove the selected items? If you have previously reduced this item\'s stock, or this order was submitted by a customer, you will need to manually restore the item\'s stock.', 'eshopbox' ),
			'i18n_select_items'				=> __( 'Please select some items.', 'eshopbox' ),
			'remove_item_meta'				=> __( 'Remove this item meta?', 'eshopbox' ),
			'remove_attribute'				=> __( 'Remove this attribute?', 'eshopbox' ),
			'name_label'					=> __( 'Name', 'eshopbox' ),
			'remove_label'					=> __( 'Remove', 'eshopbox' ),
			'click_to_toggle'				=> __( 'Click to toggle', 'eshopbox' ),
			'values_label'					=> __( 'Value(s)', 'eshopbox' ),
			'text_attribute_tip'			=> __( 'Enter some text, or some attributes by pipe (|) separating values.', 'eshopbox' ),
			'visible_label'					=> __( 'Visible on the product page', 'eshopbox' ),
			'used_for_variations_label'		=> __( 'Used for variations', 'eshopbox' ),
			'new_attribute_prompt'			=> __( 'Enter a name for the new attribute term:', 'eshopbox' ),
			'calc_totals' 					=> __( 'Calculate totals based on order items, discounts, and shipping?', 'eshopbox' ),
			'calc_line_taxes' 				=> __( 'Calculate line taxes? This will calculate taxes based on the customers country. If no billing/shipping is set it will use the store base country.', 'eshopbox' ),
			'copy_billing' 					=> __( 'Copy billing information to shipping information? This will remove any currently entered shipping information.', 'eshopbox' ),
			'load_billing' 					=> __( 'Load the customer\'s billing information? This will remove any currently entered billing information.', 'eshopbox' ),
			'load_shipping' 				=> __( 'Load the customer\'s shipping information? This will remove any currently entered shipping information.', 'eshopbox' ),
			'featured_label'				=> __( 'Featured', 'eshopbox' ),
			'prices_include_tax' 			=> esc_attr( get_option('eshopbox_prices_include_tax') ),
			'round_at_subtotal'				=> esc_attr( get_option( 'eshopbox_tax_round_at_subtotal' ) ),
			'no_customer_selected'			=> __( 'No customer selected', 'eshopbox' ),
			'plugin_url' 					=> $eshopbox->plugin_url(),
			'ajax_url' 						=> admin_url('admin-ajax.php'),
			'order_item_nonce' 				=> wp_create_nonce("order-item"),
			'add_attribute_nonce' 			=> wp_create_nonce("add-attribute"),
			'save_attributes_nonce' 		=> wp_create_nonce("save-attributes"),
			'calc_totals_nonce' 			=> wp_create_nonce("calc-totals"),
			'get_customer_details_nonce' 	=> wp_create_nonce("get-customer-details"),
			'search_products_nonce' 		=> wp_create_nonce("search-products"),
			'calendar_image'				=> $eshopbox->plugin_url().'/assets/images/calendar.png',
			'post_id'						=> $post->ID,
			'base_country'					=> $eshopbox->countries->get_base_country(),
			'currency_format_num_decimals'	=> absint( get_option( 'eshopbox_price_num_decimals' ) ),
			'currency_format_symbol'		=> get_eshopbox_currency_symbol(),
			'currency_format_decimal_sep'	=> esc_attr( stripslashes( get_option( 'eshopbox_price_decimal_sep' ) ) ),
			'currency_format_thousand_sep'	=> esc_attr( stripslashes( get_option( 'eshopbox_price_thousand_sep' ) ) ),
			'currency_format'				=> esc_attr( str_replace( array( '%1$s', '%2$s' ), array( '%s', '%v' ), get_eshopbox_price_format() ) ), // For accounting JS
			'product_types'					=> array_map( 'sanitize_title', get_terms( 'product_type', array( 'hide_empty' => false, 'fields' => 'names' ) ) ),
			'default_attribute_visibility'  => apply_filters( 'default_attribute_visibility', false ),
			'default_attribute_variation'   => apply_filters( 'default_attribute_variation', false )
		 );

		wp_localize_script( 'eshopbox_writepanel', 'eshopbox_writepanel_params', $eshopbox_witepanel_params );
	}

	// Term ordering - only when sorting by term_order
	if ( ( strstr( $screen->id, 'edit-pa_' ) || ( ! empty( $_GET['taxonomy'] ) && in_array( $_GET['taxonomy'], apply_filters( 'eshopbox_sortable_taxonomies', array( 'product_cat' ) ) ) ) ) && ! isset( $_GET['orderby'] ) ) {

		wp_register_script( 'eshopbox_term_ordering', $eshopbox->plugin_url() . '/assets/js/admin/term-ordering.js', array('jquery-ui-sortable'), $eshopbox->version );
		wp_enqueue_script( 'eshopbox_term_ordering' );

		$taxonomy = isset( $_GET['taxonomy'] ) ? eshopbox_clean( $_GET['taxonomy'] ) : '';

		$eshopbox_term_order_params = array(
			'taxonomy' 			=>  $taxonomy
		 );

		wp_localize_script( 'eshopbox_term_ordering', 'eshopbox_term_ordering_params', $eshopbox_term_order_params );

	}

	// Product sorting - only when sorting by menu order on the products page
	if ( current_user_can('edit_others_pages') && $screen->id == 'edit-product' && isset( $wp_query->query['orderby'] ) && $wp_query->query['orderby'] == 'menu_order title' ) {

		wp_enqueue_script( 'eshopbox_product_ordering', $eshopbox->plugin_url() . '/assets/js/admin/product-ordering.js', array('jquery-ui-sortable'), '1.0', true );

	}

	// Reports pages
    if ( in_array( $screen->id, apply_filters( 'eshopbox_reports_screen_ids', array( $wc_screen_id . '_page_eshopbox_reports', apply_filters( 'eshopbox_reports_screen_id', 'eshopbox_page_eshopbox_reports' ) ) ) ) ) {

		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'flot', $eshopbox->plugin_url() . '/assets/js/admin/jquery.flot'.$suffix.'.js', 'jquery', '1.0' );
		wp_enqueue_script( 'flot-resize', $eshopbox->plugin_url() . '/assets/js/admin/jquery.flot.resize'.$suffix.'.js', array('jquery', 'flot'), '1.0' );

	}

	// Chosen RTL
	if ( is_rtl() ) {
		wp_enqueue_script( 'chosen-rtl', $eshopbox->plugin_url() . '/assets/js/chosen/chosen-rtl' . $suffix . '.js', array( 'jquery' ), $eshopbox->version, true );
	}
}

add_action( 'admin_enqueue_scripts', 'eshopbox_admin_scripts' );


/**
 * Queue EshopBox CSS.
 *
 * @access public
 * @return void
 */
function eshopbox_admin_css() {
	global $eshopbox, $typenow, $post, $wp_scripts;

	if ( $typenow == 'post' && ! empty( $_GET['post'] ) ) {
		$typenow = $post->post_type;
	} elseif ( empty( $typenow ) && ! empty( $_GET['post'] ) ) {
        $post = get_post( $_GET['post'] );
        $typenow = $post->post_type;
    }

	if ( $typenow == '' || $typenow == "product" || $typenow == "shop_order" || $typenow == "shop_coupon" ) {
		wp_enqueue_style( 'eshopbox_admin_styles', $eshopbox->plugin_url() . '/assets/css/admin.css' );

		$jquery_version = isset( $wp_scripts->registered['jquery-ui-core']->ver ) ? $wp_scripts->registered['jquery-ui-core']->ver : '1.9.2';

		wp_enqueue_style( 'jquery-ui-style', '//ajax.googleapis.com/ajax/libs/jqueryui/' . $jquery_version . '/themes/smoothness/jquery-ui.css' );
	}

	wp_enqueue_style('farbtastic');

	do_action('eshopbox_admin_css');
}


/**
 * Queue admin menu icons CSS.
 *
 * @access public
 * @return void
 */
function eshopbox_admin_menu_styles() {
	global $eshopbox;
	wp_enqueue_style( 'eshopbox_admin_menu_styles', $eshopbox->plugin_url() . '/assets/css/menu.css' );
}

add_action( 'admin_print_styles', 'eshopbox_admin_menu_styles' );


/**
 * Reorder the WC menu items in admin.
 *
 * @access public
 * @param mixed $menu_order
 * @return void
 */
function eshopbox_admin_menu_order( $menu_order ) {

	// Initialize our custom order array
	$eshopbox_menu_order = array();

	// Get the index of our custom separator
	$eshopbox_separator = array_search( 'separator-eshopbox', $menu_order );

	// Get index of product menu
	$eshopbox_product = array_search( 'edit.php?post_type=product', $menu_order );

	// Loop through menu order and do some rearranging
	foreach ( $menu_order as $index => $item ) :

		if ( ( ( 'eshopbox' ) == $item ) ) :
			$eshopbox_menu_order[] = 'separator-eshopbox';
			$eshopbox_menu_order[] = $item;
			$eshopbox_menu_order[] = 'edit.php?post_type=product';
			unset( $menu_order[$eshopbox_separator] );
			unset( $menu_order[$eshopbox_product] );
		elseif ( !in_array( $item, array( 'separator-eshopbox' ) ) ) :
			$eshopbox_menu_order[] = $item;
		endif;

	endforeach;

	// Return order
	return $eshopbox_menu_order;
}

add_action('menu_order', 'eshopbox_admin_menu_order');


/**
 * eshopbox_admin_custom_menu_order function.
 *
 * @access public
 * @return void
 */
function eshopbox_admin_custom_menu_order() {
	if ( ! current_user_can( 'manage_eshopbox' ) )
		return false;
	return true;
}

add_action( 'custom_menu_order', 'eshopbox_admin_custom_menu_order' );


/**
 * Admin Head
 *
 * Outputs some styles in the admin <head> to show icons on the eshopbox admin pages
 *
 * @access public
 * @return void
 */
function eshopbox_admin_head() {
	global $eshopbox;

	if ( ! current_user_can( 'manage_eshopbox' ) ) return false;
	?>
	<style type="text/css">
		<?php if ( isset($_GET['taxonomy']) && $_GET['taxonomy']=='product_cat' ) : ?>
			.icon32-posts-product { background-position: -243px -5px !important; }
		<?php elseif ( isset($_GET['taxonomy']) && $_GET['taxonomy']=='product_tag' ) : ?>
			.icon32-posts-product { background-position: -301px -5px !important; }
		<?php endif; ?>
	</style>
	<?php
}

add_action('admin_head', 'eshopbox_admin_head');


/**
 * Duplicate a product action
 *
 * @access public
 * @return void
 */
function eshopbox_duplicate_product_action() {
	include_once('includes/duplicate_product.php');
	eshopbox_duplicate_product();
}

add_action('admin_action_duplicate_product', 'eshopbox_duplicate_product_action');


/**
 * Post updated messages
 *
 * @access public
 * @param mixed $messages
 * @return void
 */
function eshopbox_product_updated_messages( $messages ) {
	global $post, $post_ID;

	$messages['product'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __( 'Product updated. <a href="%s">View Product</a>', 'eshopbox' ), esc_url( get_permalink($post_ID) ) ),
		2 => __( 'Custom field updated.', 'eshopbox' ),
		3 => __( 'Custom field deleted.', 'eshopbox' ),
		4 => __( 'Product updated.', 'eshopbox' ),
		5 => isset($_GET['revision']) ? sprintf( __( 'Product restored to revision from %s', 'eshopbox' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __( 'Product published. <a href="%s">View Product</a>', 'eshopbox' ), esc_url( get_permalink($post_ID) ) ),
		7 => __( 'Product saved.', 'eshopbox' ),
		8 => sprintf( __( 'Product submitted. <a target="_blank" href="%s">Preview Product</a>', 'eshopbox' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __( 'Product scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Product</a>', 'eshopbox' ),
		  date_i18n( __( 'M j, Y @ G:i', 'eshopbox' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __( 'Product draft updated. <a target="_blank" href="%s">Preview Product</a>', 'eshopbox' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);

	$messages['shop_order'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => __( 'Order updated.', 'eshopbox' ),
		2 => __( 'Custom field updated.', 'eshopbox' ),
		3 => __( 'Custom field deleted.', 'eshopbox' ),
		4 => __( 'Order updated.', 'eshopbox' ),
		5 => isset($_GET['revision']) ? sprintf( __( 'Order restored to revision from %s', 'eshopbox' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __( 'Order updated.', 'eshopbox' ),
		7 => __( 'Order saved.', 'eshopbox' ),
		8 => __( 'Order submitted.', 'eshopbox' ),
		9 => sprintf( __( 'Order scheduled for: <strong>%1$s</strong>.', 'eshopbox' ),
		  date_i18n( __( 'M j, Y @ G:i', 'eshopbox' ), strtotime( $post->post_date ) ) ),
		10 => __( 'Order draft updated.', 'eshopbox' )
	);

	$messages['shop_coupon'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => __( 'Coupon updated.', 'eshopbox' ),
		2 => __( 'Custom field updated.', 'eshopbox' ),
		3 => __( 'Custom field deleted.', 'eshopbox' ),
		4 => __( 'Coupon updated.', 'eshopbox' ),
		5 => isset($_GET['revision']) ? sprintf( __( 'Coupon restored to revision from %s', 'eshopbox' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __( 'Coupon updated.', 'eshopbox' ),
		7 => __( 'Coupon saved.', 'eshopbox' ),
		8 => __( 'Coupon submitted.', 'eshopbox' ),
		9 => sprintf( __( 'Coupon scheduled for: <strong>%1$s</strong>.', 'eshopbox' ),
		  date_i18n( __( 'M j, Y @ G:i', 'eshopbox' ), strtotime( $post->post_date ) ) ),
		10 => __( 'Coupon draft updated.', 'eshopbox' )
	);

	return $messages;
}

add_filter('post_updated_messages', 'eshopbox_product_updated_messages');


/**
 * Post updated messages
 *
 * @access public
 * @param mixed $types
 * @return void
 */
function eshopbox_admin_comment_types_dropdown( $types ) {
	$types['order_note'] = __( 'Order notes', 'eshopbox' );
	return $types;
}

add_filter( 'admin_comment_types_dropdown', 'eshopbox_admin_comment_types_dropdown' );


/**
 * eshopbox_permalink_settings function.
 *
 * @access public
 * @return void
 */
function eshopbox_permalink_settings() {

	echo wpautop( __( 'These settings control the permalinks used for products. These settings only apply when <strong>not using "default" permalinks above</strong>.', 'eshopbox' ) );

	$permalinks = get_option( 'eshopbox_permalinks' );
	$product_permalink = $permalinks['product_base'];

	// Get shop page
	$shop_page_id 	= eshopbox_get_page_id( 'shop' );
	$base_slug 		= ( $shop_page_id > 0 && get_page( $shop_page_id ) ) ? get_page_uri( $shop_page_id ) : _x( 'shop', 'default-slug', 'eshopbox' );
	$product_base 	= _x( 'product', 'default-slug', 'eshopbox' );

	$structures = array(
		0 => '',
		1 => '/' . trailingslashit( $product_base ),
		2 => '/' . trailingslashit( $base_slug ),
		3 => '/' . trailingslashit( $base_slug ) . trailingslashit( '%product_cat%' )
	);
	?>
	<table class="form-table">
		<tbody>
			<tr>
				<th><label><input name="product_permalink" type="radio" value="<?php echo $structures[0]; ?>" class="wctog" <?php checked( $structures[0], $product_permalink ); ?> /> <?php _e( 'Default', 'eshopbox' ); ?></label></th>
				<td><code><?php echo home_url(); ?>/?product=sample-product</code></td>
			</tr>
			<tr>
				<th><label><input name="product_permalink" type="radio" value="<?php echo $structures[1]; ?>" class="wctog" <?php checked( $structures[1], $product_permalink ); ?> /> <?php _e( 'Product', 'eshopbox' ); ?></label></th>
				<td><code><?php echo home_url(); ?>/<?php echo $product_base; ?>/sample-product/</code></td>
			</tr>
			<?php if ( $shop_page_id ) : ?>
				<tr>
					<th><label><input name="product_permalink" type="radio" value="<?php echo $structures[2]; ?>" class="wctog" <?php checked( $structures[2], $product_permalink ); ?> /> <?php _e( 'Shop base', 'eshopbox' ); ?></label></th>
					<td><code><?php echo home_url(); ?>/<?php echo $base_slug; ?>/sample-product/</code></td>
				</tr>
				<tr>
					<th><label><input name="product_permalink" type="radio" value="<?php echo $structures[3]; ?>" class="wctog" <?php checked( $structures[3], $product_permalink ); ?> /> <?php _e( 'Shop base with category', 'eshopbox' ); ?></label></th>
					<td><code><?php echo home_url(); ?>/<?php echo $base_slug; ?>/product-category/sample-product/</code></td>
				</tr>
			<?php endif; ?>
			<tr>
				<th><label><input name="product_permalink" id="eshopbox_custom_selection" type="radio" value="custom" class="tog" <?php checked( in_array( $product_permalink, $structures ), false ); ?> />
					<?php _e( 'Custom Base', 'eshopbox' ); ?></label></th>
				<td>
					<input name="product_permalink_structure" id="eshopbox_permalink_structure" type="text" value="<?php echo esc_attr( $product_permalink ); ?>" class="regular-text code"> <span class="description"><?php _e( 'Enter a custom base to use. A base <strong>must</strong> be set or BoxBeat will use default instead.', 'eshopbox' ); ?></span>
				</td>
			</tr>
		</tbody>
	</table>
	<script type="text/javascript">
		jQuery(function(){
			jQuery('input.wctog').change(function() {
				jQuery('#eshopbox_permalink_structure').val( jQuery(this).val() );
			});

			jQuery('#eshopbox_permalink_structure').focus(function(){
				jQuery('#eshopbox_custom_selection').click();
			});
		});
	</script>
	<?php
}

/**
 * eshopbox_permalink_settings_init function.
 *
 * @access public
 * @return void
 */
function eshopbox_permalink_settings_init() {

	// Add a section to the permalinks page
	add_settings_section( 'eshopbox-permalink', __( 'Product permalink base', 'eshopbox' ), 'eshopbox_permalink_settings', 'permalink' );

	// Add our settings
	add_settings_field(
		'eshopbox_product_category_slug',      	// id
		__( 'Product category base', 'eshopbox' ), 	// setting title
		'eshopbox_product_category_slug_input',  // display callback
		'permalink',                 				// settings page
		'optional'                  				// settings section
	);
	add_settings_field(
		'eshopbox_product_tag_slug',      		// id
		__( 'Product tag base', 'eshopbox' ), 	// setting title
		'eshopbox_product_tag_slug_input',  		// display callback
		'permalink',                 				// settings page
		'optional'                  				// settings section
	);
	add_settings_field(
		'eshopbox_product_attribute_slug',      	// id
		__( 'Product attribute base', 'eshopbox' ), 	// setting title
		'eshopbox_product_attribute_slug_input',  		// display callback
		'permalink',                 				// settings page
		'optional'                  				// settings section
	);
}

add_action( 'admin_init', 'eshopbox_permalink_settings_init' );

/**
 * eshopbox_permalink_settings_save function.
 *
 * @access public
 * @return void
 */
function eshopbox_permalink_settings_save() {
	if ( ! is_admin() )
		return;

	// We need to save the options ourselves; settings api does not trigger save for the permalinks page
	if ( isset( $_POST['permalink_structure'] ) || isset( $_POST['category_base'] ) && isset( $_POST['product_permalink'] ) ) {
		// Cat and tag bases
		$eshopbox_product_category_slug = eshopbox_clean( $_POST['eshopbox_product_category_slug'] );
		$eshopbox_product_tag_slug = eshopbox_clean( $_POST['eshopbox_product_tag_slug'] );
		$eshopbox_product_attribute_slug = eshopbox_clean( $_POST['eshopbox_product_attribute_slug'] );

		$permalinks = get_option( 'eshopbox_permalinks' );
		if ( ! $permalinks )
			$permalinks = array();

		$permalinks['category_base'] 	= untrailingslashit( $eshopbox_product_category_slug );
		$permalinks['tag_base'] 		= untrailingslashit( $eshopbox_product_tag_slug );
		$permalinks['attribute_base'] 	= untrailingslashit( $eshopbox_product_attribute_slug );

		// Product base
		$product_permalink = eshopbox_clean( $_POST['product_permalink'] );

		if ( $product_permalink == 'custom' ) {
			$product_permalink = eshopbox_clean( $_POST['product_permalink_structure'] );
		} elseif ( empty( $product_permalink ) ) {
			$product_permalink = false;
		}

		$permalinks['product_base'] = untrailingslashit( $product_permalink );

		update_option( 'eshopbox_permalinks', $permalinks );
	}
}

add_action( 'before_eshopbox_init', 'eshopbox_permalink_settings_save' );

/**
 * eshopbox_product_category_slug_input function.
 *
 * @access public
 * @return void
 */
function eshopbox_product_category_slug_input() {
	$permalinks = get_option( 'eshopbox_permalinks' );
	?>
	<input name="eshopbox_product_category_slug" type="text" class="regular-text code" value="<?php if ( isset( $permalinks['category_base'] ) ) echo esc_attr( $permalinks['category_base'] ); ?>" placeholder="<?php echo _x('product-category', 'slug', 'eshopbox') ?>" />
	<?php
}

/**
 * eshopbox_product_tag_slug_input function.
 *
 * @access public
 * @return void
 */
function eshopbox_product_tag_slug_input() {
	$permalinks = get_option( 'eshopbox_permalinks' );
	?>
	<input name="eshopbox_product_tag_slug" type="text" class="regular-text code" value="<?php if ( isset( $permalinks['tag_base'] ) ) echo esc_attr( $permalinks['tag_base'] ); ?>" placeholder="<?php echo _x('product-tag', 'slug', 'eshopbox') ?>" />
	<?php
}

/**
 * eshopbox_product_attribute_slug_input function.
 *
 * @access public
 * @return void
 */
function eshopbox_product_attribute_slug_input() {
	$permalinks = get_option( 'eshopbox_permalinks' );
	?>
	<input name="eshopbox_product_attribute_slug" type="text" class="regular-text code" value="<?php if ( isset( $permalinks['attribute_base'] ) ) echo esc_attr( $permalinks['attribute_base'] ); ?>" /><code>/attribute-name/attribute/</code>
	<?php
}
