<?php
/**
 * EshopBox Uninstall
 *
 * Uninstalling EshopBox deletes user roles, options, tables, and pages.
 *
 * @author 		WooThemes
 * @category 	Core
 * @package 	EshopBox/Uninstaller
 * @version     1.6.4
 */
if( !defined('WP_UNINSTALL_PLUGIN') ) exit();

global $wpdb, $wp_roles;

// Roles + caps
if ( ! function_exists( 'eshopbox_remove_roles' ) )
	include_once( 'eshopbox-core-functions.php' );

if ( function_exists( 'eshopbox_remove_roles' ) )
	eshopbox_remove_roles();

// Pages
wp_delete_post( get_option('eshopbox_shop_page_id'), true );
wp_delete_post( get_option('eshopbox_cart_page_id'), true );
wp_delete_post( get_option('eshopbox_checkout_page_id'), true );
wp_delete_post( get_option('eshopbox_myaccount_page_id'), true );
wp_delete_post( get_option('eshopbox_edit_address_page_id'), true );
wp_delete_post( get_option('eshopbox_view_order_page_id'), true );
wp_delete_post( get_option('eshopbox_change_password_page_id'), true );
wp_delete_post( get_option('eshopbox_pay_page_id'), true );
wp_delete_post( get_option('eshopbox_thanks_page_id'), true );

// mijireh checkout page
if ( $mijireh_page = get_page_by_path( 'mijireh-secure-checkout' ) )
	wp_delete_post( $mijireh_page->ID, true );

// Tables
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "eshopbox_attribute_taxonomies" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "eshopbox_downloadable_product_permissions" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "eshopbox_termmeta" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->base_prefix . "shareyourcart_tokens" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->base_prefix . "shareyourcart_coupons" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "eshopbox_tax_rates" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "eshopbox_tax_rate_locations" );

// Delete options
$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'eshopbox_%';");