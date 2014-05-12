<?php
ob_start();
	global $product;
	global $eshopbox;
	$limit=20;
	global $wpdb;
	$pagetitle=$_SERVER['REQUEST_URI'];
	$title= explode("/",$pagetitle);
	$title=$title[1];
        global $wp_query;
 $post_data=$wp_query->queried_object;
 $post_title=$post_data->post_title;

        if($_POST['postval']=='1'){
            // if true
            //echo '<pre>';
            //print_r($_POST);
            $args = array(
                                'post_parent'     => $_POST['product_id'],
                                'post_type'       => 'product_variation',
                                'meta_query' => array(
                                                                'relation' => 'AND',
                                                        array(
                                                                'key' => 'attribute_pa_color',
                                                                'value' => $_POST['var']['attribute_pa_color'],
                                                        ),
                                                        array(
                                                                'key' => 'attribute_pa_size',
                                                                'value' => $_POST['var']['attribute_pa_size'],
                                                        )
                                                     )
                                 );
//echo"<pre>";print_r($args);
                    $posts_array = get_posts( $args );
//echo"pre";print_r($posts_array);
                    $variation_id=$posts_array[0]->ID;
//echo "scndas".$variation_id;exit;

           $add= $eshopbox->cart->add_to_cart($_POST['product_id'],1,$variation_id,$_POST['var'],null);
           $remove=wpfp_do_remove_favorite($_POST['product_id']);
           $path=get_home_url().'/cart';
          // header('Location: http://getglamrl.com/cart');
          echo "<script type='text/javascript'>window.location ='".$path."'</script>";
        }
?>


<div class="wishlist_title"><?php echo  $post_title; ?></div>
<?php
if(is_user_logged_in())
{
    if ($favorite_post_ids)
    {
        ?>
<!--        <div class="wishlist_wishlist">Following items are added into your Wishlist</div>-->
        <ul class="products overview">
         <?php
                $c = 0;
                $favorite_post_ids = array_reverse($favorite_post_ids);
                $i=1;
                foreach ($favorite_post_ids as $post_id)
                {
                    $allswatches=get_post_meta( $post_id,'_swatch_type_options');
                    $swatches=$allswatches[0];
                    $allcolors=$swatches[pa_color][attributes];
                    $allsizes=$swatches[pa_size][attributes];
                    //if ($c++ == $limit) break;
                    $p = get_post($post_id);
                    $meta_values = get_post_meta($post_id);
                    $price=$meta_values['_price'][0];
                    $style=$meta_values['_sku'][0];
                             ?>
                    <li class="product">
                        
                            <div  classstyle="display:inline-block; width:100%; margin:10px 0;">
                                <?php echo $link="<a href='?wpfpaction=remove&amp;page=1&amp;postid=". $post_id ."' title='".wpfp_get_option('rem')."' rel='nofollow' class='removelink'>x</a>";   ?>
                            </div>
                            <div class="imageblock" id="default-<?php echo $product->id; ?>">
                                <a href="<?php echo get_permalink($post_id)?>" title="<?php $p->post_title ?>" > <?php echo get_the_post_thumbnail($post_id,'shop_catalog') ?> </a>
                            </div>
                            <h3> <a href="<?php echo get_permalink($post_id)?>" title="<?php $p->post_title ?>" > <?php echo $p->post_title ?> <p class="italic_text"><?php //echo "style: ".$style."\n"; ?></p></a></h3>
                            <span class="price">
                                <span class="amount">INR <?php echo $price; ?> </span>
                            </span>
                            <div class="selectcolor_block">
                                <span class="title">Select Color</span><span>
<?php				//echo"<pre>";print_r($allcolors);exit;

					$defaultColor = $allcolors[0];
                                    $defaultSize = $allsizes[0];

                                    ?>
                                <select id="pro_color" class="ccolor" rel="<?php echo $i;  ?>">
                                    <?php $k=0; foreach($allcolors as $color=>$value)
                                    {
					if($k==0){
                                    	$defaultColor = $color;
					}
					$k++;
                                        ?>
                                    <option><?php echo $color ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                </span>
                            </div>
                            <div class="selectsize_block">
                                <span class="title">Select Size</span>
                                <span>
                                    <select id="pro_size" class="csize" rel="<?php echo $i;  ?>">
                                        <?php $f=0; foreach($allsizes as $size=>$sizevalue)
                                        { if($f==0){
                                    	$defaultSize = $size;
					}
					$f++;
                                        ?>
                                        <option><?php echo $size ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </span>
                            </div>
                          <form action="" method="post">

                            <input type="hidden" name="product_id" value="<?php echo $post_id ; ?>" />
                            <input type="hidden" name="postval" id="postval" value="1"/>
                            <input type="hidden" name="var[attribute_pa_color]" id="attribute_pa_color<?php echo $i;  ?>" value="<?php echo $defaultColor;  ?>"/>
                            <input type="hidden" name="var[attribute_pa_size]" id="attribute_pa_size<?php echo $i;  ?>" value="<?php echo $defaultSize;  ?>"/>
                            <div class="movetowishlist_btn"><button type="submit" class="add_to_cart"></button></div>
                            </form>
                     </li>

                     <?php
                        $i++;
                }

    }
    else
    {
        $path=get_home_url();
        echo "<li class='register_empty_cart'>";?>
                     <div id="emptycart">
        <p class="title_text" style="font:bolder; font-size: large"> You haven’t saved any item yet</p>
<p class="title_content">Save your must-have products here to revisit later. When you're ready to buy, add items directly to your cart</p>

                </div>
<!--                <p><a class="button" href="<?php //echo get_permalink(eshopbox_get_page_id('shop')); ?>"><?php //_e( 'Continue Shopping', 'eshopbox' ) ?></a></p>-->
                <?php
        //echo 'You haven’t saved any item yet.';
        //echo "Your Wishlist is empty  . "."<a href=".$path.">Continue Shopping</a>"."  to add your favourites to your Wishlist.";
        echo "</li>";

    }
}
else
{
    $path=get_home_url().'/my-account';
    echo "<ul class='new_user'>";
    echo "<li>";
    echo "<a href=".$path."> Please Login to add your Favourites to your Wishlist</a>";
    echo "</li>";
    echo "</ul>";
}
 ?></ul>
