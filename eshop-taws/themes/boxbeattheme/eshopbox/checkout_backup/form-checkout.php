<?php
/**
 * Checkout Form
 *
 * @author 		WooThemes
 * @package 	EshopBox/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $eshopbox;

$eshopbox->show_messages();

do_action( 'eshopbox_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
	echo apply_filters( 'eshopbox_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'eshopbox' ) );
	return;
}

// filter hook for include new pages inside the payment method
$get_checkout_url = apply_filters( 'eshopbox_get_checkout_url', $eshopbox->cart->get_checkout_url() ); ?>

<form name="checkout" method="post" class="checkout" action="<?php echo esc_url( $get_checkout_url ); ?>">

	<?php if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>

		<?php do_action( 'eshopbox_checkout_before_customer_details' ); ?>

		<div class="col2-set" id="customer_details">

			<div class="col-1">

				<?php do_action( 'eshopbox_checkout_billing' ); ?>

			</div>

			<div class="col-2">

				<?php do_action( 'eshopbox_checkout_shipping' ); ?>

			</div>

		</div>

		<?php do_action( 'eshopbox_checkout_after_customer_details' ); ?>

		<h3 id="order_review_heading"><?php _e( 'Your order', 'eshopbox' ); ?></h3>

	<?php endif; ?>

	<?php do_action( 'eshopbox_checkout_order_review' ); ?>

</form>

<?php do_action( 'eshopbox_after_checkout_form', $checkout ); ?>
