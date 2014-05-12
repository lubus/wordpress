<?php
/**
 * EshopBox Admin Hooks
 *
 * Action/filter hooks used for EshopBox functions.
 *
 * @author 		WooThemes
 * @category 	Admin
 * @package 	EshopBox/Admin
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Events
 *
 * @see eshopbox_delete_post()
 * @see eshopbox_trash_post()
 * @see eshopbox_untrash_post()
 * @see eshopbox_preview_emails()
 * @see eshopbox_prevent_admin_access()
 * @see eshopbox_check_download_folder_protection()
 * @see eshopbox_ms_protect_download_rewite_rules()
 */
add_action('delete_post', 'eshopbox_delete_post');
add_action('wp_trash_post', 'eshopbox_trash_post');
add_action('untrash_post', 'eshopbox_untrash_post');
add_action('admin_init', 'eshopbox_preview_emails');
add_action('admin_init', 'eshopbox_prevent_admin_access');
add_action('eshopbox_settings_saved', 'eshopbox_check_download_folder_protection');
add_filter('mod_rewrite_rules', 'eshopbox_ms_protect_download_rewite_rules');

/**
 * File uploads
 *
 * @see eshopbox_downloads_upload_dir()
 * @see eshopbox_media_upload_downloadable_product()
 */
add_filter('upload_dir', 'eshopbox_downloads_upload_dir');
add_action('media_upload_downloadable_product', 'eshopbox_media_upload_downloadable_product');

/**
 * Shortcode buttons
 *
 * @see eshopbox_add_shortcode_button()
 * @see eshopbox_refresh_mce()
 */
add_action( 'init', 'eshopbox_add_shortcode_button' );
add_filter( 'tiny_mce_version', 'eshopbox_refresh_mce' );

/**
 * Category/term ordering
 *
 * @see eshopbox_create_term()
 * @see eshopbox_delete_term()
 */
add_action( "create_term", 'eshopbox_create_term', 5, 3 );
add_action( "delete_term", 'eshopbox_delete_term', 5 );

/**
 * Bulk editing
 *
 * @see eshopbox_bulk_admin_footer()
 * @see eshopbox_order_bulk_action()
 * @see eshopbox_order_bulk_admin_notices()
 */
add_action( 'admin_footer', 'eshopbox_bulk_admin_footer', 10 );
add_action( 'load-edit.php', 'eshopbox_order_bulk_action' );
add_action( 'admin_notices', 'eshopbox_order_bulk_admin_notices' );

/**
 * Mijireh Gateway
 */
add_action( 'add_meta_boxes', array( 'WC_Gateway_Mijireh', 'add_page_slurp_meta' ) );
add_action( 'wp_ajax_page_slurp', array( 'WC_Gateway_Mijireh', 'page_slurp' ) );