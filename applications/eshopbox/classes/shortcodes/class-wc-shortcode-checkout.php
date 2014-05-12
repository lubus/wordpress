<?php
/**
 * Checkout Shortcode
 *
 * Used on the checkout page, the checkout shortcode displays the checkout process.
 *
 * @author 		WooThemes
 * @category 	Shortcodes
 * @package 	EshopBox/Shortcodes/Checkout
 * @version     2.0.0
 */

class WC_Shortcode_Checkout {

	/**
	 * Get the shortcode content.
	 *
	 * @access public
	 * @param array $atts
	 * @return string
	 */
	public static function get( $atts ) {
		global $eshopbox;
		return $eshopbox->shortcode_wrapper( array( __CLASS__, 'output' ), $atts );
	}

	/**
	 * Output the shortcode.
	 *
	 * @access public
	 * @param array $atts
	 * @return void
	 */
	public static function output( $atts ) {
		global $eshopbox;

		// Check checkout is configured correctly
		if ( current_user_can( 'manage_options' ) ) {
			$pay_page_id    = eshopbox_get_page_id( 'pay' );
			$thanks_page_id = eshopbox_get_page_id( 'thanks' );
			$pay_page       = get_permalink( $pay_page_id );
			$thanks_page    = get_permalink( $thanks_page_id );

			if ( ! $pay_page_id || ! $thanks_page_id || empty( $pay_page ) || empty( $thanks_page ) )
				$eshopbox->add_error( sprintf( __( 'EshopBox Config Error: The checkout thanks/pay pages are missing - these pages are required for the checkout to function correctly. Please configure the pages <a href="%s">here</a>.', 'eshopbox' ), admin_url( 'admin.php?page=eshopbox_settings&tab=pages' ) ) );
		}

		// Show non-cart errors
		$eshopbox->show_messages();

		// Check cart has contents
		if ( sizeof( $eshopbox->cart->get_cart() ) == 0 )
			return;

		// Calc totals
		$eshopbox->cart->calculate_totals();

		// Check cart contents for errors
		do_action('eshopbox_check_cart_items');

		// Get checkout object
		$checkout = $eshopbox->checkout();

		if ( empty( $_POST ) && $eshopbox->error_count() > 0 ) {

			eshopbox_get_template( 'checkout/cart-errors.php', array( 'checkout' => $checkout ) );

		} else {

			$non_js_checkout = ! empty( $_POST['eshopbox_checkout_update_totals'] ) ? true : false;

			if ( $eshopbox->error_count() == 0 && $non_js_checkout )
				$eshopbox->add_message( __( 'The order totals have been updated. Please confirm your order by pressing the Place Order button at the bottom of the page.', 'eshopbox' ) );

			eshopbox_get_template( 'checkout/form-checkout.php', array( 'checkout' => $checkout ) );

		}
	}
}