<?php
/*
Plugin Name: Contact Us Form
Description: Just another contact form plugin. Simple but flexible.
Author: <strong>Boxbeat Technologies Pvt Ltd</strong>
Version:1.0
Author URI: http://theboxbeat.com/
*/

define( 'WPCF7_VERSION', '3.4.1' );
define( 'WPCF7_REQUIRED_WP_VERSION', '3.5' );

if ( ! defined( 'WPCF7_PLUGIN_BASENAME' ) )
	define( 'WPCF7_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

if ( ! defined( 'WPCF7_PLUGIN_NAME' ) )
	define( 'WPCF7_PLUGIN_NAME', trim( dirname( WPCF7_PLUGIN_BASENAME ), '/' ) );

if ( ! defined( 'WPCF7_PLUGIN_DIR' ) )
	define( 'WPCF7_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );

if ( ! defined( 'WPCF7_PLUGIN_URL' ) )
	define( 'WPCF7_PLUGIN_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) );

if ( ! defined( 'WPCF7_PLUGIN_MODULES_DIR' ) )
	define( 'WPCF7_PLUGIN_MODULES_DIR', WPCF7_PLUGIN_DIR . '/modules' );

if ( ! defined( 'WPCF7_LOAD_JS' ) )
	define( 'WPCF7_LOAD_JS', true );

if ( ! defined( 'WPCF7_LOAD_CSS' ) )
	define( 'WPCF7_LOAD_CSS', true );

if ( ! defined( 'WPCF7_AUTOP' ) )
	define( 'WPCF7_AUTOP', true );

if ( ! defined( 'WPCF7_USE_PIPE' ) )
	define( 'WPCF7_USE_PIPE', true );

/* If you or your client hate to see about donation, set this value false. */
if ( ! defined( 'WPCF7_SHOW_DONATION_LINK' ) )
	define( 'WPCF7_SHOW_DONATION_LINK', true );

if ( ! defined( 'WPCF7_ADMIN_READ_CAPABILITY' ) )
	define( 'WPCF7_ADMIN_READ_CAPABILITY', 'edit_posts' );

if ( ! defined( 'WPCF7_ADMIN_READ_WRITE_CAPABILITY' ) )
	define( 'WPCF7_ADMIN_READ_WRITE_CAPABILITY', 'publish_pages' );

if ( ! defined( 'WPCF7_VERIFY_NONCE' ) )
	define( 'WPCF7_VERIFY_NONCE', true );

require_once WPCF7_PLUGIN_DIR . '/settings.php';

?>