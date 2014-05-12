<?php
/**
 * Defines the array of settings which are displayed in admin.
 *
 * Settings are defined here and displayed via functions.
 *
 * @author 		WooThemes
 * @category 	Admin
 * @package 	EshopBox/Admin/Settings
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $eshopbox;

$localisation_setting = defined( 'WPLANG' ) && file_exists( $eshopbox->plugin_path() . '/i18n/languages/informal/eshopbox-' . WPLANG . '.mo' ) ? array(
	'title' => __( 'Localisation', 'eshopbox' ),
	'desc' 		=> sprintf( __( 'Use informal localisation for %s', 'eshopbox' ), WPLANG ),
	'id' 		=> 'eshopbox_informal_localisation_type',
	'type' 		=> 'checkbox',
	'default'	=> 'no',
) : array();

$currency_code_options = get_eshopbox_currencies();

foreach ( $currency_code_options as $code => $name ) {
	$currency_code_options[ $code ] = $name . ' (' . get_eshopbox_currency_symbol( $code ) . ')';
}

$eshopbox_settings['general'] = apply_filters('eshopbox_general_settings', array(

	array( 'title' => __( 'General Options', 'eshopbox' ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),

	array(
		'title' 	=> __( 'Base Location', 'eshopbox' ),
		'desc' 		=> __( 'This is the base location for your business. Tax rates will be based on this country.', 'eshopbox' ),
		'id' 		=> 'eshopbox_default_country',
		'css' 		=> 'min-width:350px;',
		'default'	=> 'GB',
		'type' 		=> 'single_select_country',
		'desc_tip'	=>  true,
	),

	array(
		'title' 	=> __( 'Currency', 'eshopbox' ),
		'desc' 		=> __( "This controls what currency prices are listed at in the catalog and which currency gateways will take payments in.", 'eshopbox' ),
		'id' 		=> 'eshopbox_currency',
		'css' 		=> 'min-width:350px;',
		'default'	=> 'GBP',
		'type' 		=> 'select',
		'class'		=> 'chosen_select',
		'desc_tip'	=>  true,
		'options'   => $currency_code_options
	),

	array(
		'title' => __( 'Allowed Countries', 'eshopbox' ),
		'desc' 		=> __( 'These are countries that you are willing to ship to.', 'eshopbox' ),
		'id' 		=> 'eshopbox_allowed_countries',
		'default'	=> 'all',
		'type' 		=> 'select',
		'class'		=> 'chosen_select',
		'css' 		=> 'min-width:350px;',
		'desc_tip'	=>  true,
		'options' => array(
			'all'  => __( 'All Countries', 'eshopbox' ),
			'specific' => __( 'Specific Countries', 'eshopbox' )
		)
	),

	array(
		'title' => __( 'Specific Countries', 'eshopbox' ),
		'desc' 		=> '',
		'id' 		=> 'eshopbox_specific_allowed_countries',
		'css' 		=> '',
		'default'	=> '',
		'type' 		=> 'multi_select_countries'
	),

	$localisation_setting,

	array(
		'title' => __( 'Store Notice', 'eshopbox' ),
		'desc' 		=> __( 'Enable site-wide store notice text', 'eshopbox' ),
		'id' 		=> 'eshopbox_demo_store',
		'default'	=> 'no',
		'type' 		=> 'checkbox'
	),

	array(
		'title' => __( 'Store Notice Text', 'eshopbox' ),
		'desc' 		=> '',
		'id' 		=> 'eshopbox_demo_store_notice',
		'default'	=> __( 'This is a demo store for testing purposes &mdash; no orders shall be fulfilled.', 'eshopbox' ),
		'type' 		=> 'text',
		'css' 		=> 'min-width:300px;',
	),

	array( 'type' => 'sectionend', 'id' => 'general_options'),

	array(	'title' => __( 'Cart, Checkout and Accounts', 'eshopbox' ), 'type' => 'title', 'id' => 'checkout_account_options' ),

	array(
		'title' => __( 'Coupons', 'eshopbox' ),
		'desc'          => __( 'Enable the use of coupons', 'eshopbox' ),
		'id'            => 'eshopbox_enable_coupons',
		'default'       => 'yes',
		'type'          => 'checkbox',
		'desc_tip'		=>  __( 'Coupons can be applied from the cart and checkout pages.', 'eshopbox' ),
	),

	array(
		'title' => __( 'Checkout', 'eshopbox' ),
		'desc' 		=> __( 'Enable guest checkout (no account required)', 'eshopbox' ),
		'id' 		=> 'eshopbox_enable_guest_checkout',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup'	=> 'start'
	),

	array(
		'desc' 		=> __( 'Enable customer note field on checkout', 'eshopbox' ),
		'id' 		=> 'eshopbox_enable_order_comments',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> ''
	),

	array(
		'desc' 		=> __( 'Force secure checkout', 'eshopbox' ),
		'id' 		=> 'eshopbox_force_ssl_checkout',
		'default'	=> 'no',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> '',
		'show_if_checked' => 'option',
		'desc_tip'	=>  __( 'Force SSL (HTTPS) on the checkout pages (an SSL Certificate is required).', 'eshopbox' ),
	),

	array(
		'desc' 		=> __( 'Un-force HTTPS when leaving the checkout', 'eshopbox' ),
		'id' 		=> 'eshopbox_unforce_ssl_checkout',
		'default'	=> 'no',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'end',
		'show_if_checked' => 'yes',
	),

	array(
		'title' => __( 'Registration', 'eshopbox' ),
		'desc' 		=> __( 'Allow registration on the checkout page', 'eshopbox' ),
		'id' 		=> 'eshopbox_enable_signup_and_login_from_checkout',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'start'
	),

	array(
		'desc' 		=> __( 'Allow registration on the "My Account" page', 'eshopbox' ),
		'id' 		=> 'eshopbox_enable_myaccount_registration',
		'default'	=> 'no',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> ''
	),

	array(
		'desc' 		=> __( 'Register using the email address for the username', 'eshopbox' ),
		'id' 		=> 'eshopbox_registration_email_for_username',
		'default'	=> 'no',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'end'
	),

	array(
		'title' => __( 'Customer Accounts', 'eshopbox' ),
		'desc' 		=> __( 'Prevent customers from accessing BoxBeat admin', 'eshopbox' ),
		'id' 		=> 'eshopbox_lock_down_admin',
		'default'	=> 'no',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'start'
	),

	array(
		'desc' 		=> __( 'Clear cart when logging out', 'eshopbox' ),
		'id' 		=> 'eshopbox_clear_cart_on_logout',
		'default'	=> 'no',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> ''
	),

	array(
		'desc' 		=> __( 'Allow customers to repurchase orders from their account page', 'eshopbox' ),
		'id' 		=> 'eshopbox_allow_customers_to_reorder',
		'default'	=> 'no',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'end'
	),

	array( 'type' => 'sectionend', 'id' => 'checkout_account_options'),

	array(	'title' => __( 'Styles and Scripts', 'eshopbox' ), 'type' => 'title', 'id' => 'script_styling_options' ),

	array(
		'title' => __( 'Styling', 'eshopbox' ),
		'desc' 		=> __( 'Enable EshopBox CSS', 'eshopbox' ),
		'id' 		=> 'eshopbox_frontend_css',
		'default'	=> 'yes',
		'type' 		=> 'checkbox'
	),

	array(
		'type' 		=> 'frontend_styles'
	),

	array(
		'title' => __( 'Scripts', 'eshopbox' ),
		'desc' 	=> __( 'Enable Lightbox', 'eshopbox' ),
		'id' 		=> 'eshopbox_enable_lightbox',
		'default'	=> 'yes',
		'desc_tip'	=> __( 'Include EshopBox\'s lightbox. Product gallery images and the add review form will open in a lightbox.', 'eshopbox' ),
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'start'
	),

	array(
		'desc' 		=> __( 'Enable enhanced country select boxes', 'eshopbox' ),
		'id' 		=> 'eshopbox_enable_chosen',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'end',
		'desc_tip'	=> __( 'This will enable a script allowing the country fields to be searchable.', 'eshopbox' ),
	),

	array( 'type' => 'sectionend', 'id' => 'script_styling_options'),

	array(	'title' => __( 'Downloadable Products', 'eshopbox' ), 'type' => 'title', 'id' => 'digital_download_options' ),

	array(
		'title' => __( 'File Download Method', 'eshopbox' ),
		'desc' 		=> __( 'Forcing downloads will keep URLs hidden, but some servers may serve large files unreliably. If supported, <code>X-Accel-Redirect</code>/ <code>X-Sendfile</code> can be used to serve downloads instead (server requires <code>mod_xsendfile</code>).', 'eshopbox' ),
		'id' 		=> 'eshopbox_file_download_method',
		'type' 		=> 'select',
		'class'		=> 'chosen_select',
		'css' 		=> 'min-width:300px;',
		'default'	=> 'force',
		'desc_tip'	=>  true,
		'options' => array(
			'force'  	=> __( 'Force Downloads', 'eshopbox' ),
			'xsendfile' => __( 'X-Accel-Redirect/X-Sendfile', 'eshopbox' ),
			'redirect'  => __( 'Redirect only', 'eshopbox' ),
		)
	),

	array(
		'title' => __( 'Access Restriction', 'eshopbox' ),
		'desc' 		=> __( 'Downloads require login', 'eshopbox' ),
		'id' 		=> 'eshopbox_downloads_require_login',
		'type' 		=> 'checkbox',
		'default'	=> 'no',
		'desc_tip'	=> __( 'This setting does not apply to guest purchases.', 'eshopbox' ),
		'checkboxgroup'		=> 'start'
	),

	array(
		'desc' 		=> __( 'Grant access to downloadable products after payment', 'eshopbox' ),
		'id' 		=> 'eshopbox_downloads_grant_access_after_payment',
		'type' 		=> 'checkbox',
		'default'	=> 'yes',
		'desc_tip'	=> __( 'Enable this option to grant access to downloads when orders are "processing", rather than "completed".', 'eshopbox' ),
		'checkboxgroup'		=> 'end'
	),

	array( 'type' => 'sectionend', 'id' => 'digital_download_options' ),

)); // End general settings

// Get shop page
$shop_page_id = eshopbox_get_page_id('shop');

$base_slug = ($shop_page_id > 0 && get_page( $shop_page_id )) ? get_page_uri( $shop_page_id ) : 'shop';

$eshopbox_prepend_shop_page_to_products_warning = '';

if ( $shop_page_id > 0 && sizeof(get_pages("child_of=$shop_page_id")) > 0 )
	$eshopbox_prepend_shop_page_to_products_warning = ' <mark class="notice">' . __( 'Note: The shop page has children - child pages will not work if you enable this option.', 'eshopbox' ) . '</mark>';

$eshopbox_settings['pages'] = apply_filters('eshopbox_page_settings', array(

	array(
		'title' => __( 'Page Setup', 'eshopbox' ),
		'type' => 'title',
		'desc' => sprintf( __( 'Set up core EshopBox pages here, for example the base page. The base page can also be used in your %sproduct permalinks%s.', 'eshopbox' ), '<a target="_blank" href="' . admin_url( 'options-permalink.php' ) . '">', '</a>' ),
		'id' => 'page_options'
	),

	array(
		'title' => __( 'Shop Base Page', 'eshopbox' ),
		'desc' 		=> __( 'This sets the base page of your shop - this is where your product archive will be.', 'eshopbox' ),
		'id' 		=> 'eshopbox_shop_page_id',
		'type' 		=> 'single_select_page',
		'default'	=> '',
		'class'		=> 'chosen_select_nostd',
		'css' 		=> 'min-width:300px;',
		'desc_tip'	=>  true
	),

	array(
		'title' => __( 'Terms Page ID', 'eshopbox' ),
		'desc' 		=> __( 'If you define a "Terms" page the customer will be asked if they accept them when checking out.', 'eshopbox' ),
		'id' 		=> 'eshopbox_terms_page_id',
		'default'	=> '',
		'class'		=> 'chosen_select_nostd',
		'css' 		=> 'min-width:300px;',
		'type' 		=> 'single_select_page',
		'desc_tip'	=>  true,
	),

	array( 'type' => 'sectionend', 'id' => 'page_options' ),

	array( 'title' => __( 'Shop Pages', 'eshopbox' ), 'type' => 'title', 'desc' => __( 'The following pages need selecting so that EshopBox knows where they are. These pages should have been created upon installation of the plugin, if not you will need to create them.', 'eshopbox' ) ),

	array(
		'title' => __( 'Cart Page', 'eshopbox' ),
		'desc' 		=> __( 'Page contents: [eshopbox_cart]', 'eshopbox' ),
		'id' 		=> 'eshopbox_cart_page_id',
		'type' 		=> 'single_select_page',
		'default'	=> '',
		'class'		=> 'chosen_select_nostd',
		'css' 		=> 'min-width:300px;',
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Checkout Page', 'eshopbox' ),
		'desc' 		=> __( 'Page contents: [eshopbox_checkout]', 'eshopbox' ),
		'id' 		=> 'eshopbox_checkout_page_id',
		'type' 		=> 'single_select_page',
		'default'	=> '',
		'class'		=> 'chosen_select_nostd',
		'css' 		=> 'min-width:300px;',
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Pay Page', 'eshopbox' ),
		'desc' 		=> __( 'Page contents: [eshopbox_pay] Parent: "Checkout"', 'eshopbox' ),
		'id' 		=> 'eshopbox_pay_page_id',
		'type' 		=> 'single_select_page',
		'default'	=> '',
		'class'		=> 'chosen_select_nostd',
		'css' 		=> 'min-width:300px;',
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Thanks Page', 'eshopbox' ),
		'desc' 		=> __( 'Page contents: [eshopbox_thankyou] Parent: "Checkout"', 'eshopbox' ),
		'id' 		=> 'eshopbox_thanks_page_id',
		'type' 		=> 'single_select_page',
		'default'	=> '',
		'class'		=> 'chosen_select_nostd',
		'css' 		=> 'min-width:300px;',
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'My Account Page', 'eshopbox' ),
		'desc' 		=> __( 'Page contents: [eshopbox_my_account]', 'eshopbox' ),
		'id' 		=> 'eshopbox_myaccount_page_id',
		'type' 		=> 'single_select_page',
		'default'	=> '',
		'class'		=> 'chosen_select_nostd',
		'css' 		=> 'min-width:300px;',
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Edit Address Page', 'eshopbox' ),
		'desc' 		=> __( 'Page contents: [eshopbox_edit_address] Parent: "My Account"', 'eshopbox' ),
		'id' 		=> 'eshopbox_edit_address_page_id',
		'type' 		=> 'single_select_page',
		'default'	=> '',
		'class'		=> 'chosen_select_nostd',
		'css' 		=> 'min-width:300px;',
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'View Order Page', 'eshopbox' ),
		'desc' 		=> __( 'Page contents: [eshopbox_view_order] Parent: "My Account"', 'eshopbox' ),
		'id' 		=> 'eshopbox_view_order_page_id',
		'type' 		=> 'single_select_page',
		'default'	=> '',
		'class'		=> 'chosen_select_nostd',
		'css' 		=> 'min-width:300px;',
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Change Password Page', 'eshopbox' ),
		'desc' 		=> __( 'Page contents: [eshopbox_change_password] Parent: "My Account"', 'eshopbox' ),
		'id' 		=> 'eshopbox_change_password_page_id',
		'type' 		=> 'single_select_page',
		'default'	=> '',
		'class'		=> 'chosen_select_nostd',
		'css' 		=> 'min-width:300px;',
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Logout Page', 'eshopbox' ),
		'desc' 		=> __( 'Parent: "My Account"', 'eshopbox' ),
		'id' 		=> 'eshopbox_logout_page_id',
		'type' 		=> 'single_select_page',
		'default'	=> '',
		'class'		=> 'chosen_select_nostd',
		'css' 		=> 'min-width:300px;',
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Lost Password Page', 'eshopbox' ),
		'desc' 		=> __( 'Page contents: [eshopbox_lost_password] Parent: "My Account"', 'eshopbox' ),
		'id' 		=> 'eshopbox_lost_password_page_id',
		'type' 		=> 'single_select_page',
		'default'	=> '',
		'class'		=> 'chosen_select_nostd',
		'css' 		=> 'min-width:300px;',
		'desc_tip'	=>  true,
	),

	array( 'type' => 'sectionend', 'id' => 'page_options')

)); // End pages settings


$eshopbox_settings['catalog'] = apply_filters('eshopbox_catalog_settings', array(

	array(	'title' => __( 'Catalog Options', 'eshopbox' ), 'type' => 'title','desc' => '', 'id' => 'catalog_options' ),

	array(
		'title' => __( 'Default Product Sorting', 'eshopbox' ),
		'desc' 		=> __( 'This controls the default sort order of the catalog.', 'eshopbox' ),
		'id' 		=> 'eshopbox_default_catalog_orderby',
		'css' 		=> 'min-width:150px;',
		'default'	=> 'title',
		'type' 		=> 'select',
		'options' => apply_filters('eshopbox_default_catalog_orderby_options', array(
			'menu_order' => __( 'Default sorting (custom ordering + name)', 'eshopbox' ),
			'popularity' => __( 'Popularity (sales)', 'eshopbox' ),
			'rating'     => __( 'Average Rating', 'eshopbox' ),
			'date'       => __( 'Sort by most recent', 'eshopbox' ),
			'price'      => __( 'Sort by price (asc)', 'eshopbox' ),
			'price-desc' => __( 'Sort by price (desc)', 'eshopbox' ),
		)),
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Shop Page Display', 'eshopbox' ),
		'desc' 		=> __( 'This controls what is shown on the product archive.', 'eshopbox' ),
		'id' 		=> 'eshopbox_shop_page_display',
		'css' 		=> 'min-width:150px;',
		'default'	=> '',
		'type' 		=> 'select',
		'options' => array(
			''  			=> __( 'Show products', 'eshopbox' ),
			'subcategories' => __( 'Show subcategories', 'eshopbox' ),
			'both'   		=> __( 'Show both', 'eshopbox' ),
		),
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Default Category Display', 'eshopbox' ),
		'desc' 		=> __( 'This controls what is shown on category archives.', 'eshopbox' ),
		'id' 		=> 'eshopbox_category_archive_display',
		'css' 		=> 'min-width:150px;',
		'default'	=> '',
		'type' 		=> 'select',
		'options' => array(
			''  			=> __( 'Show products', 'eshopbox' ),
			'subcategories' => __( 'Show subcategories', 'eshopbox' ),
			'both'   		=> __( 'Show both', 'eshopbox' ),
		),
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Add to cart', 'eshopbox' ),
		'desc' 		=> __( 'Redirect to the cart page after successful addition', 'eshopbox' ),
		'id' 		=> 'eshopbox_cart_redirect_after_add',
		'default'	=> 'no',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'start'
	),

	array(
		'desc' 		=> __( 'Enable AJAX add to cart buttons on archives', 'eshopbox' ),
		'id' 		=> 'eshopbox_enable_ajax_add_to_cart',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'end'
	),

	array( 'type' => 'sectionend', 'id' => 'catalog_options' ),

	array(	'title' => __( 'Product Data', 'eshopbox' ), 'type' => 'title', 'desc' => __( 'The following options affect the fields available on the edit product page.', 'eshopbox' ), 'id' => 'product_data_options' ),

	array(
		'title' => __( 'Product Fields', 'eshopbox' ),
		'desc' 		=> __( 'Enable the <strong>SKU</strong> field for products', 'eshopbox' ),
		'id' 		=> 'eshopbox_enable_sku',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'start'
	),

	array(
		'desc' 		=> __( 'Enable the <strong>weight</strong> field for products (some shipping methods may require this)', 'eshopbox' ),
		'id' 		=> 'eshopbox_enable_weight',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> ''
	),

	array(
		'desc' 		=> __( 'Enable the <strong>dimension</strong> fields for products (some shipping methods may require this)', 'eshopbox' ),
		'id' 		=> 'eshopbox_enable_dimensions',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> ''
	),

	array(
		'desc' 		=> __( 'Show <strong>weight and dimension</strong> values on the <strong>Additional Information</strong> tab', 'eshopbox' ),
		'id' 		=> 'eshopbox_enable_dimension_product_attributes',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'end'
	),

	array(
		'title' => __( 'Weight Unit', 'eshopbox' ),
		'desc' 		=> __( 'This controls what unit you will define weights in.', 'eshopbox' ),
		'id' 		=> 'eshopbox_weight_unit',
		'css' 		=> 'min-width:150px;',
		'default'	=> 'kg',
		'type' 		=> 'select',
		'options' => array(
			'kg'  => __( 'kg', 'eshopbox' ),
			'g'   => __( 'g', 'eshopbox' ),
			'lbs' => __( 'lbs', 'eshopbox' ),
			'oz' => __( 'oz', 'eshopbox' ),
		),
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Dimensions Unit', 'eshopbox' ),
		'desc' 		=> __( 'This controls what unit you will define lengths in.', 'eshopbox' ),
		'id' 		=> 'eshopbox_dimension_unit',
		'css' 		=> 'min-width:150px;',
		'default'	=> 'cm',
		'type' 		=> 'select',
		'options' => array(
			'm'  => __( 'm', 'eshopbox' ),
			'cm' => __( 'cm', 'eshopbox' ),
			'mm' => __( 'mm', 'eshopbox' ),
			'in' => __( 'in', 'eshopbox' ),
			'yd' => __( 'yd', 'eshopbox' ),
		),
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Product Ratings', 'eshopbox' ),
		'desc' 		=> __( 'Enable ratings on reviews', 'eshopbox' ),
		'id' 		=> 'eshopbox_enable_review_rating',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'start',
		'show_if_checked' => 'option',
	),

	array(
		'desc' 		=> __( 'Ratings are required to leave a review', 'eshopbox' ),
		'id' 		=> 'eshopbox_review_rating_required',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> '',
		'show_if_checked' => 'yes',
	),

	array(
		'desc' 		=> __( 'Show "verified owner" label for customer reviews', 'eshopbox' ),
		'id' 		=> 'eshopbox_review_rating_verification_label',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'end',
		'show_if_checked' => 'yes',
	),

	array( 'type' => 'sectionend', 'id' => 'product_review_options' ),

	array(	'title' => __( 'Pricing Options', 'eshopbox' ), 'type' => 'title', 'desc' => __( 'The following options affect how prices are displayed on the frontend.', 'eshopbox' ), 'id' => 'pricing_options' ),

	array(
		'title' => __( 'Currency Position', 'eshopbox' ),
		'desc' 		=> __( 'This controls the position of the currency symbol.', 'eshopbox' ),
		'id' 		=> 'eshopbox_currency_pos',
		'css' 		=> 'min-width:150px;',
		'default'	=> 'left',
		'type' 		=> 'select',
		'options' => array(
			'left' => __( 'Left', 'eshopbox' ),
			'right' => __( 'Right', 'eshopbox' ),
			'left_space' => __( 'Left (with space)', 'eshopbox' ),
			'right_space' => __( 'Right (with space)', 'eshopbox' )
		),
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Thousand Separator', 'eshopbox' ),
		'desc' 		=> __( 'This sets the thousand separator of displayed prices.', 'eshopbox' ),
		'id' 		=> 'eshopbox_price_thousand_sep',
		'css' 		=> 'width:50px;',
		'default'	=> ',',
		'type' 		=> 'text',
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Decimal Separator', 'eshopbox' ),
		'desc' 		=> __( 'This sets the decimal separator of displayed prices.', 'eshopbox' ),
		'id' 		=> 'eshopbox_price_decimal_sep',
		'css' 		=> 'width:50px;',
		'default'	=> '.',
		'type' 		=> 'text',
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Number of Decimals', 'eshopbox' ),
		'desc' 		=> __( 'This sets the number of decimal points shown in displayed prices.', 'eshopbox' ),
		'id' 		=> 'eshopbox_price_num_decimals',
		'css' 		=> 'width:50px;',
		'default'	=> '2',
		'desc_tip'	=>  true,
		'type' 		=> 'number',
		'custom_attributes' => array(
			'min' 	=> 0,
			'step' 	=> 1
		)
	),

	array(
		'title'		=> __( 'Trailing Zeros', 'eshopbox' ),
		'desc' 		=> __( 'Remove zeros after the decimal point. e.g. <code>$10.00</code> becomes <code>$10</code>', 'eshopbox' ),
		'id' 		=> 'eshopbox_price_trim_zeros',
		'default'	=> 'yes',
		'type' 		=> 'checkbox'
	),

	array( 'type' => 'sectionend', 'id' => 'pricing_options' ),

	array(	'title' => __( 'Image Options', 'eshopbox' ), 'type' => 'title','desc' => sprintf(__( 'These settings affect the actual dimensions of images in your catalog - the display on the front-end will still be affected by CSS styles. After changing these settings you may need to <a href="%s">regenerate your thumbnails</a>.', 'eshopbox' ), 'http://boxbeat.org/extend/plugins/regenerate-thumbnails/'), 'id' => 'image_options' ),

	array(
		'title' => __( 'Catalog Images', 'eshopbox' ),
		'desc' 		=> __( 'This size is usually used in product listings', 'eshopbox' ),
		'id' 		=> 'shop_catalog_image_size',
		'css' 		=> '',
		'type' 		=> 'image_width',
		'default'	=> array(
			'width' 	=> '150',
			'height'	=> '150',
			'crop'		=> true
		),
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Single Product Image', 'eshopbox' ),
		'desc' 		=> __( 'This is the size used by the main image on the product page.', 'eshopbox' ),
		'id' 		=> 'shop_single_image_size',
		'css' 		=> '',
		'type' 		=> 'image_width',
		'default'	=> array(
			'width' 	=> '300',
			'height'	=> '300',
			'crop'		=> 1
		),
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Product Thumbnails', 'eshopbox' ),
		'desc' 		=> __( 'This size is usually used for the gallery of images on the product page.', 'eshopbox' ),
		'id' 		=> 'shop_thumbnail_image_size',
		'css' 		=> '',
		'type' 		=> 'image_width',
		'default'	=> array(
			'width' 	=> '90',
			'height'	=> '90',
			'crop'		=> 1
		),
		'desc_tip'	=>  true,
	),

	array( 'type' => 'sectionend', 'id' => 'image_options' ),

)); // End catalog settings


$eshopbox_settings['inventory'] = apply_filters('eshopbox_inventory_settings', array(

	array(	'title' => __( 'Inventory Options', 'eshopbox' ), 'type' => 'title','desc' => '', 'id' => 'inventory_options' ),

	array(
		'title' => __( 'Manage Stock', 'eshopbox' ),
		'desc' 		=> __( 'Enable stock management', 'eshopbox' ),
		'id' 		=> 'eshopbox_manage_stock',
		'default'	=> 'yes',
		'type' 		=> 'checkbox'
	),

	array(
		'title' => __( 'Hold Stock (minutes)', 'eshopbox' ),
		'desc' 		=> __( 'Hold stock (for unpaid orders) for x minutes. When this limit is reached, the pending order will be cancelled. Leave blank to disable.', 'eshopbox' ),
		'id' 		=> 'eshopbox_hold_stock_minutes',
		'type' 		=> 'number',
		'custom_attributes' => array(
			'min' 	=> 0,
			'step' 	=> 1
		),
		'css' 		=> 'width:50px;',
		'default'	=> '60'
	),

	array(
		'title' => __( 'Notifications', 'eshopbox' ),
		'desc' 		=> __( 'Enable low stock notifications', 'eshopbox' ),
		'id' 		=> 'eshopbox_notify_low_stock',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup' => 'start'
	),

	array(
		'desc' 		=> __( 'Enable out of stock notifications', 'eshopbox' ),
		'id' 		=> 'eshopbox_notify_no_stock',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup' => 'end'
	),

	array(
		'title' => __( 'Notification Recipient', 'eshopbox' ),
		'desc' 		=> '',
		'id' 		=> 'eshopbox_stock_email_recipient',
		'type' 		=> 'email',
		'default'	=> get_option( 'admin_email' )
	),

	array(
		'title' => __( 'Low Stock Threshold', 'eshopbox' ),
		'desc' 		=> '',
		'id' 		=> 'eshopbox_notify_low_stock_amount',
		'css' 		=> 'width:50px;',
		'type' 		=> 'number',
		'custom_attributes' => array(
			'min' 	=> 0,
			'step' 	=> 1
		),
		'default'	=> '2'
	),

	array(
		'title' => __( 'Out Of Stock Threshold', 'eshopbox' ),
		'desc' 		=> '',
		'id' 		=> 'eshopbox_notify_no_stock_amount',
		'css' 		=> 'width:50px;',
		'type' 		=> 'number',
		'custom_attributes' => array(
			'min' 	=> 0,
			'step' 	=> 1
		),
		'default'	=> '0'
	),

	array(
		'title' => __( 'Out Of Stock Visibility', 'eshopbox' ),
		'desc' 		=> __( 'Hide out of stock items from the catalog', 'eshopbox' ),
		'id' 		=> 'eshopbox_hide_out_of_stock_items',
		'default'	=> 'no',
		'type' 		=> 'checkbox'
	),

	array(
		'title' => __( 'Stock Display Format', 'eshopbox' ),
		'desc' 		=> __( 'This controls how stock is displayed on the frontend.', 'eshopbox' ),
		'id' 		=> 'eshopbox_stock_format',
		'css' 		=> 'min-width:150px;',
		'default'	=> '',
		'type' 		=> 'select',
		'options' => array(
			''  			=> __( 'Always show stock e.g. "12 in stock"', 'eshopbox' ),
			'low_amount'	=> __( 'Only show stock when low e.g. "Only 2 left in stock" vs. "In Stock"', 'eshopbox' ),
			'no_amount' 	=> __( 'Never show stock amount', 'eshopbox' ),
		),
		'desc_tip'	=>  true,
	),

	array( 'type' => 'sectionend', 'id' => 'inventory_options'),

)); // End inventory settings


$eshopbox_settings['shipping'] = apply_filters('eshopbox_shipping_settings', array(

	array( 'title' => __( 'Shipping Options', 'eshopbox' ), 'type' => 'title', 'id' => 'shipping_options' ),

	array(
		'title' 		=> __( 'Shipping Calculations', 'eshopbox' ),
		'desc' 		=> __( 'Enable shipping', 'eshopbox' ),
		'id' 		=> 'eshopbox_calc_shipping',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'start'
	),

	array(
		'desc' 		=> __( 'Enable the shipping calculator on the cart page', 'eshopbox' ),
		'id' 		=> 'eshopbox_enable_shipping_calc',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> ''
	),

	array(
		'desc' 		=> __( 'Hide shipping costs until an address is entered', 'eshopbox' ),
		'id' 		=> 'eshopbox_shipping_cost_requires_address',
		'default'	=> 'no',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'end'
	),

	array(
		'title' 	=> __( 'Shipping Method Display', 'eshopbox' ),
		'desc' 		=> __( 'This controls how multiple shipping methods are displayed on the frontend.', 'eshopbox' ),
		'id' 		=> 'eshopbox_shipping_method_format',
		'css' 		=> 'min-width:150px;',
		'default'	=> '',
		'type' 		=> 'select',
		'options' => array(
			''  			=> __( 'Radio buttons', 'eshopbox' ),
			'select'		=> __( 'Select box', 'eshopbox' ),
		),
		'desc_tip'	=>  true,
	),

	array(
		'title' 	=> __( 'Shipping Destination', 'eshopbox' ),
		'desc' 		=> __( 'Only ship to the users billing address', 'eshopbox' ),
		'id' 		=> 'eshopbox_ship_to_billing_address_only',
		'default'	=> 'no',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'start'
	),

	array(
		'desc' 		=> __( 'Ship to billing address by default', 'eshopbox' ),
		'id' 		=> 'eshopbox_ship_to_same_address',
		'default'	=> 'yes',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> ''
	),

	array(
		'desc' 		=> __( 'Collect shipping address even when not required', 'eshopbox' ),
		'id' 		=> 'eshopbox_require_shipping_address',
		'default'	=> 'no',
		'type' 		=> 'checkbox',
		'checkboxgroup'		=> 'end'
	),

	array(
		'type' 		=> 'shipping_methods',
	),

	array( 'type' => 'sectionend', 'id' => 'shipping_options' ),

)); // End shipping settings


$eshopbox_settings['payment_gateways'] = apply_filters('eshopbox_payment_gateways_settings', array(

	array( 'title' => __( 'Payment Gateways', 'eshopbox' ), 'desc' => __( 'Installed payment gateways are displayed below. Drag and drop payment gateways to control their display order on the checkout.', 'eshopbox' ), 'type' => 'title', 'id' => 'payment_gateways_options' ),

	array(
		'type' 		=> 'payment_gateways',
	),

	array( 'type' => 'sectionend', 'id' => 'payment_gateways_options' ),

)); // End payment_gateway settings

$tax_classes = array_filter( array_map( 'trim', explode( "\n", get_option( 'eshopbox_tax_classes' ) ) ) );
$classes_options = array();
if ( $tax_classes )
	foreach ( $tax_classes as $class )
		$classes_options[ sanitize_title( $class ) ] = esc_html( $class );

$eshopbox_settings['tax'] = apply_filters('eshopbox_tax_settings', array(

	array(	'title' => __( 'Tax Options', 'eshopbox' ), 'type' => 'title','desc' => '', 'id' => 'tax_options' ),

	array(
		'title' => __( 'Enable Taxes', 'eshopbox' ),
		'desc' 		=> __( 'Enable taxes and tax calculations', 'eshopbox' ),
		'id' 		=> 'eshopbox_calc_taxes',
		'default'	=> 'no',
		'type' 		=> 'checkbox'
	),

	array(
		'title' => __( 'Prices Entered With Tax', 'eshopbox' ),
		'id' 		=> 'eshopbox_prices_include_tax',
		'default'	=> 'no',
		'type' 		=> 'radio',
		'desc_tip'	=>  __( 'This option is important as it will affect how you input prices. Changing it will not update existing products.', 'eshopbox' ),
		'options'	=> array(
			'yes' => __( 'Yes, I will enter prices inclusive of tax', 'eshopbox' ),
			'no' => __( 'No, I will enter prices exclusive of tax', 'eshopbox' )
		),
	),

	array(
		'title'     => __( 'Calculate Tax Based On:', 'eshopbox' ),
		'id'        => 'eshopbox_tax_based_on',
		'desc_tip'	=>  __( 'This option determines which address is used to calculate tax.', 'eshopbox' ),
		'default'   => 'shipping',
		'type'      => 'select',
		'options'   => array(
			'shipping' => __( 'Customer shipping address', 'eshopbox' ),
			'billing'  => __( 'Customer billing address', 'eshopbox' ),
			'base'     => __( 'Shop base address', 'eshopbox' )
		),
	),

	array(
		'title'     => __( 'Default Customer Address:', 'eshopbox' ),
		'id'        => 'eshopbox_default_customer_address',
		'desc_tip'	=>  __( 'This option determines the customers default address (before they input their own).', 'eshopbox' ),
		'default'   => 'base',
		'type'      => 'select',
		'options'   => array(
			''     => __( 'No address', 'eshopbox' ),
			'base' => __( 'Shop base address', 'eshopbox' ),
		),
	),

	array(
		'title' 		=> __( 'Shipping Tax Class:', 'eshopbox' ),
		'desc' 		=> __( 'Optionally control which tax class shipping gets, or leave it so shipping tax is based on the cart items themselves.', 'eshopbox' ),
		'id' 		=> 'eshopbox_shipping_tax_class',
		'css' 		=> 'min-width:150px;',
		'default'	=> 'title',
		'type' 		=> 'select',
		'options' 	=> array( '' => __( 'Shipping tax class based on cart items', 'eshopbox' ), 'standard' => __( 'Standard', 'eshopbox' ) ) + $classes_options,
		'desc_tip'	=>  true,
	),

	array(
		'title' => __( 'Rounding', 'eshopbox' ),
		'desc' 		=> __( 'Round tax at subtotal level, instead of rounding per line', 'eshopbox' ),
		'id' 		=> 'eshopbox_tax_round_at_subtotal',
		'default'	=> 'no',
		'type' 		=> 'checkbox',
	),

	array(
		'title' 		=> __( 'Additional Tax Classes', 'eshopbox' ),
		'desc' 		=> __( 'List additonal tax classes below (1 per line). This is in addition to the default <code>Standard Rate</code>. Tax classes can be assigned to products.', 'eshopbox' ),
		'id' 		=> 'eshopbox_tax_classes',
		'css' 		=> 'width:100%; height: 65px;',
		'type' 		=> 'textarea',
		'default'	=> sprintf( __( 'Reduced Rate%sZero Rate', 'eshopbox' ), PHP_EOL )
	),

	array(
		'title'   => __( 'Display prices during cart/checkout:', 'eshopbox' ),
		'id'      => 'eshopbox_tax_display_cart',
		'default' => 'excl',
		'type'    => 'select',
		'options' => array(
			'incl'   => __( 'Including tax', 'eshopbox' ),
			'excl'   => __( 'Excluding tax', 'eshopbox' ),
		),
	),

	array( 'type' => 'sectionend', 'id' => 'tax_options' ),

)); // End tax settings

$eshopbox_settings['email'] = apply_filters('eshopbox_email_settings', array(

	array( 'type' => 'sectionend', 'id' => 'email_recipient_options' ),

	array(	'title' => __( 'Email Sender Options', 'eshopbox' ), 'type' => 'title', 'desc' => __( 'The following options affect the sender (email address and name) used in EshopBox emails.', 'eshopbox' ), 'id' => 'email_options' ),

	array(
		'title' => __( '"From" Name', 'eshopbox' ),
		'desc' 		=> '',
		'id' 		=> 'eshopbox_email_from_name',
		'type' 		=> 'text',
		'css' 		=> 'min-width:300px;',
		'default'	=> esc_attr(get_bloginfo('title'))
	),

	array(
		'title' => __( '"From" Email Address', 'eshopbox' ),
		'desc' 		=> '',
		'id' 		=> 'eshopbox_email_from_address',
		'type' 		=> 'email',
		'custom_attributes' => array(
			'multiple' 	=> 'multiple'
		),
		'css' 		=> 'min-width:300px;',
		'default'	=> get_option('admin_email')
	),

	array( 'type' => 'sectionend', 'id' => 'email_options' ),

	array(	'title' => __( 'Email Template', 'eshopbox' ), 'type' => 'title', 'desc' => sprintf(__( 'This section lets you customise the EshopBox emails. <a href="%s" target="_blank">Click here to preview your email template</a>. For more advanced control copy <code>eshopbox/templates/emails/</code> to <code>yourtheme/eshopbox/emails/</code>.', 'eshopbox' ), wp_nonce_url(admin_url('?preview_eshopbox_mail=true'), 'preview-mail')), 'id' => 'email_template_options' ),

	array(
		'title' => __( 'Header Image', 'eshopbox' ),
		'desc' 		=> sprintf(__( 'Enter a URL to an image you want to show in the email\'s header. Upload your image using the <a href="%s">media uploader</a>.', 'eshopbox' ), admin_url('media-new.php')),
		'id' 		=> 'eshopbox_email_header_image',
		'type' 		=> 'text',
		'css' 		=> 'min-width:300px;',
		'default'	=> ''
	),

	array(
		'title' => __( 'Email Footer Text', 'eshopbox' ),
		'desc' 		=> __( 'The text to appear in the footer of EshopBox emails.', 'eshopbox' ),
		'id' 		=> 'eshopbox_email_footer_text',
		'css' 		=> 'width:100%; height: 75px;',
		'type' 		=> 'textarea',
		'default'	=> get_bloginfo('title') . ' - ' . __( 'Powered by EshopBox', 'eshopbox' )
	),

	array(
		'title' => __( 'Base Colour', 'eshopbox' ),
		'desc' 		=> __( 'The base colour for EshopBox email templates. Default <code>#557da1</code>.', 'eshopbox' ),
		'id' 		=> 'eshopbox_email_base_color',
		'type' 		=> 'color',
		'css' 		=> 'width:6em;',
		'default'	=> '#557da1'
	),

	array(
		'title' => __( 'Background Colour', 'eshopbox' ),
		'desc' 		=> __( 'The background colour for EshopBox email templates. Default <code>#f5f5f5</code>.', 'eshopbox' ),
		'id' 		=> 'eshopbox_email_background_color',
		'type' 		=> 'color',
		'css' 		=> 'width:6em;',
		'default'	=> '#f5f5f5'
	),

	array(
		'title' => __( 'Email Body Background Colour', 'eshopbox' ),
		'desc' 		=> __( 'The main body background colour. Default <code>#fdfdfd</code>.', 'eshopbox' ),
		'id' 		=> 'eshopbox_email_body_background_color',
		'type' 		=> 'color',
		'css' 		=> 'width:6em;',
		'default'	=> '#fdfdfd'
	),

	array(
		'title' => __( 'Email Body Text Colour', 'eshopbox' ),
		'desc' 		=> __( 'The main body text colour. Default <code>#505050</code>.', 'eshopbox' ),
		'id' 		=> 'eshopbox_email_text_color',
		'type' 		=> 'color',
		'css' 		=> 'width:6em;',
		'default'	=> '#505050'
	),

	array( 'type' => 'sectionend', 'id' => 'email_template_options' ),

)); // End email settings