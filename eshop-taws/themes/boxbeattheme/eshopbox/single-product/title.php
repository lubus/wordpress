<?php
/**
 * Single Product title
 *
 * @author 		WooThemes
 * @package 	EshopBox/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $post, $product;
?>
<h1 itemprop="name" class="product_title entry-title"><?php the_title(); ?></h1>
<div class="short_des1">
<?php 
global $post;
echo apply_filters( 'eshopbox_short_description', $post->post_excerpt ) ?>
</div>
<div class="sku_div"><?php _e( 'Style:', 'eshopbox' ); ?>  <?php echo $product->get_sku(); ?></div>
<div class="next_prev">
<!--<span class="prev" title="Previous"><a href="#" rel="prev" title="PREVIOUS"> PREV</a></span>
<span class="next" title="Next"><a href="#" rel="next" title="NEXT"> NEXT</a></span>-->
<span class="prev" title="Previous"><?php previous_post_link_product('%link', 'PREVIOUS', true);?></span>
<span class="next" title="Next"><?php next_post_link_product('%link', 'NEXT', true); ?></span>
</div>


