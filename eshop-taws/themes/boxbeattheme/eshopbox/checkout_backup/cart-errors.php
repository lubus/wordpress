<?php
/**
 * Cart errors page
 *
 * @author 		WooThemes
 * @package 	EshopBox/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<?php $eshopbox->show_messages(); ?>

<p><?php _e( 'There are some issues with the items in your cart (shown above). Please go back to the cart page and resolve these issues before checking out.', 'eshopbox' ) ?></p>

<?php do_action('eshopbox_cart_has_errors'); ?>

<p><a class="button" href="<?php echo get_permalink(eshopbox_get_page_id('cart')); ?>"><?php _e( '&larr; Return To Cart', 'eshopbox' ) ?></a></p>