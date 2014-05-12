<?php
/**
 * Checkout billing information form
 *
 * @author 		WooThemes
 * @package 	EshopBox/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $eshopbox;
?>

<?php if ( $eshopbox->cart->ship_to_billing_address_only() && $eshopbox->cart->needs_shipping() ) : ?>

	<h3><?php _e( 'Billing &amp; Shipping', 'eshopbox' ); ?></h3>

<?php else : ?>

	<h3><?php _e( 'Billing Address', 'eshopbox' ); ?></h3>

<?php endif; ?>

<?php do_action('eshopbox_before_checkout_billing_form', $checkout ); ?>

<?php foreach ($checkout->checkout_fields['billing'] as $key => $field) : ?>

	<?php eshopbox_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

<?php endforeach; ?>

<?php do_action('eshopbox_after_checkout_billing_form', $checkout ); ?>



<?php if ( ! is_user_logged_in() && $checkout->enable_signup ) : ?>

	<?php if ( $checkout->enable_guest_checkout ) : ?>

		<p class="form-row form-row-wide">
			<input class="input-checkbox" id="createaccount" <?php checked($checkout->get_value('createaccount'), true) ?> type="checkbox" name="createaccount" value="1" /> <label for="createaccount" class="checkbox"><?php _e( 'Create an account?', 'eshopbox' ); ?></label>
		</p>

	<?php endif; ?>

	<?php do_action( 'eshopbox_before_checkout_registration_form', $checkout ); ?>

	<div class="create-account">

		<p><?php _e( 'Create an account by entering the information below. If you are a returning customer please login at the top of the page.', 'eshopbox' ); ?></p>

		<?php foreach ($checkout->checkout_fields['account'] as $key => $field) : ?>

			<?php eshopbox_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

		<?php endforeach; ?>

		<div class="clear"></div>

	</div>

	<?php do_action( 'eshopbox_after_checkout_registration_form', $checkout ); ?>

<?php endif; ?>