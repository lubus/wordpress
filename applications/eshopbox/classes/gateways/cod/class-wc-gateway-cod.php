<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Cash on Delivery Gateway
 *
 * Provides a Cash on Delivery Payment Gateway.
 *
 * @class 		WC_Gateway_COD
 * @extends		WC_Payment_Gateway
 * @version		2.0.0
 * @package		EshopBox/Classes/Payment
 * @author 		Patrick Garman
 */
class WC_Gateway_COD extends WC_Payment_Gateway {

    /**
     * Constructor for the gateway.
     *
     * @access public
     * @return void
     */
	function __construct() {
		$this->id           = 'cod';
		$this->icon         = apply_filters( 'eshopbox_cod_icon', '' );
		$this->method_title = __( 'Cash on Delivery', 'eshopbox' );
		$this->has_fields   = false;

		// Load the settings
		$this->init_form_fields();
		$this->init_settings();

		// Get settings
		$this->title              = $this->get_option( 'title' );
		$this->description        = $this->get_option( 'description' );
		$this->instructions       = $this->get_option( 'instructions' );
		$this->enable_for_methods = $this->get_option( 'enable_for_methods', array() );

		add_action( 'eshopbox_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'eshopbox_thankyou_cod', array( $this, 'thankyou' ) );
	}


	/**
	 * Admin Panel Options
	 * - Options for bits like 'title' and availability on a country-by-country basis
	 *
	 * @access public
	 * @return void
	 */
	function admin_options() {
		?>
		<h3><?php _e('Cash on Delivery','eshopbox'); ?></h3>
    	<p><?php _e('Have your customers pay with cash (or by other means) upon delivery.', 'eshopbox' ); ?></p>
    	<table class="form-table">
    		<?php $this->generate_settings_html(); ?>
		</table> <?php
    }


    /**
     * Initialise Gateway Settings Form Fields
     *
     * @access public
     * @return void
     */
    function init_form_fields() {
    	global $eshopbox;

    	$shipping_methods = array();

    	if ( is_admin() )
	    	foreach ( $eshopbox->shipping->load_shipping_methods() as $method ) {
		    	$shipping_methods[ $method->id ] = $method->get_title();
	    	}

    	$this->form_fields = array(
			'enabled' => array(
				'title' => __( 'Enable COD', 'eshopbox' ),
				'label' => __( 'Enable Cash on Delivery', 'eshopbox' ),
				'type' => 'checkbox',
				'description' => '',
				'default' => 'no'
			),
			'title' => array(
				'title' => __( 'Title', 'eshopbox' ),
				'type' => 'text',
				'description' => __( 'Payment method title that the customer will see on your website.', 'eshopbox' ),
				'default' => __( 'Cash on Delivery', 'eshopbox' ),
				'desc_tip'      => true,
			),
			'description' => array(
				'title' => __( 'Description', 'eshopbox' ),
				'type' => 'textarea',
				'description' => __( 'Payment method description that the customer will see on your website.', 'eshopbox' ),
				'default' => __( 'Pay with cash upon delivery.', 'eshopbox' ),
			),
			'instructions' => array(
				'title' => __( 'Instructions', 'eshopbox' ),
				'type' => 'textarea',
				'description' => __( 'Instructions that will be added to the thank you page.', 'eshopbox' ),
				'default' => __( 'Pay with cash upon delivery.', 'eshopbox' )
			),
			'enable_for_methods' => array(
				'title' 		=> __( 'Enable for shipping methods', 'eshopbox' ),
				'type' 			=> 'multiselect',
				'class'			=> 'chosen_select',
				'css'			=> 'width: 450px;',
				'default' 		=> '',
				'description' 	=> __( 'If COD is only available for certain methods, set it up here. Leave blank to enable for all methods.', 'eshopbox' ),
				'options'		=> $shipping_methods,
				'desc_tip'      => true,
			)
 	   );
    }


	/**
	 * Check If The Gateway Is Available For Use
	 *
	 * @access public
	 * @return bool
	 */
	function is_available() {
		global $eshopbox;

		if ( ! empty( $this->enable_for_methods ) ) {

			if ( is_page( eshopbox_get_page_id( 'pay' ) ) ) {

				$order_id = (int) $_GET['order_id'];
				$order = new WC_Order( $order_id );

				if ( ! $order->shipping_method )
					return false;

				$chosen_method = $order->shipping_method;

			} elseif ( empty( $eshopbox->session->chosen_shipping_method ) ) {
				return false;
			} else {
				$chosen_method = $eshopbox->session->chosen_shipping_method;
			}

			$found = false;

			foreach ( $this->enable_for_methods as $method_id ) {
				if ( strpos( $chosen_method, $method_id ) === 0 ) {
					$found = true;
					break;
				}
			}

			if ( ! $found )
				return false;
		}

		return parent::is_available();
	}


    /**
     * Process the payment and return the result
     *
     * @access public
     * @param int $order_id
     * @return array
     */
	function process_payment ($order_id) {
		global $eshopbox;

		$order = new WC_Order( $order_id );

		// Mark as on-hold (we're awaiting the cheque)
		$order->update_status('on-hold', __( 'Payment to be made upon delivery.', 'eshopbox' ));

		// Reduce stock levels
		$order->reduce_order_stock();

		// Remove cart
		$eshopbox->cart->empty_cart();

		// Return thankyou redirect
		return array(
			'result' 	=> 'success',
			'redirect'	=> add_query_arg('key', $order->order_key, add_query_arg('order', $order_id, get_permalink(eshopbox_get_page_id('thanks'))))
		);
	}


    /**
     * Output for the order received page.
     *
     * @access public
     * @return void
     */
	function thankyou() {
		//echo $this->instructions != '' ? wpautop( $this->instructions ) : '';
	}

}