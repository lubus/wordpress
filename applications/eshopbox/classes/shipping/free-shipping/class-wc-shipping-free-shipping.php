<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Free Shipping Method
 *
 * A simple shipping method for free shipping
 *
 * @class 		WC_Shipping_Free_Shipping
 * @version		2.0.0
 * @package		EshopBox/Classes/Shipping
 * @author 		WooThemes
 */
class WC_Shipping_Free_Shipping extends WC_Shipping_Method {

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	function __construct() {
        $this->id 			= 'free_shipping';
        $this->method_title = __( 'Free Shipping', 'eshopbox' );
		$this->init();
    }

    /**
     * init function.
     *
     * @access public
     * @return void
     */
    function init() {

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables
        $this->enabled		= $this->get_option( 'enabled' );
		$this->title 		= $this->get_option( 'title' );
		$this->min_amount 	= $this->get_option( 'min_amount', 0 );
		$this->availability = $this->get_option( 'availability' );
		$this->countries 	= $this->get_option( 'countries' );
		$this->requires		= $this->get_option( 'requires' );

		// Actions
		add_action( 'eshopbox_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
    }


    /**
     * Initialise Gateway Settings Form Fields
     *
     * @access public
     * @return void
     */
    function init_form_fields() {
    	global $eshopbox;

    	// Backwards compat
    	if ( $this->get_option( 'requires_coupon' ) && $this->min_amount )
			$default_requires = 'either';
		elseif ( $this->get_option( 'requires_coupon' ) )
			$default_requires = 'coupon';
		elseif ( $this->min_amount )
			$default_requires = 'min_amount';
		else
			$default_requires = '';

    	$this->form_fields = array(
			'enabled' => array(
							'title' 		=> __( 'Enable/Disable', 'eshopbox' ),
							'type' 			=> 'checkbox',
							'label' 		=> __( 'Enable Free Shipping', 'eshopbox' ),
							'default' 		=> 'yes'
						),
			'title' => array(
							'title' 		=> __( 'Method Title', 'eshopbox' ),
							'type' 			=> 'text',
							'description' 	=> __( 'This controls the title which the user sees during checkout.', 'eshopbox' ),
							'default'		=> __( 'Free Shipping', 'eshopbox' ),
							'desc_tip'      => true,
						),
			'availability' => array(
							'title' 		=> __( 'Method availability', 'eshopbox' ),
							'type' 			=> 'select',
							'default' 		=> 'all',
							'class'			=> 'availability',
							'options'		=> array(
								'all' 		=> __( 'All allowed countries', 'eshopbox' ),
								'specific' 	=> __( 'Specific Countries', 'eshopbox' )
							)
						),
			'countries' => array(
							'title' 		=> __( 'Specific Countries', 'eshopbox' ),
							'type' 			=> 'multiselect',
							'class'			=> 'chosen_select',
							'css'			=> 'width: 450px;',
							'default' 		=> '',
							'options'		=> $eshopbox->countries->countries
						),
			'requires' => array(
							'title' 		=> __( 'Free Shipping Requires...', 'eshopbox' ),
							'type' 			=> 'select',
							'default' 		=> $default_requires,
							'options'		=> array(
								'' 				=> __( 'N/A', 'eshopbox' ),
								'coupon'		=> __( 'A valid free shipping coupon', 'eshopbox' ),
								'min_amount' 	=> __( 'A minimum order amount (defined below)', 'eshopbox' ),
								'either' 		=> __( 'A minimum order amount OR a coupon', 'eshopbox' ),
								'both' 			=> __( 'A minimum order amount AND a coupon', 'eshopbox' ),
							)
						),
			'min_amount' => array(
							'title' 		=> __( 'Minimum Order Amount', 'eshopbox' ),
							'type' 			=> 'number',
							'custom_attributes' => array(
								'step'	=> 'any',
								'min'	=> '0'
							),
							'description' 	=> __( 'Users will need to spend this amount to get free shipping (if enabled above).', 'eshopbox' ),
							'default' 		=> '0',
							'desc_tip'      => true,
							'placeholder'	=> '0.00'
						)
			);

    }


	/**
	 * Admin Panel Options
	 * - Options for bits like 'title' and availability on a country-by-country basis
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_options() {

    	?>
    	<h3><?php _e( 'Free Shipping', 'eshopbox' ); ?></h3>
    	<table class="form-table">
    	<?php
    		// Generate the HTML For the settings form.
    		$this->generate_settings_html();
    	?>
		</table><!--/.form-table-->
    	<?php
    }


    /**
     * is_available function.
     *
     * @access public
     * @param mixed $package
     * @return bool
     */
    function is_available( $package ) {
    	global $eshopbox;

    	if ( $this->enabled == "no" ) return false;

		$ship_to_countries = '';

		if ( $this->availability == 'specific' ) {
			$ship_to_countries = $this->countries;
		} else {
			if ( get_option('eshopbox_allowed_countries') == 'specific' )
				$ship_to_countries = get_option('eshopbox_specific_allowed_countries');
		}

		if ( is_array( $ship_to_countries ) )
			if ( ! in_array( $package['destination']['country'], $ship_to_countries ) )
				return false;

		// Enabled logic
		$is_available 		= false;
		$has_coupon 		= false;
		$has_met_min_amount = false;

		if ( in_array( $this->requires, array( 'coupon', 'either', 'both' ) ) ) {

			if ( $eshopbox->cart->applied_coupons ) {
				foreach ($eshopbox->cart->applied_coupons as $code) {
					$coupon = new WC_Coupon( $code );

					if ( $coupon->is_valid() && $coupon->enable_free_shipping() )
						$has_coupon = true;
				}
			}
		}

		if ( in_array( $this->requires, array( 'min_amount', 'either', 'both' ) ) && isset( $eshopbox->cart->cart_contents_total ) ) {

			if ( $eshopbox->cart->prices_include_tax )
				$total = $eshopbox->cart->cart_contents_total + array_sum( $eshopbox->cart->taxes );
			else
				$total = $eshopbox->cart->cart_contents_total;

			if ( $total >= $this->min_amount )
				$has_met_min_amount = true;
		}

		switch ( $this->requires ) {
			case 'min_amount' :
				if ( $has_met_min_amount ) $is_available = true;
			break;
			case 'coupon' :
				if ( $has_coupon ) $is_available = true;
			break;
			case 'both' :
				if ( $has_met_min_amount && $has_coupon ) $is_available = true;
			break;
			case 'either' :
				if ( $has_met_min_amount || $has_coupon ) $is_available = true;
			break;
			default :
				$is_available = true;
			break;
		}

		return apply_filters( 'eshopbox_shipping_' . $this->id . '_is_available', $is_available );
    }


    /**
     * calculate_shipping function.
     *
     * @access public
     * @return array
     */
    function calculate_shipping() {
    	$args = array(
    		'id' 	=> $this->id,
    		'label' => $this->title,
    		'cost' 	=> 0,
    		'taxes' => false
    	);
    	$this->add_rate( $args );
    }

}