<?php
/**
 * Description tab
 *
 * @author 		WooThemes
 * @package 	EshopBox/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $eshopbox, $post;

$heading = esc_html( apply_filters('eshopbox_product_description_heading', __( 'Product Description', 'eshopbox' ) ) );
?>

<h2><?php echo $heading; ?></h2>

<?php the_content(); ?>