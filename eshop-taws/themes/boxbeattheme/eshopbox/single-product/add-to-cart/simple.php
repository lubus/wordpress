<?php
/**
 * Simple product add to cart
 *
 * @author 		WooThemes
 * @package 	EshopBox/Templates
 * @version     2.0.15
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $eshopbox, $product;

if ( ! $product->is_purchasable() ) return;
?>

<?php
	// Availability
	$availability = $product->get_availability();

	if ($availability['availability']) :
		echo apply_filters( 'eshopbox_stock_html', '<p class="stock ' . esc_attr( $availability['class'] ) . '">' . esc_html( $availability['availability'] ) . '</p>', $availability['availability'] );
    endif;
?>

<?php //if ( $product->is_in_stock() ) : 
//if($availability['availability'] == 'Out of stock'){
//echo "out od st producttttt";
//}
?>

	<?php do_action('eshopbox_before_add_to_cart_form'); ?>

	<form class="cart" method="post" enctype='multipart/form-data'>

	 	<?php do_action('eshopbox_before_add_to_cart_button'); ?>

	 	<?php
	 		if ( ! $product->is_sold_individually() )
	 			eshopbox_quantity_input( array(
	 				'min_value' => apply_filters( 'eshopbox_quantity_input_min', 1, $product ),
	 				'max_value' => apply_filters( 'eshopbox_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product )
	 			) );
	 	?>

		<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->id ); ?>" />
<div class="second_priceblock"><p itemprop="price" class="price"><span class="amount"><?php echo $product->get_price_html(); ?></span> <span class="free_shiiping"><?php if(COUNTRY_IN_SESSION == 'IN'){
		echo 'Plus FREE Shipping';
		}else{
		echo 'Plus Shipping';
		}
		?></span></p></div>
		<?php
		if($availability['availability'] == 'Out of stock'){  
		echo "<div class='out_stock'>Out of Stock</div>";
		}else{               
		?>
                        <button type="submit" class="single_add_to_cart_button button alt"><?php echo apply_filters('single_add_to_cart_text', __( 'Add to cart', 'eshopbox' ), $product->product_type); ?></button>               
                <?php               
                }               
                ?>
	 	<?php do_action('eshopbox_after_add_to_cart_button'); ?>

	</form>

	<?php do_action('eshopbox_after_add_to_cart_form'); ?>

<?php //endif; ?>
