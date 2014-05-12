<?php

global $product;
global $eshopbox;
global $wpdb;
$limit= 20;
$pagetitle=   $_SERVER['REQUEST_URI'];
$title= explode("/",$pagetitle);
$title=$title[1];
echo "<div class='entry-title'>My Wishlist</div>";
//$pagetitle=array_reverse($pagetitle);
//$title=$pagetitle[1];
//echo $title;
global $wp_query;
$post_data=$wp_query->queried_object;
$post_title=$post_data->post_title;
if($_POST['postval']=='1')
    {

    // if true
    //echo '<pre>';
    //print_r($_POST);
    $args = array
    (
        'post_parent'     => $_POST['product_id'],
        'post_type'       => 'product_variation',
        'meta_query' => array
        (
            'relation' => 'AND',
            array
            (
                'key' => 'attribute_pa_color',
                'value' => $_POST['var']['attribute_pa_color'],
            ),
            array
            (
                'key' => 'attribute_pa_size',
                'value' => $_POST['var']['attribute_pa_size'],
            )
        )
    );
    //echo"<pre>";print_r($args);
    $posts_array = get_posts( $args );

    $variation_id=$posts_array[0]->ID;
    $variation_detail=get_post_meta($variation_id);
    //echo"<pre>";print_r($_POST);exit;
   $variation_stock=$variation_detail['_stock'][0];
   $i=$_POST['ivalue'];
    //echo "scndas".$variation_id;exit;
   //$variation_stock=0;
    if($variation_stock>0)
    {
        $add= $eshopbox->cart->add_to_cart($_POST['product_id'],1,$variation_id,$_POST['var'],null);

    $remove=wpfp_do_remove_favorite($_POST['product_id']);
    $path=get_home_url().'/cart';
    // header('Location: http://getglamrl.com/cart');
    echo "<script type='text/javascript'>window.location ='".$path."'</script>";

    }
    else
        {
        //$add_to_bag='Not in stock';
    //echo"  <script type='text/javascript'>jQuery(.add_to_cart'".$i."').hide();</script>";
        $valuestock['stock'.$i]='true';

    }

    }

if(is_user_logged_in())
      {
        if ($favorite_post_ids):
                ?>
               <table class="shop_table cart" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="product-thumbnail">Image</th>
                            <th class="product-name">Product</th>
                             <th class="product-price">Price</th>
                             <th class="product-price">Availability</th>
                            <th class="product-remove">Action</th>
                        </tr>
                    </thead>
                     <tbody>
                        <?php
                              $c = 0;
                              $favorite_post_ids = array_reverse($favorite_post_ids);

                               $i=1;
                               //echo"<pre>";print_r($_SESSION);exit;
                              foreach ($favorite_post_ids as $key=>$post_id)
                                {
                                    //if ($c++ == $limit) break;

                                    $p = get_post($post_id);
                                    $meta_values = get_post_meta($post_id);

                                    $price=$meta_values['_price'][0];
                                    $style=$meta_values['_sku'][0];
                                    //echo "<pre>";print_r($meta_values);

                                    $allswatches=get_post_meta( $post_id,'_swatch_type_options');
                                    //echo $post_id."<pre>";print_r($allswatches);exit;
                                    $swatches=$allswatches[0];
                                    $allcolors=$swatches['pa_color']['attributes'];
                                    //echo"hello";exit;
                                    $allsizes=$swatches['pa_size']['attributes'];

                                    $availability=$meta_values['_stock_status'][0];
                                     ?>
                                    <tr class="cart_table_item">
                                    <!-- Remove from cart link -->
                                    <!-- The thumbnail -->
                                    <td class="product-thumbnail">
                                        <a href="<?php echo get_permalink($post_id)?>" title="<?php $p->post_title ?>" > <?php echo get_the_post_thumbnail($post_id,'shop_thumbnail') ?> </a>

                                    <!-- Product Name -->
                                    <td class="product-name">
                                        <a href="<?php echo get_permalink($post_id)?>" title="<?php $p->post_title ?>" > <?php echo $p->post_title ?> <br/> </a>

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

                                                      </td>
                                     <!-- Quantity inputs -->
                                    <!-- <td class="product-quantity">
                                        <div class="quantity buttons_added">
                                            <?php if($weight!= ''){echo "Weight: ".$weight."g\n";}?><br/>
                                           <?php if ($colorattr!='' ){echo "Color: ".substr($colorattr,0,-2)."\n"; }?><br/>
                                           <?php if($length!=''){ echo "Length: ".$length."\n"; }?></div>
                                                       </td> -->
                                    <!-- Product price -->
                                    <td class="product-price">
                                        <span class="amount">INR <?php echo $price; ?> </span>                       </td>
                                    <!-- Product availability -->
                                        <?php if($valuestock['stock'.($key+1)] !='true')
                                        {
                                            ?>
                                            <td class="stock"><?php echo $availability; ?></td>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                            <td class="stock"> out of stock</td>
                                            <?php
                                        }

                                        ?>


                                        <!--    remove block-->
                                    <td class="product-remove">
                                        <div class="block">
                                         <?php   echo $link="<a id='rem_$post_id'  class='$class' href='?wpfpaction=remove&amp;page=1&amp;postid=". $post_id ."' title='".wpfp_get_option('rem')."' rel='nofollow' ><span class='remove_item'></span>Remove item</a>";   ?>
                                         </div>
                                          <div class="block">
                                <form action="" method="post">

                            <input type="hidden" name="product_id" value="<?php echo $post_id ; ?>" />
                            <input type="hidden" name="postval" id="postval" value="1"/>
                             <input type="hidden" name="ivalue" id="ivalue<?php echo $i;?>" value="<?php echo $i;?>"/>
                            <input type="hidden" name="var[attribute_pa_color]" id="attribute_pa_color<?php echo $i;  ?>" value="<?php echo $defaultColor;  ?>"/>
                            <input type="hidden" name="var[attribute_pa_size]" id="attribute_pa_size<?php echo $i;  ?>" value="<?php echo $defaultSize;  ?>"/>

                            <div class="movetowishlist_btn"><button type="submit" class="add_to_cart<?php echo $i;  ?> move_to_cart" rel="<?php echo $i; ?>"><span class='remove_bag'></span>Add to bag</button></div>
                           </form>
                                         <?php  // echo $link="<a id='rem_$post_id'  class='$class' href='?wpfpaction=remove&amp;page=1&amp;postid=". $post_id ."' title='".wpfp_get_option('rem')."' rel='nofollow' ><span class='remove_bag'></span>Add to bag</a>";   ?>
                                         </div>
                                    </td>
                                </tr>
                                    <?php
                                          $colorattr='';
                                     // $_SESSION['stock_value'.$i]= '';

                                     $i++;
                          // echo"<pre>";print_r($_SESSION);
                            }

        else:
                    $path=get_home_url();
                    echo "<li class='register_empty_cart'>";
                    echo "Your Wishlist is empty  . "."<a href=".$path.">Continue Shopping</a>"."  to add your favourites to your Wishlist.";
                    echo "</li>";
                endif;


    }
    else

     {
                $path=get_home_url().'/my-account';
                wp_redirect(get_permalink('234'));


//                  echo "<ul class='new_user'>";
//                    echo "<li>";
//                    echo "<a href=".$path."> Please Login to add your Favourites to your wishlist</a>";
//                    echo "</li>";
//
//                echo "</ul>";

    }
?>
            </tbody>
</table>

