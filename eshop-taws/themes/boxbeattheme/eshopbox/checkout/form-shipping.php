<?php





/**





 * Checkout shipping information form





 *





 * @author 		WooThemes





 * @package 	eshopbox/Templates





 * @version     1.6.4





 */











if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly











global $eshopbox;





?>











<?php if ( ( $eshopbox->cart->needs_shipping() || get_option('eshopbox_require_shipping_address') == 'yes' ) && ! $eshopbox->cart->ship_to_billing_address_only() ) : ?>











	<?php





		if ( empty( $_POST ) ) :











			$shiptobilling = (get_option('eshopbox_ship_to_same_address')=='yes') ? 1 : 0;





			$shiptobilling = apply_filters('eshopbox_shiptobilling_default', $shiptobilling);











		else :











			$shiptobilling = $checkout->get_value('shiptobilling');











		endif;





	?>











	





	<div class="headingblock">





	<h3><?php _e( 'ENTER SHIPPING ADDRESS', 'eshopbox' ); ?></h3>





   


    <p class="form-row" id="shiptobilling">





		<input id="shiptobilling-checkbox" class="input-checkbox" <?php checked($shiptobilling, 1); ?> type="checkbox" name="shiptobilling" value="1" />





		<label for="shiptobilling-checkbox" class="checkbox"><?php _e( 'Ship to billing address?', 'eshopbox' ); ?></label>





	</p>





    </div>





    <div class="formsippingblock">





    	<div class="shipping_address">











		<?php do_action('eshopbox_before_checkout_shipping_form', $checkout); ?>











			<?php 





// order the keys for your custom ordering or delete the ones you don't need





$myshippingfields=array(





    "shipping_first_name",





    "shipping_last_name",





    "shipping_address_1",





    "shipping_city",


	"shipping_country",


    "shipping_state",





    "shipping_postcode",









	"shipping_email",





    "shipping_phone",





);





foreach ($myshippingfields as $key) : ?>





<?php eshopbox_form_field( $key, $checkout->checkout_fields['shipping'][$key], $checkout->get_value( $key ) ); ?>





<?php endforeach; ?>





		<?php do_action('eshopbox_after_checkout_shipping_form', $checkout); ?>











	





</div>


<div class="ordershippingblock">Your order will be shipped to your billing address.</div>


<?php endif; ?>











<?php do_action('eshopbox_before_order_notes', $checkout); ?>











<?php if (get_option('eshopbox_enable_order_comments')!='no') : ?>











	<?php if ($eshopbox->cart->ship_to_billing_address_only()) : ?>











		<h3><?php _e( 'Additional Information', 'eshopbox' ); ?></h3>











	<?php endif; ?>











	<?php foreach ($checkout->checkout_fields['order'] as $key => $field) : ?>











		<?php eshopbox_form_field( $key, $field, $checkout->get_value( $key ) ); ?>











	<?php endforeach; ?>











<?php endif; ?>











<?php do_action('eshopbox_after_order_notes', $checkout); ?>





</div>