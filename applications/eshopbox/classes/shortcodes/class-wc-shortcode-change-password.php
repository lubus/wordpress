<?php
/**
 * Change Password Shortcode
 *
 * @author 		WooThemes
 * @category 	Shortcodes
 * @package 	EshopBox/Shortcodes/Change_Password
 * @version     2.0.0
 */
class WC_Shortcode_Change_Password {

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

		if ( ! is_user_logged_in() ) return;

		eshopbox_get_template( 'myaccount/form-change-password.php' );
	}
}