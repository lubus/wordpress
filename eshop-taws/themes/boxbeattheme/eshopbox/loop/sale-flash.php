<?php
/**
 * Product loop sale flash
 *
 * @author 		WooThemes
 * @package 	EshopBox/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product;
?>
<?php if ($product->is_on_sale()) : ?>

	<?php echo apply_filters('eshopbox_sale_flash', '<span class="onsale">'.__( 'Sale!', 'eshopbox' ).'</span>', $post, $product); ?>

<?php endif; ?>