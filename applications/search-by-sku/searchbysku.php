<?php
/*
  Plugin Name: Search By SKU
  Description: This simple plugin adds this functionality of search any product by its SKU to both the admin site and regular search
  Author: <strong>Boxbeat Technologies Pvt Ltd</strong>
  Version: 1.0
  Author URI: http://www.theboxbeat.com/
 */

add_filter('the_posts', 'variation_query');
function variation_query($posts, $query = false) {
    if (is_search()) 
    {
        $ignoreIds = array(0);
        foreach($posts as $post)
        {
            $ignoreIds[] = $post->ID;
        }
        
        //get_search_query does sanitization
        $matchedSku = get_parent_post_by_sku(get_search_query(), $ignoreIds);
        
        if ($matchedSku) 
        {
            foreach($matchedSku as $product_id)
            {
                $posts[] = get_post($product_id->post_id);
                
            }

        }
        return $posts;
    }

    return $posts;
}

function get_parent_post_by_sku($sku, $ignoreIds) {
    //Check for 
    global $wpdb, $wp_query;
    
    $results = array();
    //Search for the sku of a variation and return the parent.
    $ignoreIdsForMySql = implode(",", $ignoreIds);
    $variations = $wpdb->get_results(
                    "
          SELECT p.post_parent as post_id FROM $wpdb->posts as p
          join $wpdb->postmeta pm
          on p.ID = pm.post_id
          WHERE meta_key='_sku'
          AND meta_value LIKE '%$sku%'
          AND post_parent <> 0
          and p.ID not in ($ignoreIdsForMySql)
          group by p.post_parent
          "
    );
    
    //var_dump($variations);die();
    foreach($variations as $post)
    {
        //var_dump($var);
        $ignoreIds[] = $post->post_id;
    }
    //If not variation try a regular product sku
    //Add the ids we just found to the ignore list...
    $ignoreIdsForMySql = implode(",", $ignoreIds);
    //var_dump($ignoreIds,$ignoreIdsForMySql);die();
    $regular_products = $wpdb->get_results(
        "SELECT post_id FROM $wpdb->posts as p
        join $wpdb->postmeta pm
        on p.ID = pm.post_id
        WHERE meta_key='_sku' 
        AND meta_value LIKE '%$sku%' 
        and (post_parent = 0 or post_parent is null)
        and p.ID not in ($ignoreIdsForMySql)
        group by p.ID

");
    
    $results = array_merge($variations, $regular_products);   
    $wp_query->found_posts += sizeof($results);  
    return $results;
}
?>