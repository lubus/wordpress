<?php
/**
 * Cart totals
 *
 * @author 		WooThemes
 * @package 	EshopBox/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $eshopbox;

$available_methods = $eshopbox->shipping->get_available_shipping_methods();
?>
<div class="cart_totals <?php if ( $eshopbox->customer->has_calculated_shipping() ) echo 'calculated_shipping'; ?>">

	<?php do_action( 'eshopbox_before_cart_totals' ); ?>

	<?php if ( ! $eshopbox->shipping->enabled || $available_methods || ! $eshopbox->customer->get_shipping_country() || ! $eshopbox->customer->has_calculated_shipping() ) : ?>

		<h2><?php _e( 'Cart Totals', 'eshopbox' ); ?></h2>

		<table cellspacing="0">
			<tbody>

				<tr class="cart-subtotal">
					<th><strong><?php _e( 'Cart Subtotal', 'eshopbox' ); ?></strong></th>
					<td><strong><?php echo $eshopbox->cart->get_cart_subtotal(); ?></strong></td>
				</tr>

				<?php if ( $eshopbox->cart->get_discounts_before_tax() ) : ?>

					<tr class="discount">
						<th><?php _e( 'Cart Discount', 'eshopbox' ); ?> <a href="<?php echo add_query_arg( 'remove_discounts', '1', $eshopbox->cart->get_cart_url() ) ?>"><?php _e( '[Remove]', 'eshopbox' ); ?></a></th>
						<td>-<?php echo $eshopbox->cart->get_discounts_before_tax(); ?></td>
					</tr>

				<?php endif; ?>

				<?php if ( $eshopbox->cart->needs_shipping() && $eshopbox->cart->show_shipping() && ( $available_methods || get_option( 'eshopbox_enable_shipping_calc' ) == 'yes' ) ) : ?>

					<?php do_action( 'eshopbox_cart_totals_before_shipping' ); ?>

					<tr class="shipping">
						<th><?php _e( 'Shipping', 'eshopbox' ); ?></th>
						<td><?php eshopbox_get_template( 'cart/shipping-methods.php', array( 'available_methods' => $available_methods ) ); ?></td>
					</tr>

					<?php do_action( 'eshopbox_cart_totals_after_shipping' ); ?>

				<?php endif ?>

				<?php foreach ( $eshopbox->cart->get_fees() as $fee ) : ?>

					<tr class="fee fee-<?php echo $fee->id ?>">
						<th><?php echo $fee->name ?></th>
						<td><?php
							if ( $eshopbox->cart->tax_display_cart == 'excl' )
								echo eshopbox_price( $fee->amount );
							else
								echo eshopbox_price( $fee->amount + $fee->tax );
						?></td>
					</tr>

				<?php endforeach; ?>

				<?php
					// Show the tax row if showing prices exclusive of tax only
					if ( $eshopbox->cart->tax_display_cart == 'excl' ) {
						foreach ( $eshopbox->cart->get_tax_totals() as $code => $tax ) {
							echo '<tr class="tax-rate tax-rate-' . $code . '">
								<th>' . $tax->label . '</th>
								<td>' . $tax->formatted_amount . '</td>
							</tr>';
						}
					}
				?>

				<?php if ( $eshopbox->cart->get_discounts_after_tax() ) : ?>

					<tr class="discount">
						<th><?php _e( 'Order Discount', 'eshopbox' ); ?> <a href="<?php echo add_query_arg( 'remove_discounts', '2', $eshopbox->cart->get_cart_url() ) ?>"><?php _e( '[Remove]', 'eshopbox' ); ?></a></th>
						<td>-<?php echo $eshopbox->cart->get_discounts_after_tax(); ?></td>
					</tr>

				<?php endif; ?>

				<?php do_action( 'eshopbox_cart_totals_before_order_total' ); ?>

				<tr class="total">
					<th><strong><?php _e( 'Order Total', 'eshopbox' ); ?></strong></th>
					<td>
						<strong><?php echo $eshopbox->cart->get_total(); ?></strong>
						<?php
							// If prices are tax inclusive, show taxes here
							if (  $eshopbox->cart->tax_display_cart == 'incl' ) {
								$tax_string_array = array();

								foreach ( $eshopbox->cart->get_tax_totals() as $code => $tax ) {
									$tax_string_array[] = sprintf( '%s %s', $tax->formatted_amount, $tax->label );
								}

								if ( ! empty( $tax_string_array ) ) {
									echo '<small class="includes_tax">' . sprintf( __( '(Includes %s)', 'eshopbox' ), implode( ', ', $tax_string_array ) ) . '</small>';
								}
							}
						?>
					</td>
				</tr>

				<?php do_action( 'eshopbox_cart_totals_after_order_total' ); ?>

			</tbody>
		</table>

		<?php if ( $eshopbox->cart->get_cart_tax() ) : ?>

			<p><small><?php

				$estimated_text = ( $eshopbox->customer->is_customer_outside_base() && ! $eshopbox->customer->has_calculated_shipping() ) ? sprintf( ' ' . __( ' (taxes estimated for %s)', 'eshopbox' ), $eshopbox->countries->estimated_for_prefix() . __( $eshopbox->countries->countries[ $eshopbox->countries->get_base_country() ], 'eshopbox' ) ) : '';

				printf( __( 'Note: Shipping and taxes are estimated%s and will be updated during checkout based on your billing and shipping information.', 'eshopbox' ), $estimated_text );

			?></small></p>

		<?php endif; ?>

	<?php elseif( $eshopbox->cart->needs_shipping() ) : ?>

		<?php if ( ! $eshopbox->customer->get_shipping_state() || ! $eshopbox->customer->get_shipping_postcode() ) : ?>

			<div class="eshopbox-info">

				<p><?php _e( 'No shipping methods were found; please recalculate your shipping and enter your state/county and zip/postcode to ensure there are no other available methods for your location.', 'eshopbox' ); ?></p>

			</div>

		<?php else : ?>

			<?php

				$customer_location = $eshopbox->countries->countries[ $eshopbox->customer->get_shipping_country() ];

				echo apply_filters( 'eshopbox_cart_no_shipping_available_html',
					'<div class="eshopbox-error"><p>' .
					sprintf( __( 'Sorry, it seems that there are no available shipping methods for your location (%s).', 'eshopbox' ) . ' ' . __( 'If you require assistance or wish to make alternate arrangements please contact us.', 'eshopbox' ), $customer_location ) .
					'</p></div>'
				);

			?>

		<?php endif; ?>

	<?php endif; ?>

	<?php do_action( 'eshopbox_after_cart_totals' ); ?>

</div>