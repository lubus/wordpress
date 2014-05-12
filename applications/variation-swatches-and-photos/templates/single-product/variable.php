<?php
/**
 * Variable Product Add to Cart
 */
global $eshopbox, $product, $post;

$variation_params = eshopbox_swatches_get_variation_form_args();

?>
<script type="text/javascript">
    var product_variations = <?php echo json_encode($variation_params['available_variations']) ?>;
    var product_attributes = <?php echo json_encode($variation_params['attributes_renamed']); ?>;
    var product_variations_flat = <?php echo json_encode($variation_params['available_variations_flat']); ?>;
</script>

<span id="size_alert_msg" style="display:none;">Select a Size</span>

<?php do_action('eshopbox_before_add_to_cart_form'); ?>
<form action="<?php echo esc_url($product->add_to_cart_url()); ?>" class="variations_form cart" method="post" enctype='multipart/form-data'>
    
    <div class="variation_form_section">
        <?php
        $eshopbox_variation_control_output = new WC_Swatch_Picker($product->id, $attributes, $selected_attributes);
        $eshopbox_variation_control_output->picker();
        ?>
    </div>
    
 	<?php do_action('eshopbox_before_add_to_cart_button'); ?>

<?php
 //if($_SERVER['REMOTE_ADDR'] == '203.92.41.3'){
  global $wpdb;
	                $array_size = array();	               
	                $qrypost = mysql_query("SELECT ID FROM $wpdb->posts where post_parent='".$product->id."'");
	                while($fetch_sql = mysql_fetch_array($qrypost)){
	                $parent_id[] = $fetch_sql[0];
	                }//echo "<pre>";print_r($parent_id);
	                if($parent_id != NULL){
	                        foreach($parent_id as $key => $val_id){
	                        $postmetadetail=get_post_meta($val_id);
         	                $postmetadetail['attribute_pa_size'][0];
	                        $stock_arr[] = $postmetadetail['_stock'][0];//echo "<pre>";print_r($stock_arr);	                		                
	                        }
	                }
	                $tmp = array_filter($stock_arr);//echo "<pre>";print_r($tmp);
	                
//}	        

?>
<div class="single_variation"></div>
	<div class="single_variation_wrap" style="display:block;">
		
		<?php 
		
		if($tmp == NULL ){ ?>
		<div class="variations_button" style="display:none;">
		<?php }else{ ?>
		<div class="variations_button" style="display:block;">
		<?php } ?>
		
			<input type="hidden" name="variation_id" value="" />
<div class="buttn_left">
                    <?php
                    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

                    global $product;
                    ?>

                    <?php //if ( $price_html = $product->get_price_html() ) : ?>
                    <!--<span class="price"><?php //echo $price_html; ?></span>-->
                    <?php //endif; ?>
                    </div>
                    <?php
                    if($tmp != NULL ){
                    ?>
			<button type="submit" class="single_add_to_cart_button button alt"><?php echo apply_filters('single_add_to_cart_text', __( 'Add to cart', 'eshopbox' ), $product->product_type); ?></button>
	             <?php
	             } else{
	                        echo '<div id="quantitynone" style="display:block">Out of Stock.</div>';
	                        $variation_buttton_style = "display:block;";
	                        //echo '<div id="single_add_to_cart_button button" style="display:block"></div>';
	                }
	             ?>
		<div class="addtowihlist">
<!--                    <a href="#"><span class="wishlisticon"></span>-->
                        <?php
                if(function_exists(wpfp_link)){
                    wpfp_link();
                    }?>
<!--                                    </a>-->
                </div>
                </div>
	</div>
	<div><input type="hidden" name="product_id" value="<?php echo esc_attr( $post->ID ); ?>" /></div>

	<?php do_action('eshopbox_after_add_to_cart_button'); ?>

</form>

<?php do_action('eshopbox_after_add_to_cart_form'); ?>
