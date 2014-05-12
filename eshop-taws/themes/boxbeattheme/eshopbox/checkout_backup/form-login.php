<?php
/**
 * Checkout login form
 *
 * @author 		WooThemes
 * @package 	EshopBox/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( is_user_logged_in() ) return;

$info_message = apply_filters( 'eshopbox_checkout_login_message', __( 'Returning customer?', 'eshopbox' ) );
?>

<p class="eshopbox-info"><?php echo esc_html( $info_message ); ?> <a href="#" class="showlogin"><?php _e( 'Click here to login', 'eshopbox' ); ?></a></p>

<?php
	eshopbox_login_form(
		array(
			'message'  => __( 'If you have shopped with us before, please enter your details in the boxes below. If you are a new customer please proceed to the Billing &amp; Shipping section.', 'eshopbox' ),
			'redirect' => get_permalink( eshopbox_get_page_id( 'checkout') ),
			'hidden'   => true
		)
	);
?>