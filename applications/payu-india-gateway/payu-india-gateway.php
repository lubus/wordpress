<?php
/*
	Plugin Name: PayU India Gateway
	Description: Provides a <a href="http://www.payu.in/">PayU India</a> gateway for EshopBox.
	Version: 1.0
	Author: <strong>Boxbeat Technologies Pvt Ltd</strong>
	Author URI: http://theboxbeat.com/
	Requires at least: 3.1
	Tested up to: 3.4.1
*/

/**
 * Required functions
 */
if ( ! function_exists( 'woothemes_queue_update' ) )
	require_once( 'woo-includes/woo-functions.php' );

/**
 * Plugin updates
 */
woothemes_queue_update( plugin_basename( __FILE__ ), '2e56bbe1b8877193b170f472b0451fbd', '18707' );

// Init PayU IN Gateway after EshopBox has loaded
add_action( 'plugins_loaded', 'init_payu_in_gateway', 0 );


/**
 * init_payu_in_gateway function.
 *
 * @description Initializes the gateway.
 * @access public
 * @return void
 */
function init_payu_in_gateway() {
	// If the EshopBox payment gateway class is not available, do nothing
	if ( ! class_exists( 'WC_Payment_Gateway' ) ) return;

	// Localization
	load_plugin_textdomain('eshopbox_payu_in', false, dirname( plugin_basename( __FILE__ ) ) . '/languages');

	require_once( plugin_basename( 'classes/payu_in.class.php' ) );

	add_filter( 'eshopbox_payment_gateways', 'add_payu_in_gateway' );

	/**
	* add_gateway()
	*
	* Register the gateway within EshopBox.
	*
	* @since 1.0.0
	*/
	function add_payu_in_gateway($methods) {
		$methods[] = 'WC_Gateway_Payu_In'; return $methods;
	}

}

// Add the Indian Rupee to the currency list
add_filter( 'eshopbox_currencies', 'eshopbox_add_indian_rupee' );

function eshopbox_add_indian_rupee( $currencies ) {
     $currencies['INR'] = __( 'Indian Rupee (INR )', 'eshopbox' );
     return $currencies;
}

add_filter('eshopbox_currency_symbol', 'eshopbox_add_indian_rupee_currency_symbol', 10, 2);

function eshopbox_add_indian_rupee_currency_symbol( $currency_symbol, $currency ) {
     switch( $currency ) {
          case 'INR': $currency_symbol = 'INR '; break;
     }
     return $currency_symbol;
}
