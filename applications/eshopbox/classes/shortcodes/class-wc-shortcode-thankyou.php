<?php
/**
 * Thankyou Shortcode
 *
 * The thankyou page displays after successful checkout and can be hooked into by payment gateways.
 *
 * @author 		WooThemes
 * @category 	Shortcodes
 * @package 	EshopBox/Shortcodes/Thankyou
 * @version     2.0.0
 */

class WC_Shortcode_Thankyou {

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

		$eshopbox->show_messages();

		$order = false;

		// Get the order
		$order_id  = apply_filters( 'eshopbox_thankyou_order_id', empty( $_GET['order'] ) ? 0 : absint( $_GET['order'] ) );
		$order_key = apply_filters( 'eshopbox_thankyou_order_key', empty( $_GET['key'] ) ? '' : eshopbox_clean( $_GET['key'] ) );

		if ( $order_id > 0 ) {
			$order = new WC_Order( $order_id );
			if ( $order->order_key != $order_key )
				unset( $order );
		}

		// Empty awaiting payment session
		unset( $eshopbox->session->order_awaiting_payment );

		eshopbox_get_template( 'checkout/thankyou.php', array( 'order' => $order ) );
	}
}