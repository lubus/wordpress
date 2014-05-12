<?php

function eshopbox_swatches_get_variation_form_args() {
    global $eshopbox, $product, $post;

    $attributes = $product->get_variation_attributes();
    $attributes_renamed = array();
    foreach ($attributes as $attribute => $values) {
        $attributes_renamed['attribute_' . sanitize_title($attribute)] = array_values($values);
    }

    $default_attributes = (array) maybe_unserialize(get_post_meta($post->ID, '_default_attributes', true));
    $selected_attributes = apply_filters('eshopbox_product_default_attributes', $default_attributes);

    // Put available variations into an array and put in a Javascript variable (JSON encoded)
    $available_variations = array();
    $available_variations_flat = array();

    foreach ($product->get_children() as $child_id) {

        $variation = $product->get_child($child_id);

        if ($variation instanceof WC_Product_Variation) {

            if (get_post_status($variation->get_variation_id()) != 'publish')
                continue; // Disabled

            if (!$variation->is_visible())
                continue; // Visible setting - may be hidden if out of stock

            $variation_attributes = $variation->get_variation_attributes();
            $available_variations_flat[] = $variation_attributes;
        }
    }

    return array(
        'available_variations' => $product->get_available_variations(),
        'available_variations_flat' => $available_variations_flat,
        'attributes' => $attributes,
        'attributes_renamed' => $attributes_renamed,
        'selected_attributes' 	=> $product->get_variation_default_attributes(),
    );
}
function eshop_get_post_id($post_name){
        global $wpdb;
	$qrypost = mysql_query("SELECT * FROM `phiver_posts` where post_name='$post_name'");
  	$fetchpost = mysql_fetch_array($qrypost);
 	$post_parent_id = $fetchpost['ID'];
return $post_parent_id;
}
// function for swatch join query used for page class-wc-swatch-term.php
function eshop_swatch_join($post_parent_id){
        global $wpdb;
	$qry = "SELECT * FROM `phiver_posts`,phiver_postmeta WHERE phiver_posts.ID = phiver_postmeta.post_id and phiver_postmeta.meta_key='attribute_pa_color' and phiver_posts.post_parent='$post_parent_id'";	
	return $result = mysql_query($qry);
}
function getquantity($productid){
    $args = array(
	'posts_per_page'  => 500,
	'numberposts'     => 500,
	'offset'          => 0,
	'category'        => '',
	'orderby'         => 'post_date',
	'order'           => 'DESC',
	'include'         => '',
	'exclude'         => '',
	'meta_key'        => '',
	'meta_value'      => '',
	'post_type'       => 'product_variation',
	'post_mime_type'  => '',
	'post_parent'     => $productid,
	'post_status'     => 'publish',
	'suppress_filters' => true ); 
    $value=get_posts($args);
    
    foreach($value as $key=>$postid){
        $postmetadetail=get_post_meta($postid->ID);
        //echo "<pre>";print_r($postmetadetail);echo "</pre>";
        $data.=$postmetadetail['attribute_pa_size'][0]."==".$postmetadetail['attribute_pa_color'][0]."==".$postmetadetail['_stock'][0]."***";
    }
    return $data;
}

?>
