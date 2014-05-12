<?php
/**
 * Cart Shortcode
 *
 * Used on the cart page, the cart shortcode displays the cart contents and interface for coupon codes and other cart bits and pieces.
 *
 * @author 		WooThemes
 * @category 	Shortcodes
 * @package 	EshopBox/Shortcodes/Cart
 * @version     2.0.0
 */
class WC_Shortcode_Cart {

	/**
	 * Output the cart shortcode.
	 *
	 * @access public
	 * @param array $atts
	 * @return void
	 */
	public static function output( $atts ) {
		global $eshopbox;

		if ( ! defined( 'WOOCOMMERCE_CART' ) ) define( 'WOOCOMMERCE_CART', true );

		// Add Discount
		if ( ! empty( $_POST['apply_coupon'] ) ) {

			if ( ! empty( $_POST['coupon_code'] ) ) {
				$eshopbox->cart->add_discount( sanitize_text_field( $_POST['coupon_code'] ) );
			} else {
				$eshopbox->add_error( WC_Coupon::get_generic_coupon_error( WC_Coupon::E_WC_COUPON_PLEASE_ENTER ) );
			}

		// Remove Coupon Codes
		} elseif ( isset( $_GET['remove_discounts'] ) ) {

			$eshopbox->cart->remove_coupons( $_GET['remove_discounts'] );

		// Update Shipping
		} elseif ( ! empty( $_POST['calc_shipping'] ) && $eshopbox->verify_nonce('cart') ) {

			$validation = $eshopbox->validation();

			$eshopbox->shipping->reset_shipping();
			$eshopbox->customer->calculated_shipping( true );
			$country 	= eshopbox_clean( $_POST['calc_shipping_country'] );
			$state 		= eshopbox_clean( $_POST['calc_shipping_state'] );
			$postcode   = apply_filters( 'eshopbox_shipping_calculator_enable_postcode', true ) ? eshopbox_clean( $_POST['calc_shipping_postcode'] ) : '';
			$city       = apply_filters( 'eshopbox_shipping_calculator_enable_city', false ) ? eshopbox_clean( $_POST['calc_shipping_city'] ) : '';

			if ( $postcode && ! $validation->is_postcode( $postcode, $country ) ) {
				$eshopbox->add_error( __( 'Please enter a valid postcode/ZIP.', 'eshopbox' ) );
				$postcode = '';
			} elseif ( $postcode ) {
				$postcode = $validation->format_postcode( $postcode, $country );
			}

			if ( $country ) {

				// Update customer location
				$eshopbox->customer->set_location( $country, $state, $postcode, $city );
				$eshopbox->customer->set_shipping_location( $country, $state, $postcode, $city );
				$eshopbox->add_message(  __( 'Shipping costs updated.', 'eshopbox' ) );

			} else {

				$eshopbox->customer->set_to_base();
				$eshopbox->customer->set_shipping_to_base();
				$eshopbox->add_message(  __( 'Shipping costs updated.', 'eshopbox' ) );

			}

			do_action( 'eshopbox_calculated_shipping' );
		}

		// Check cart items are valid
		do_action('eshopbox_check_cart_items');

		// Calc totals
		$eshopbox->cart->calculate_totals();

		if ( sizeof( $eshopbox->cart->get_cart() ) == 0 )
			eshopbox_get_template( 'cart/cart-empty.php' );
		else
			eshopbox_get_template( 'cart/cart.php' );

	}
}