<?php
/**
 * Cart totals
 *
 * @author 		WooThemes
 * @package 	eshopbox/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $eshopbox;

$available_methods = $eshopbox->shipping->get_available_shipping_methods();
    
?>
<div class="cart_totals <?php if ( $eshopbox->customer->has_calculated_shipping() ) echo 'calculated_shipping'; ?>">

	<?php do_action( 'eshopbox_before_cart_totals' ); ?>

	<?php if ( ! $eshopbox->shipping->enabled || $available_methods || ! $eshopbox->customer->get_shipping_country() || ! $eshopbox->customer->has_calculated_shipping() ) : ?>



		<table cellspacing="0" class="cart_total cartsub">
			<tbody>

				<tr class="cart-subtotal">
					<th><?php _e( 'Bag Subtotal	 ', 'eshopbox' ); ?></th>
                    <td> : </td>
					<td><?php echo $eshopbox->cart->get_cart_subtotal(); ?></td>
				</tr>

				<?php if ( $eshopbox->cart->get_discounts_before_tax() ) : ?>

					<tr class="discount">
						<th><?php _e( 'Cart Discount', 'eshopbox' ); ?> <a href="<?php echo add_query_arg( 'remove_discounts', '1', $eshopbox->cart->get_cart_url() ) ?>"><?php _e( '[Remove]', 'eshopbox' ); ?></a></th>
                        <td> : </td>
						<td>-<?php echo $eshopbox->cart->get_discounts_before_tax(); ?></td>
					</tr>

				<?php endif; ?>

				<?php if ( $eshopbox->cart->needs_shipping() && $eshopbox->cart->show_shipping() && ( $available_methods || get_option( 'eshopbox_enable_shipping_calc' ) == 'yes' ) ) : ?>

					<?php do_action( 'eshopbox_cart_totals_before_shipping' ); ?>

					<tr class="shipping">
						<th><?php _e( 'Shipping', 'eshopbox' ); ?></th>
                        <td> : </td>
						<td><?php eshopbox_get_template( 'cart/shipping-methods.php', array( 'available_methods' => $available_methods ) ); ?></td>
					</tr>
                    
<!--                    <tr class="discount_total">
                    <th>Discount </th>
                    <td> : </td>
                    <td> value

                  

                    </td>
                    </tr>-->

					<?php do_action( 'eshopbox_cart_totals_after_shipping' ); ?>

				<?php endif ?>

				<?php foreach ( $eshopbox->cart->get_fees() as $fee ) : ?>

					<tr class="fee fee-<?php echo $fee->id ?>">
						<th><?php echo $fee->name ?></th>
                        <td> : </td>
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
						$taxes = $eshopbox->cart->get_formatted_taxes();

						if ( sizeof( $taxes ) > 0 ) {

							$has_compound_tax = false;

							foreach ( $taxes as $key => $tax ) {
								if ( $eshopbox->cart->tax->is_compound( $key ) ) {
									$has_compound_tax = true;
									continue;
								}

								echo '<tr class="tax-rate tax-rate-' . $key . '">
									<th>' . $eshopbox->cart->tax->get_rate_label( $key ) . '</th>
									<td>' . $tax . '</td>
								</tr>';
							}

							if ( $has_compound_tax ) {

								echo '<tr class="order-subtotal">
									<th>' . __( 'Subtotal', 'eshopbox' ) . '</th>
									<td>' . $eshopbox->cart->get_cart_subtotal( true ) . '</td>
								</tr>';
							}

							foreach ( $taxes as $key => $tax ) {
								if ( ! $eshopbox->cart->tax->is_compound( $key ) )
									continue;

								echo '<tr class="tax-rate tax-rate-' . $key . '">
									<th>' . $eshopbox->cart->tax->get_rate_label( $key ) . '</th>
									<td>' . $tax . '</td>
								</tr>';
							}

						} elseif ( $eshopbox->cart->get_cart_tax() > 0 ) {

							echo '<tr class="tax">
								<th>' . __( 'Tax', 'eshopbox' ) . '</th>
								<td>' . $eshopbox->cart->get_cart_tax() . '</td>
							</tr>';
						}
					}
				?>

				<?php if ( $eshopbox->cart->get_discounts_after_tax() ) : ?>

					<tr class="discount">
						<th><?php _e( 'Order Discount', 'eshopbox' ); ?> <a href="<?php echo add_query_arg( 'remove_discounts', '2', $eshopbox->cart->get_cart_url() ) ?>"><?php _e( '[Remove]', 'eshopbox' ); ?></a></th>
                        <td> : </td>
						<td>-<?php echo $eshopbox->cart->get_discounts_after_tax(); ?></td>
					</tr>

				<?php endif; ?>

				<?php do_action( 'eshopbox_cart_totals_before_order_total' ); ?>

				<tr class="total">
					<th><?php _e( 'Grand Total ', 'eshopbox' ); ?></th>
                    <td> : </td>
					<td>
						<?php echo $eshopbox->cart->get_total(); ?>
						<?php
							// If prices are tax inclusive, show taxes here
							if (  $eshopbox->cart->tax_display_cart == 'incl' ) {
								$tax_string_array = array();
								$taxes = $eshopbox->cart->get_formatted_taxes();

								if ( sizeof( $taxes ) > 0 )
									foreach ( $taxes as $key => $tax )
										$tax_string_array[] = sprintf( '%s %s', $tax, $eshopbox->cart->tax->get_rate_label( $key ) );
								elseif ( $eshopbox->cart->get_cart_tax() )
									$tax_string_array[] = sprintf( '%s tax', $tax );

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

			<div class="eshopbox-error">

				<p><?php printf( __( 'Sorry, it seems that there are no available shipping methods for your location (%s).', 'eshopbox' ), $eshopbox->countries->countries[ $eshopbox->customer->get_shipping_country() ] ); ?></p>

				<p><?php _e( 'If you require assistance or wish to make alternate arrangements please contact us.', 'eshopbox' ); ?></p>

			</div>

		<?php endif; ?>

	<?php endif; ?>

	<?php do_action( 'eshopbox_after_cart_totals' ); ?>

</div>
