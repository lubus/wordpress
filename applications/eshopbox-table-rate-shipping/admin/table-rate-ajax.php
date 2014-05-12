<?php

/**
 * Delete rate via ajax function
 */
add_action('wp_ajax_eshopbox_table_rate_delete', 'eshopbox_table_rate_delete');

function eshopbox_table_rate_delete() {
	
	check_ajax_referer( 'delete-rate', 'security' );
	
	if ( is_array( $_POST['rate_id'] ) ) {
		$rate_ids = array_map( 'intval', $_POST['rate_id'] );
	} else {
		$rate_ids = array( intval( $_POST['rate_id'] ) );
	}
	
	if ( ! empty( $rate_ids ) ) {
		
		global $wpdb;
		
		$wpdb->query( "DELETE FROM {$wpdb->prefix}eshopbox_shipping_table_rates WHERE rate_id IN (" . implode( ',', $rate_ids ) . ")" );
		
	}
	
	die();
}
