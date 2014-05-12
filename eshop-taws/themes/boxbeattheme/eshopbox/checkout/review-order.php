<?php

/**

 * Review order form

 *

 * @author 		WooThemes

 * @package 	eshopbox/Templates

 * @version     1.6.4

 */



if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



global $eshopbox;



$available_methods = $eshopbox->shipping->get_available_shipping_methods();

?>

<div id="order_review">

	<table class="shop_table">

		<thead>

			<tr>

				<th class="product-name"><?php _e( 'Product Info', 'eshopbox' ); ?></th>

                <th class="product-info"><?php _e( '', 'eshopbox' ); ?></th>
                <th class="product-total"><?php _e( 'Total', 'eshopbox' ); ?></th>

			</tr>

		</thead>

		<tfoot>

			<tr class="cart-subtotal">
				<th colspan="2"><?php _e( 'Cart Subtotal', 'eshopbox' ); ?></th>
				<td><?php echo $eshopbox->cart->get_cart_subtotal(); ?></td>
			</tr>



			<?php if ( $eshopbox->cart->get_discounts_before_tax() ) : ?>



			<tr class="discount">
				<th colspan="2"><?php _e( 'Discount', 'eshopbox' ); ?></th>
				<td>-<?php echo $eshopbox->cart->get_discounts_before_tax(); ?></td>
			</tr>



			<?php endif; ?>



			<?php if ( $eshopbox->cart->needs_shipping() && $eshopbox->cart->show_shipping() ) : ?>



				<?php //do_action('eshopbox_review_order_before_shipping'); ?>



				<tr class="shipping">
					<th colspan="2"><?php  _e( 'Shipping', 'eshopbox' ); ?></th>

                   <td><?php  eshopbox_get_template( 'cart/shipping-methods.php', array( 'available_methods' => $available_methods ) ); ?></td>

				</tr>



				<?php  do_action('eshopbox_review_order_after_shipping'); ?>



			<?php endif; ?>



			<?php foreach ( $eshopbox->cart->get_fees() as $fee ) : ?>



				<tr class="fee fee-<?php echo $fee->id ?>">
              	<th colspan="2"><?php echo $fee->name ?></th>
				<td><?php

						if ( $eshopbox->cart->tax_display_cart == 'excl' )

							echo eshopbox_price( $fee->amount );

						else

							echo eshopbox_price( $fee->amount + $fee->tax );

					?></td>

				</tr>



			<?php endforeach; ?>



			<?php

				// Show the tax row if showing prices exlcusive of tax only

				if ( $eshopbox->cart->tax_display_cart == 'excl' ) {



					$taxes = $eshopbox->cart->get_formatted_taxes();



					if ( sizeof( $taxes ) > 0 ) {



						$has_compound_tax = false;



						foreach ( $taxes as $key => $tax ) {

							if ( $eshopbox->cart->tax->is_compound( $key ) ) {

								$has_compound_tax = true;

								continue;

							}

							?>

							<tr class="tax-rate tax-rate-<?php echo $key; ?>">
                            	<th colspan="2" style="border:0px none;"><?php echo $eshopbox->cart->tax->get_rate_label( $key ); ?></th>


                            	<td><?php echo $tax; ?></td>

							</tr>

							<?php

						}



						if ( $has_compound_tax ) {

							?>

							<tr class="order-subtotal">
                            	<th colspan="2"><?php _e( 'Cart Subtotal', 'eshopbox' ); ?></th>

                                
                            	<td><?php echo $eshopbox->cart->get_cart_subtotal( true ); ?></td>

							</tr>

							<?php

						}



						foreach ( $taxes as $key => $tax ) {

							if ( ! $eshopbox->cart->tax->is_compound( $key ) )

								continue;

							?>

							<tr class="tax-rate tax-rate-<?php echo $key; ?>">
                            
                            	<th colspan="2" style="border:0px none;"><?php echo $eshopbox->cart->tax->get_rate_label( $key ); ?></th>

                               

                            	<td><?php echo $tax; ?></td>

							</tr>

							<?php

						}



					} elseif ( $eshopbox->cart->get_cart_tax() ) {

						?>

						<tr class="tax">
							<th colspan="2"><?php _e( 'Tax', 'eshopbox' ); ?></th>

                        	

                            <td><?php echo $eshopbox->cart->get_cart_tax(); ?></td>

						</tr>

						<?php

					}

				}

			?>



			<?php if ($eshopbox->cart->get_discounts_after_tax()) : ?>



			<tr class="discount">
				<th colspan="2"><?php _e( 'Discount', 'eshopbox' ); ?></th>

                
               <td>-<?php echo $eshopbox->cart->get_discounts_after_tax(); ?></td>

			</tr>



			<?php endif; ?>



			<?php do_action( 'eshopbox_review_order_before_order_total' ); ?>



			<tr class="total">
				<th colspan="2"><strong><?php _e( 'Order Total', 'eshopbox' ); ?></strong></th>

                <td>

					<strong><?php echo $eshopbox->cart->get_total(); ?></strong>

					<?php

						// If prices are tax inclusive, show taxes here

						if ( $eshopbox->cart->tax_display_cart == 'incl' ) {

							$tax_string_array = array();

							$taxes = $eshopbox->cart->get_formatted_taxes();



							if ( sizeof( $taxes ) > 0 ) {

								foreach ( $taxes as $key => $tax ) {

									$tax_string_array[] = sprintf( '%s %s', $tax, $eshopbox->cart->tax->get_rate_label( $key ) );

								}

							} elseif ( $eshopbox->cart->get_cart_tax() ) {

								$tax_string_array[] = sprintf( '%s tax', $tax );

							}



							if ( ! empty( $tax_string_array ) ) {

								?><small class="includes_tax"><?php printf( __( '(Includes %s)', 'eshopbox' ), implode( ', ', $tax_string_array ) ); ?></small><?php

							}

						}

					?>

				</td>

			</tr>



			<?php   do_action( 'eshopbox_review_order_after_order_total' ); ?>



		</tfoot>

		<tbody>

			<?php

				do_action( 'eshopbox_review_order_before_cart_contents' );



				if (sizeof($eshopbox->cart->get_cart())>0) :

					foreach ($eshopbox->cart->get_cart() as $cart_item_key => $values) :

						$_product = $values['data'];
                                 $product_size=$_product->variation_data['attribute_pa_size'];

						if ($_product->exists() && $values['quantity']>0) :

							echo '<tr class="' . esc_attr( apply_filters('eshopbox_checkout_table_item_class', 'checkout_table_item', $values, $cart_item_key ) ) . '">

									<td class="product-image">';

										//echo  $thumbnail = apply_filters( 'eshopbox_in_cart_product_thumbnail', $_product->get_image(), $values, $cart_item_key );
                                                                $thumbnail = $_product->get_image('shop_catalog');
								//$thumbnail = apply_filters( 'eshopbox_in_cart_product_thumbnail', $_product->get_image(), $values, $cart_item_key );

								if ( ! $_product->is_visible() || ( ! empty( $_product->variation_id ) && ! $_product->parent_is_visible() ) )
									echo $thumbnail;
								else
									printf('<a href="%s">%s</a>', esc_url( get_permalink( apply_filters('eshopbox_in_cart_product_id', $values['product_id'] ) ) ), $thumbnail );
							

                                     echo'</td>
									 
									 <td class="product-info">' .
									 //apply_filters( 'eshopbox_checkout_product_title', $_product->get_title(), $_product ) . ' ' .' <div class="skucode">Style Code :# '.$_product->get_sku().'</div>	<div class="productcode">'. $eshopbox->cart->get_item_data( $values ).'</div> <div class="qty"> Quantity :' .apply_filters( 'eshopbox_checkout_item_quantity', '<strong class="product-quantity">' . $values['quantity'] . '</strong>', $values, $cart_item_key ).'</div>'.'</td>
                                             apply_filters( 'eshopbox_checkout_product_title', $_product->get_title(), $_product ) . ' ' .' <div class="skucode">color : <span>'. $product_size=$_product->variation_data['attribute_pa_size'].'</span></div><div class="productcode">'. $eshopbox->cart->get_item_data( $values ).'</div> <div class="qty"> Dimension : ' .apply_filters( 'eshopbox_checkout_item_quantity', '<strong class="product-quantity">' . $values['quantity'] . '</strong>', $values, $cart_item_key ).'</div>'.'</td>

									<td class="product-total">' . apply_filters( 'eshopbox_checkout_item_subtotal', $eshopbox->cart->get_product_subtotal( $_product, $values['quantity'] ), $values, $cart_item_key ) . '</td>

								</tr>';

						endif;

					endforeach;

				endif;



				do_action( 'eshopbox_review_order_after_cart_contents' );

			?>

		</tbody>

	</table>



	<div id="payment">

    	<h3>CHOOSE PAYMENT METHOD</h3>

		<?php if ($eshopbox->cart->needs_payment()) : ?>

		<ul class="payment_methods methods">

			<?php

				$available_gateways = $eshopbox->payment_gateways->get_available_payment_gateways();

				if ( ! empty( $available_gateways ) ) {



					// Chosen Method

					if ( isset( $eshopbox->session->chosen_payment_method ) && isset( $available_gateways[ $eshopbox->session->chosen_payment_method ] ) ) {

						$available_gateways[ $eshopbox->session->chosen_payment_method ]->set_current();

					} elseif ( isset( $available_gateways[ get_option( 'eshopbox_default_gateway' ) ] ) ) {

						$available_gateways[ get_option( 'eshopbox_default_gateway' ) ]->set_current();

					} else {

						current( $available_gateways )->set_current();

					}



					foreach ( $available_gateways as $gateway ) {

						?>

						<li>

							<input type="radio" id="payment_method_<?php echo $gateway->id; ?>" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> />

							<label for="payment_method_<?php echo $gateway->id; ?>"><?php echo $gateway->get_title(); ?> <?php echo $gateway->get_icon(); ?></label>

							<?php

								if ( $gateway->has_fields() || $gateway->get_description() ) :

									echo '<div class="payment_box payment_method_' . $gateway->id . '" ' . ( $gateway->chosen ? '' : 'style="display:none;"' ) . '>';

									if ( $gateway->has_fields() || $gateway->get_description() ) :
									echo '<div class="payment_box payment_method_' . $gateway->id . '" ' . ( $gateway->chosen ? '' : 'style="display:none;"' ) . '>';
										if(COUNTRY_IN_SESSION == 'IN' && $gateway->id == 'cod' && isset($_REQUEST['postcode']) && $_REQUEST['postcode']!='' && !checkvalidpincode($_REQUEST['postcode'])){
											echo 'We do not provide Cash-on-Delivery to this pincode, you can try paying online.';
										}else{
											$gateway->payment_fields();
										}
									echo '</div>';
								endif;

									echo '</div>';

								endif;

							?>

						</li>

					<?php
					}
				} else {
					if ( ! $eshopbox->customer->get_country() )
						echo '<p>' . __( 'Please fill in your details above to see available payment methods.', 'eshopbox' ) . '</p>';
					else
						echo '<p>' . __( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'eshopbox' ) . '</p>';
				}
			?>
		</ul>
		<?php endif; ?>
		<div class="form-row place-order">
		<noscript><?php _e( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the <em>Update Totals</em> button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'eshopbox' ); ?><br/><input type="submit" class="button alt" name="eshopbox_checkout_update_totals" value="<?php _e( 'Update totals', 'eshopbox' ); ?>" /></noscript>
		<?php $eshopbox->nonce_field('process_checkout')?>
		<?php do_action( 'eshopbox_review_order_before_submit' );
		if(isset($_REQUEST['postcode']) && $_REQUEST['postcode']!='' && COUNTRY_IN_SESSION == 'IN'){
			if(checkvalidpincode($_REQUEST['postcode'])){ ?>
				<input type="submit" class="button alt" name="eshopbox_checkout_place_order" id="place_order" value="<?php echo apply_filters('eshopbox_order_button_text', __( 'Place order now', 'eshopbox' )); ?>" />
			<?php }
			}else{ ?>
				<input type="submit" class="button alt" name="eshopbox_checkout_place_order" id="place_order" value="<?php echo apply_filters('eshopbox_order_button_text', __( 'Place order now', 'eshopbox' )); ?>" />
			<?php } ?>
			<?php if (eshopbox_get_page_id('terms')>0) : ?>
			<p class="form-row terms">
				<label for="terms" class="checkbox"><?php _e( 'I have read and accept the', 'eshopbox' ); ?> <a href="<?php echo esc_url( get_permalink(eshopbox_get_page_id('terms')) ); ?>" target="_blank"><?php _e( 'terms &amp; conditions', 'eshopbox' ); ?></a></label>
				<input type="checkbox" class="input-checkbox" name="terms" <?php checked( isset( $_POST['terms'] ), true ); ?> id="terms" />
			</p>
			<?php endif; ?>
			<?php do_action( 'eshopbox_review_order_after_submit' ); ?>
		</div>
        <div id="placeorderbutton" style="display:none;">Processing......</div>
		<div class="clear"></div>
	</div>
</div>