<?php
/**
 * Cart Page
 *
 * @author 		WooThemes
 * @package 	eshopbox/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
error_reporting(0);
global $eshopbox;

$eshopbox->show_messages();
//echo "<pre>";print_r($eshopbox->cart);echo "</pre>";
?>

<?php do_action( 'eshopbox_before_cart' ); ?>
<div class="left_itemcart">
<?php //dynamic_sidebar ('sidebar-7'); ?>
<div class="back_btn">
<a href="<?php echo home_url(); ?>">Continue Shopping</a>
</div>
<div class="total_bucket">
<h3>BAG SUMMARY</h3>
<?php eshopbox_cart_totals(); ?>
</div>

<div class="accordian_div">
<?php
global $product;

echo do_shortcode( '[accordions]	
[accordion title="NEED HELP ?"]'.get_dynamic_sidebar( 'sidebar-7' ).'[/accordion]
[accordion title="SECURE Payments"]'.get_dynamic_sidebar( 'sidebar-8' ).'[/accordion]
[accordion title="SHIPPING & DELIVERY"]'.get_dynamic_sidebar( 'sidebar-9' ).'[/accordion]

[/accordions]' )  ?>



</div>
</div>



<div class="cover_cart">
<form action="<?php echo esc_url( $eshopbox->cart->get_cart_url() ); ?>" method="post">

<?php do_action( 'eshopbox_before_cart_table' ); ?>
<div class="table_header">
<div class="table_headerleft">
<h1><?php //the_title(); ?>Your Shopping Bag <span> (<?php echo sprintf(_n('%d item', '%d items', $eshopbox->cart->cart_contents_count, 'woothemes'), $eshopbox->cart->cart_contents_count); ?>) </span> </h1>

</div>
<div class="tableheaderright">
 <input type="submit" class="checkout-button button alt" name="proceed" value="<?php _e( 'order now', 'eshopbox' ); ?>" />

</div>
<div class="clear"></div>
</div>


<div class="cart_tablecover">
<table class="shop_table cart" cellspacing="0">
	<thead>
    
		<tr>
			
			<th class="product-thumbnail"><?php _e( 'Item Description', 'eshopbox' ); ?></th>
			<th class="product-name"><?php _e( '', 'eshopbox' ); ?></th>
			
			<th class="product-quantity"><?php _e( 'Quantity', 'eshopbox' ); ?></th>
            <th class="unit_price"><?php _e( 'Unit Price', 'eshopbox' ); ?></th>
			<th class="product-subtotal"><?php _e( 'Total', 'eshopbox' ); ?></th>
            
		</tr>
	</thead>
	<tbody>
		<?php do_action( 'eshopbox_before_cart_contents' ); ?>

		<?php
		if ( sizeof( $eshopbox->cart->get_cart() ) > 0 ) {
			foreach ( $eshopbox->cart->get_cart() as $cart_item_key => $values ) {
				$_product = $values['data'];
				//echo "<pre>";print_r($_product);echo "</pre>";
				if ( $_product->exists() && $values['quantity'] > 0 ) {
					?>
					<tr class = "<?php echo esc_attr( apply_filters('eshopbox_cart_table_item_class', 'cart_table_item', $values, $cart_item_key ) ); ?>">
						

						<!-- The thumbnail -->
						<td class="product-thumbnail">
							<?php
								$thumbnail = apply_filters( 'eshopbox_in_cart_product_catalog', $_product->get_image('shop_catalog'), $values, $cart_item_key );

								if ( ! $_product->is_visible() || ( ! empty( $_product->variation_id ) && ! $_product->parent_is_visible() ) )
									echo $thumbnail;
								else
									printf('<a href="%s">%s</a>', esc_url( get_permalink( apply_filters('eshopbox_in_cart_product_id', $_product->parent->id ) ) ), $thumbnail );
							?>
						</td>

						<!-- Product Name -->
						<td class="product-name">
							<?php //echo $_product->parent->id."===";
								if ( ! $_product->is_visible() || ( ! empty( $_product->variation_id ) && ! $_product->parent_is_visible() ) )
									echo apply_filters( 'eshopbox_in_cart_product_title', $_product->get_title(), $values, $cart_item_key );
								else
									printf('<a href="%s">%s</a>', esc_url( get_permalink( apply_filters('eshopbox_in_cart_product_id', $_product->parent->id ) ) ), apply_filters('eshopbox_in_cart_product_title', $_product->get_title(), $values, $cart_item_key ) );

                                                            if($_product->get_sku())
                                                                    {
                                                                            echo "<p class='syle_code'>Style Code : ". $_product->get_sku(). "</p>";
                                                                    }
								// Meta data

                                                                    $variation_data=$_product->variation_data;
                                                                    //$pro_size=$variation_data['attribute_pa_size'];
                                                                    //$stock=$_product->product_custom_fields['_stock'][0];
                                                                    $stock=$_product->get_stock_quantity();
                                                                    if($pro_size)
                                                                        echo "size : ".$pro_size;
                                                                    if($stock > 0){
                                                                        echo "<div class='avail_hel'>Availability : <span class='gre_cls1'>Instock</span></div>";
                                                                    }else{
                                                                        echo "<div class='avail_hel'>Availability : <span class='red_cls1'>Outofstock</span></div>";
                                                                    }


                   				// Backorder notification
                   				if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $values['quantity'] ) )
                   					echo '<p class="backorder_notification">' . __( 'Available on backorder', 'eshopbox' ) . '</p>';
							?>
                            <?php //echo $eshopbox->cart->get_item_data( $values );
							
				
				
//echo "<pre>";print_r($eshopbox->cart);echo "</pre>";
				$size=$_product->variation_data['attribute_pa_size'];
				
				if($size){
					//echo '<p class="size_cls"><span>Size :</span> '.$size. '</p>';
			}
			//echo "<pre>";print_r($_product);echo "</pre>";exit;
			$color=wp_get_post_terms($_product->parent->id, 'pa_color', array("fields" => "all"));
			
			if($color){
				//echo '<p class="color_cls"><span>Color :</span> '.$color[0]->name; '</p>';
			}
			
			?>
           <p class="price_cart">
            <?php
								$product_price = get_option('eshopbox_tax_display_cart') == 'excl' ? $_product->get_price_excluding_tax() : $_product->get_price_including_tax();

								//echo apply_filters('eshopbox_cart_item_price_html', eshopbox_price( $product_price ), $values, $cart_item_key );
							?></p>
                            
                            <?php
                                                    echo apply_filters( 'eshopbox_cart_item_remove_link', sprintf('<a href="%s" class="remove" title="%s">Remove</a>', esc_url( $eshopbox->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'eshopbox' ) ), $cart_item_key );
						?>
						</td>

						<!-- Product price -->
						

						<!-- Quantity inputs -->
						<td class="product-quantity">
							<?php
								if ( $_product->is_sold_individually() ) {
									$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
								} else {

									$step	= apply_filters( 'eshopbox_quantity_input_step', '1', $_product );
									$min 	= apply_filters( 'eshopbox_quantity_input_min', '', $_product );
									$max 	= apply_filters( 'eshopbox_quantity_input_max', $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(), $_product );

									$product_quantity = sprintf( '<div class="quantity"><input type="number" name="cart[%s][qty]" step="%s" min="%s" max="%s" value="%s" size="4" title="' . _x( 'Qty', 'Product quantity input tooltip', 'eshopbox' ) . '" class="input-text qty text" maxlength="12" /></div>', $cart_item_key, $step, $min, $max, esc_attr( $values['quantity'] ) );
								}

								echo apply_filters( 'eshopbox_cart_item_quantity', $product_quantity, $cart_item_key );
							?>
						</td>
                        
                        <td class="unit_price">
                        <p itemprop="price" class="price"><?php echo $_product->get_price_html(); ?></p>
                        
                        
                        </td>

						<!-- Product subtotal -->
						<td class="product-subtotal">
							<?php
								echo apply_filters( 'eshopbox_cart_item_subtotal', $eshopbox->cart->get_product_subtotal( $_product, $values['quantity'] ), $values, $cart_item_key );
							?>
						</td>
                        
                        <!-- Remove from cart link -->
						
					</tr>
					<?php
				}
			}
		}

		do_action( 'eshopbox_cart_contents' );
		?>
	

		
	</tbody>
</table>
</div>

     <div class="button_bottom">
<p class="coupon_text">Have a diScount Coupon ?</p>
				<?php if ( $eshopbox->cart->coupons_enabled() ) { ?>
                                <div class="coupon">
            
                <label for="coupon_code"><?php _e( 'Coupon', 'eshopbox' ); ?>:</label>
                <input name="coupon_code" class="input-text cupon_input" id="coupon_code" value="" placeholder="Enter Coupon Code" /> 
                <input type="submit" class="button update_cart" name="apply_coupon" value="<?php _e( 'Apply', 'eshopbox' ); ?>" />
            
                <?php do_action('eshopbox_cart_coupon'); ?>

					</div>
				<?php } ?>
                <input type="submit" class="checkout-button button alt" name="proceed" value="<?php _e( 'order now', 'eshopbox' ); ?>" />
				<input type="submit" class="button update_cart align_cls cls_re" name="update_cart" value="<?php _e( 'Update Bag', 'eshopbox' ); ?>" />
                

				<?php do_action('eshopbox_proceed_to_checkout'); ?>

				<?php $eshopbox->nonce_field('cart') ?>
<?php do_action( 'eshopbox_after_cart_contents' ); ?>
<div class="clear"></div>
</div>


<?php do_action( 'eshopbox_after_cart_table' ); ?>

</form>

<div class="cart-collaterals">

	<?php do_action('eshopbox_cart_collaterals'); ?>

	<?php //eshopbox_cart_totals(); ?>

	<?php eshopbox_shipping_calculator(); ?>

</div>
</div>
<?php do_action( 'eshopbox_after_cart' ); 
?>
