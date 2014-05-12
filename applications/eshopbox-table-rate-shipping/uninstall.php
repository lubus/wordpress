<?php
/**
 * Table rate Shipping Uninstall
 * 
 * Deletes the rates table
 */
if( ! defined('WP_UNINSTALL_PLUGIN') ) exit();

global $wpdb;
	
// Tables
$wpdb->query("DROP TABLE IF EXISTS ".$wpdb->prefix."eshopbox_shipping_table_rates");