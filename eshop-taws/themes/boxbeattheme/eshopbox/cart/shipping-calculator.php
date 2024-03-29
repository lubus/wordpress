<?php
/**
 * Shipping Calculator
 *
 * @author 		WooThemes
 * @package 	eshopbox/Templates
 * @version     2.0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $eshopbox;

if ( get_option('eshopbox_enable_shipping_calc')=='no' || ! $eshopbox->cart->needs_shipping() )
	return;
?>

<?php do_action( 'eshopbox_before_shipping_calculator' ); ?>

<form class="shipping_calculator" action="<?php echo esc_url( $eshopbox->cart->get_cart_url() ); ?>" method="post">

	<h2><a href="#" class="shipping-calculator-button"><?php _e( 'Calculate Shipping', 'eshopbox' ); ?> <span>&darr;</span></a></h2>

	<section class="shipping-calculator-form">

		<p class="form-row form-row-wide">
			<select name="calc_shipping_country" id="calc_shipping_country" class="country_to_state" rel="calc_shipping_state">
				<option value=""><?php _e( 'Select a country&hellip;', 'eshopbox' ); ?></option>
				<?php
					foreach( $eshopbox->countries->get_allowed_countries() as $key => $value )
						echo '<option value="' . esc_attr( $key ) . '"' . selected( $eshopbox->customer->get_shipping_country(), esc_attr( $key ), false ) . '>' . esc_html( $value ) . '</option>';
				?>
			</select>
		</p>

		<p class="form-row form-row-wide">
			<?php
				$current_cc = $eshopbox->customer->get_shipping_country();
				$current_r  = $eshopbox->customer->get_shipping_state();
				$states     = $eshopbox->countries->get_states( $current_cc );

				// Hidden Input
				if ( is_array( $states ) && empty( $states ) ) {

					?><input type="hidden" name="calc_shipping_state" id="calc_shipping_state" placeholder="<?php _e( 'State / county', 'eshopbox' ); ?>" /><?php

				// Dropdown Input
				} elseif ( is_array( $states ) ) {

					?><span>
						<select name="calc_shipping_state" id="calc_shipping_state" placeholder="<?php _e( 'State / county', 'eshopbox' ); ?>">
							<option value=""><?php _e( 'Select a state&hellip;', 'eshopbox' ); ?></option>
							<?php
								foreach ( $states as $ckey => $cvalue )
									echo '<option value="' . esc_attr( $ckey ) . '" ' . selected( $current_r, $ckey, false ) . '>' . __( esc_html( $cvalue ), 'eshopbox' ) .'</option>';
							?>
						</select>
					</span><?php

				// Standard Input
				} else {

					?><input type="text" class="input-text" value="<?php echo esc_attr( $current_r ); ?>" placeholder="<?php _e( 'State / county', 'eshopbox' ); ?>" name="calc_shipping_state" id="calc_shipping_state" /><?php

				}
			?>
		</p>

		<?php if ( apply_filters( 'eshopbox_shipping_calculator_enable_city', false ) ) : ?>

			<p class="form-row form-row-wide">
				<input type="text" class="input-text" value="<?php echo esc_attr( $eshopbox->customer->get_shipping_city() ); ?>" placeholder="<?php _e( 'City', 'eshopbox' ); ?>" name="calc_shipping_city" id="calc_shipping_city" />
			</p>

		<?php endif; ?>

		<?php if ( apply_filters( 'eshopbox_shipping_calculator_enable_postcode', true ) ) : ?>

			<p class="form-row form-row-wide">
				<input type="text" class="input-text" value="<?php echo esc_attr( $eshopbox->customer->get_shipping_postcode() ); ?>" placeholder="<?php _e( 'Postcode / Zip', 'eshopbox' ); ?>" name="calc_shipping_postcode" id="calc_shipping_postcode" />
			</p>

		<?php endif; ?>

		<p><button type="submit" name="calc_shipping" value="1" class="button"><?php _e( 'Update Totals', 'eshopbox' ); ?></button></p>

		<?php $eshopbox->nonce_field('cart') ?>
	</section>
</form>

<?php do_action( 'eshopbox_after_shipping_calculator' ); ?>