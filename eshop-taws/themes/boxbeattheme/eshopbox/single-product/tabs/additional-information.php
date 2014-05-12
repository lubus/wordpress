<?php
/**
 * Additional Information tab
 *
 * @author 		WooThemes
 * @package 	EshopBox/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $eshopbox, $post, $product;

$heading = apply_filters( 'eshopbox_product_additional_information_heading', __( 'Additional Information', 'eshopbox' ) );
?>

<h2><?php echo $heading; ?></h2>

<?php $product->list_attributes(); ?>