<?php
/**
 * Checkout Form
 *
 * @author 		WooThemes
 * @package 	eshopbox/Templates
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
  <div class="securecheckout_block">
        	<div class="pull-left">Secure Checkout </div>
				<div class="button_right">
				<input type="submit" class="button alt place_order_checkout" name="eshopbox_checkout_place_order" id="place_order" value="<?php echo apply_filters('eshopbox_order_button_text', __( 'Place order now', 'eshopbox' )); ?>" />
				</div>           
        </div>
	<?php if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>
		<?php do_action( 'eshopbox_checkout_before_customer_details' ); ?>
		<div class="col2-set" id="customer_details">
        	<div class="leftblock">
			<div class="col-1">
				<?php do_action( 'eshopbox_checkout_billing' ); ?>
			</div>
			<div class="col-2">
				<?php do_action( 'eshopbox_checkout_shipping' ); ?>
			</div>         
		</div>
		<div class="checkout_seperator"></div>
		<div class="rightblock">
        <?php do_action( 'eshopbox_checkout_after_customer_details' ); ?>
		<div class="orderreviewblock">
			<div class="checkout_editblock">
        		<h3 id="order_review_heading"><?php _e( 'ORDER SUMMARY', 'eshopbox' ); ?></h3>
                <a href="/cart" charset="editlink">edit</a>
			</div>
	<?php endif; ?>
    <?php do_action( 'eshopbox_checkout_order_review' ); ?>
        </div>
        </div>
		</div>	
</form>
<?php do_action( 'eshopbox_after_checkout_form', $checkout ); ?>