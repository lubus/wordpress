<?php
/**
 * Single Product Price, including microdata for SEO
 *
 * @author 		WooThemes
 * @package 	EshopBox/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product;
?>
<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">

	<p itemprop="price" class="price"><?php echo $product->get_price_html(); ?> 
		<span class="free_shiiping">
			<?php if(COUNTRY_IN_SESSION == 'IN'){
				echo 'Plus FREE Shipping';
			}else{
				echo 'Plus Shipping';
			}
			?>
		</span>
	</p>

	<meta itemprop="priceCurrency" content="<?php echo get_eshopbox_currency(); ?>" />
	<link itemprop="availability" href="http://schema.org/<?php echo $product->is_in_stock() ? 'InStock' : 'OutOfStock'; ?>" />

</div>
<?php
$dimension = eshopbox_get_product_terms($product->id, 'pa_dimension', 'names');//print_r($dimension);
if($dimension != NULL){
        foreach($dimension as $key=>$val){
?>
<div class="pro_dimension">Product Dimension : <span>
<?php   
       echo $val;
?>
</span></div>
<?php
        }
}
?>

