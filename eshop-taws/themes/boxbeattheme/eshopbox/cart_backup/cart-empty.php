<?php
/**
 * Empty cart page
 *
 * @author 		WooThemes
 * @package 	EshopBox/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<p><?php _e( 'Your cart is currently empty.', 'eshopbox' ) ?></p>

<?php do_action('eshopbox_cart_is_empty'); ?>

<p><a class="button" href="<?php echo get_permalink(eshopbox_get_page_id('shop')); ?>"><?php _e( '&larr; Return To Shop', 'eshopbox' ) ?></a></p>