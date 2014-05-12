<?php
/**
 * Single product short description
 *
 * @author 		WooThemes
 * @package 	EshopBox/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post;

if ( ! $post->post_excerpt ) return;
?>
<!--<div itemprop="description">
	<?php //echo apply_filters( 'eshopbox_short_description', $post->post_excerpt ) ?>
</div>-->