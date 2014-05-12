<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/eshopbox/content-product.php
 *
 * @author 		WooThemes
 * @package 	EshopBox/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $eshopbox_loop, $post;

// Store loop count we're currently on
if ( empty( $eshopbox_loop['loop'] ) )
	$eshopbox_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $eshopbox_loop['columns'] ) )
	$eshopbox_loop['columns'] = apply_filters( 'loop_shop_columns', 3 );

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;

// Increase loop count
$eshopbox_loop['loop']++;

// Extra post classes
$classes = array();
if ( 0 == ( $eshopbox_loop['loop'] - 1 ) % $eshopbox_loop['columns'] || 1 == $eshopbox_loop['columns'] )
	$classes[] = 'first';
if ( 0 == $eshopbox_loop['loop'] % $eshopbox_loop['columns'] )
	$classes[] = 'last';
?>
<li <?php post_class( $classes ); ?>>
<div class="li_cover">
	<?php do_action( 'eshopbox_before_shop_loop_item' ); ?>

<?php 
$check=0;
$sale=0;
$terms = wp_get_post_terms($post->ID,'product_cat',array('fields'=>'all'));?>
<?php foreach($terms as $key=>$value){
	if($value->term_id==17)
		$check=1;
	if($value->term_id==24)
		$sale=1;
}
$uri=explode('/',$_SERVER['REQUEST_URI']);
//echo $check."=====".$uri[1];
//if($_SERVER['REMOTE_ADDR'] == '182.68.236.9'){

    $post_stock = get_post_meta($product->id,'_stock');
    if($post_stock[0] == ''){
        $class_stck = "out_stock_img";
    }
    
//}
?>
<?php if($check==1 && $uri[2]!='new-in' && $post_stock[0] != '') {?>
<!--<div class="sale_cls">Sale</div>-->
<div class="new_cls">New</div>
<?php }
if($sale==1 && $uri[2]!='sale' && $post_stock[0] != ''){?>
	<div class="sale_class">Sale</div>
<?php }?>
                <a href="<?php the_permalink(); ?>" id="thumb_<?php echo $product->id; ?>" class="<?php echo $class_stck; ?>">
		<?php
			/**
			 * eshopbox_before_shop_loop_item_title hook
			 *
			 * @hooked eshopbox_show_product_loop_sale_flash - 10
			 * @hooked eshopbox_template_loop_product_thumbnail - 10
			 */
			do_action( 'eshopbox_before_shop_loop_item_title' );
		?>
                </a>
		<a href="<?php the_permalink(); ?>"><h3><?php the_title(); ?></h3>
                <?php echo apply_filters( 'eshopbox_short_description', $post->post_excerpt ) ?>
        </a>


		<?php
			/**
			 * eshopbox_after_shop_loop_item_title hook
			 *
			 * @hooked eshopbox_template_loop_price - 10
			 */
			do_action( 'eshopbox_after_shop_loop_item_title' );
		?>

	
<?php if(is_archive()) {

$color_attrs = eshopbox_get_product_terms($product->id, 'pa_color', 'names');
$count = count($color_attrs);
if($count > 1){
?>    
<!--<div class="color_text">+ <?php //echo $count; ?> More Colors </div>-->
<?php
}
?>
<!--<div class="color_swath">
<!--<a href="#"></a>
<a href="#"></a>
<?php //if(!is_single()){$swatch_img = eshop_get_swatch_images_list($product->id);} ?>
</div>-->
<?php } 

if($post_stock[0] == ''){
        echo "<span class='out_stock'>sold out</span>";
    }

?>
	<?php do_action( 'eshopbox_after_shop_loop_item' ); ?>
	
</div>
</li>
