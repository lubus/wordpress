<?php
/**
 * Functions used for the showing help/links to EshopBox resources in admin
 *
 * @author 		WooThemes
 * @category 	Admin
 * @package 	EshopBox/Admin
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Help Tab Content
 *
 * Shows some text about EshopBox and links to docs.
 *
 * @access public
 * @return void
 */
function eshopbox_admin_help_tab_content() {
	$screen = get_current_screen();

	$screen->add_help_tab( array(
	    'id'	=> 'eshopbox_overview_tab',
	    'title'	=> __( 'Overview', 'eshopbox' ),
	    'content'	=>

	    	'<p>' . sprintf(__( 'Thank you for using EshopBox :) Should you need help using or extending EshopBox please <a href="%s">read the documentation</a>. For further assistance you can use the <a href="%s">community forum</a> or if you have access, <a href="%s">our support desk</a>.', 'eshopbox' ), 'http://docs.woothemes.com/documentation/plugins/eshopbox/', 'http://boxbeat.org/support/plugin/eshopbox', 'http://support.woothemes.com') . '</p>' .

	    	'<p>' . __( 'If you are having problems, or to assist us with support, please check the status page to identify any problems with your configuration:', 'eshopbox' ) . '</p>' .

	    	'<p><a href="' . admin_url('admin.php?page=eshopbox_status') . '" class="button">' . __( 'System Status', 'eshopbox' ) . '</a></p>' .

	    	'<p>' . sprintf(__( 'If you come across a bug, or wish to contribute to the project you can also <a href="%s">get involved on GitHub</a>.', 'eshopbox' ), 'https://github.com/woothemes/eshopbox') . '</p>'

	) );

	$screen->add_help_tab( array(
	    'id'	=> 'eshopbox_settings_tab',
	    'title'	=> __( 'Settings', 'eshopbox' ),
	    'content'	=>
	    	'<p>' . __( 'Here you can set up your store and customise it to fit your needs. The sections available from the settings page include:', 'eshopbox' ) . '</p>' .
	    	'<p><strong>' . __( 'General', 'eshopbox' ) . '</strong> - ' . __( 'General settings such as your shop base, currency, and script/styling options which affect features used in your store.', 'eshopbox' ) . '</p>' .
	    	'<p><strong>' . __( 'Pages', 'eshopbox' ) . '</strong> - ' . __( 'This is where important store page are defined. You can also set up other pages (such as a Terms page) here.', 'eshopbox' ) . '</p>' .
	    	'<p><strong>' . __( 'Catalog', 'eshopbox' ) . '</strong> - ' . __( 'Options for how things like price, images and weights appear in your product catalog.', 'eshopbox' ) . '</p>' .
	    	'<p><strong>' . __( 'Inventory', 'eshopbox' ) . '</strong> - ' . __( 'Options concerning stock and stock notices.', 'eshopbox' ) . '</p>' .
	    	'<p><strong>' . __( 'Tax', 'eshopbox' ) . '</strong> - ' . __( 'Options concerning tax, including international and local tax rates.', 'eshopbox' ) . '</p>' .
	    	'<p><strong>' . __( 'Shipping', 'eshopbox' ) . '</strong> - ' . __( 'This is where shipping options are defined, and shipping methods are set up.', 'eshopbox' ) . '</p>' .
	    	'<p><strong>' . __( 'Payment Methods', 'eshopbox' ) . '</strong> - ' . __( 'This is where payment gateway options are defined, and individual payment gateways are set up.', 'eshopbox' ) . '</p>' .
	    	'<p><strong>' . __( 'Emails', 'eshopbox' ) . '</strong> - ' . __( 'Here you can customise the way EshopBox emails appear.', 'eshopbox' ) . '</p>' .
	    	'<p><strong>' . __( 'Integration', 'eshopbox' ) . '</strong> - ' . __( 'The integration section contains options for third party services which integrate with EshopBox.', 'eshopbox' ) . '</p>'
	) );

	$screen->add_help_tab( array(
	    'id'	=> 'eshopbox_overview_tab_2',
	    'title'	=> __( 'Reports', 'eshopbox' ),
	    'content'	=>
				'<p>' . __( 'The reports section can be accessed from the left-hand navigation menu. Here you can generate reports for sales and customers.', 'eshopbox' ) . '</p>' .
				'<p><strong>' . __( 'Sales', 'eshopbox' ) . '</strong> - ' . __( 'Reports for sales based on date, top sellers and top earners.', 'eshopbox' ) . '</p>' .
				'<p><strong>' . __( 'Coupons', 'eshopbox' ) . '</strong> - ' . __( 'Coupon usage reports.', 'eshopbox' ) . '</p>' .
				'<p><strong>' . __( 'Customers', 'eshopbox' ) . '</strong> - ' . __( 'Customer reports, such as signups per day.', 'eshopbox' ) . '</p>' .
				'<p><strong>' . __( 'Stock', 'eshopbox' ) . '</strong> - ' . __( 'Stock reports for low stock and out of stock items.', 'eshopbox' ) . '</p>'
	) );

	$screen->add_help_tab( array(
	     'id'	=> 'eshopbox_overview_tab_3',
	     'title'	=> __( 'Orders', 'eshopbox' ),
	     'content'	=>
				'<p>' . __( 'The orders section can be accessed from the left-hand navigation menu. Here you can view and manage customer orders.', 'eshopbox' ) . '</p>' .
				'<p>' . __( 'Orders can also be added from this section if you want to set them up for a customer manually.', 'eshopbox' ) . '</p>'
	) );

	$screen->add_help_tab( array(
	     'id'	=> 'eshopbox_overview_tab_4',
	     'title'	=> __( 'Coupons', 'eshopbox' ),
	     'content'	=>
				'<p>' . __( 'Coupons can be managed from this section. Once added, customers will be able to enter coupon codes on the cart/checkout page. If a customer uses a coupon code they will be viewable when viewing orders.', 'eshopbox' ) . '</p>'
	) );

	$screen->set_help_sidebar(
		'<p><strong>' . __( 'For more information:', 'eshopbox' ) . '</strong></p>' .
		'<p><a href="http://www.woothemes.com/eshopbox/" target="_blank">' . __( 'EshopBox', 'eshopbox' ) . '</a></p>' .
		'<p><a href="http://boxbeat.org/extend/plugins/eshopbox/" target="_blank">' . __( 'Project on BoxBeat.org', 'eshopbox' ) . '</a></p>' .
		'<p><a href="https://github.com/woothemes/eshopbox" target="_blank">' . __( 'Project on Github', 'eshopbox' ) . '</a></p>' .
		'<p><a href="http://docs.woothemes.com/documentation/plugins/eshopbox/" target="_blank">' . __( 'EshopBox Docs', 'eshopbox' ) . '</a></p>' .
		'<p><a href="http://www.woothemes.com/product-category/eshopbox-extensions/" target="_blank">' . __( 'Official Extensions', 'eshopbox' ) . '</a></p>' .
		'<p><a href="http://www.woothemes.com/product-category/themes/eshopbox/" target="_blank">' . __( 'Official Themes', 'eshopbox' ) . '</a></p>'
	);
}