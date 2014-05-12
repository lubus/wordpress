<?php
/**
 * Pay for order form
 *
 * @author 		WooThemes
 * @package 	eshopbox/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $eshopbox;
?>
<form id="order_review" method="post">

	<table class="shop_table">
		<thead>
			<tr>
				<th class="product-name"><?php _e( 'Product', 'eshopbox' ); ?></th>
                <th class="product-info"></th>
				<th class="product-quantity"><?php _e( 'Qty', 'eshopbox' ); ?></th>
				<th class="product-total"><?php _e( 'Totals', 'eshopbox' ); ?></th>
			</tr>
		</thead>
		<tfoot>
		<?php
			if ( $totals = $order->get_order_item_totals() ) foreach ( $totals as $total ) :
				?>
				<tr>
                	<td style="border:0px none;"></td>
                    <td style="border:0px none;"></td>
                    <th scope="row"><?php echo $total['label']; ?></th>
					<td class="product-total"><?php echo $total['value']; ?></td>
				</tr>
				<?php
			endforeach;
		?>
		</tfoot>
		<tbody>
			<?php
			if (sizeof($order->get_items())>0) :
				foreach ($order->get_items() as $item) :
					echo '
						<tr>
							<td class="product-name">image here</td>
							<td class="product-info">'.$item['name'].'<div class="skucode">Sku : lh0012</div> <div class="sizecode">Size : S</div></td>
							<td class="product-quantity">'.$item['qty'].'</td>
							<td class="product-subtotal">' . $order->get_formatted_line_subtotal($item) . '</td>
						</tr>';
				endforeach;
			endif;
			?>
		</tbody>
	</table>

	<div id="payment">
    	<h3>Payment Method</h3>
		<?php if ($order->order_total > 0) : ?>
		<ul class="payment_methods methods">
			<?php
				if ( $available_gateways = $eshopbox->payment_gateways->get_available_payment_gateways() ) {
					// Chosen Method
					if ( sizeof( $available_gateways ) )
						current( $available_gateways )->set_current();

					foreach ( $available_gateways as $gateway ) {
						?>
						<li>
							<input type="radio" id="payment_method_<?php echo $gateway->id; ?>" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php if ($gateway->chosen) echo 'checked="checked"'; ?> />
							<label for="payment_method_<?php echo $gateway->id; ?>"><?php echo $gateway->get_title(); ?> <?php echo $gateway->get_icon(); ?></label>
							<?php
								if ( $gateway->has_fields() || $gateway->get_description() ) {
									echo '<div class="payment_box payment_method_' . $gateway->id . '" style="display:none;">';
									$gateway->payment_fields();
									echo '</div>';
								}
							?>
						</li>
						<?php
					}
				} else {

					echo '<p>'.__( 'Sorry, it seems that there are no available payment methods for your location. Please contact us if you require assistance or wish to make alternate arrangements.', 'eshopbox' ).'</p>';

				}
			?>
		</ul>
		<?php endif; ?>

		<div class="form-row">
			<?php $eshopbox->nonce_field('pay')?>
			<input type="submit" class="button alt place_order_checkout" id="place_order" value="<?php _e( 'Pay for order', 'eshopbox' ); ?>" />
			<input type="hidden" name="eshopbox_pay" value="1" />
		</div>

	</div>

</form>