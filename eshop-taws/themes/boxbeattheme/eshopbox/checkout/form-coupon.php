<?php
/**
 * Checkout coupon form
 *
 * @author 		WooThemes
 * @package 	eshopbox/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $eshopbox;

if ( ! $eshopbox->cart->coupons_enabled() )
	return;

$info_message = apply_filters('eshopbox_checkout_coupon_message', __( 'Have a coupon?', 'eshopbox' ));
?>

<p class="eshopbox-info" style="display:none;"><?php echo $info_message; ?> <a href="#" class="showcoupon"><?php _e( 'Click here to enter your code', 'eshopbox' ); ?></a></p>

<form class="checkout_coupon" method="post" style="display:none">

	<p class="form-row form-row-first">
		<input type="text" name="coupon_code" class="input-text" placeholder="<?php _e( 'Coupon code', 'eshopbox' ); ?>" id="coupon_code" value="" />
	</p>

	<p class="form-row form-row-last">
		<input type="submit" class="button" name="apply_coupon" value="<?php _e( 'Apply Coupon', 'eshopbox' ); ?>" />
	</p>

	<div class="clear"></div>
</form>