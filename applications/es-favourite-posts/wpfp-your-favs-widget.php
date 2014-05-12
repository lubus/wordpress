<?php

global $product;
global $eshopbox;
global $wpdb;
$limit= 20;
$pagetitle=   $_SERVER['REQUEST_URI'];
$title= explode("/",$pagetitle);
$title=$title[1];
//echo "<div class='entry-title'>My Wishlist</div>";
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
     if ($favorite_post_ids){
    $wishcount=count($favorite_post_ids);
     }else { $wishcount=0;}
     if( !sizeof( $eshopbox->cart->get_cart() ) > 0 )
        {
        $nowishnobag= 'style="width:1000px; margin-right:0px; border-right:0px none;"';
        }

        if( !empty($favorite_post_ids) && !sizeof( $eshopbox->cart->get_cart() ) > 0 )
        {
        $nobag= 'style="width:890px"';
        }
        ?>
<h1 class="entry-title"><?php global $wp_query; echo $wp_query->query['pagename']; ?></h1>
<?php if ( ! dynamic_sidebar( 'headingbottommenu' )) : ?><?php endif; ?>

    <div id="saveditemblock">
    <div class="mainblock">
        <div class="leftblock"  <?php  echo $nowishnobag; ?> >
            <div class="headingtitle">My saved items (<?php  echo $wishcount; ?>)</div>
            <div class="contentblock"  >
    <?php
        if ($favorite_post_ids):
                ?>               
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
                                    $allsizes = eshopbox_get_product_terms($post_id, 'pa_size', 'names');
                                     $sku=$meta_values['_sku'][0];
                                    $availability=$meta_values['_stock_status'][0];
                                     ?>
                         <ul>
                	<li>
                    	<div class="imageblock">
                            <a href="<?php echo get_permalink($post_id)?>" title="<?php $p->post_title ?>" > <?php echo get_the_post_thumbnail($post_id,'shop_thumbnail') ?> </a>
                        </div>
                        <div class="wishlistcontent" <?php echo $nobag;?> >
                        	<div class="pull-left">
                    		<div class="title"><a href="<?php echo get_permalink($post_id)?>" title="<?php $p->post_title ?>" > <?php echo $p->post_title ?></a></div>
                            <div class="stylecode"><?php if($sku){ echo "Style Code : ". $sku; }?></div>
                             <div class="pricecode">INR  <?php echo $price; ?></div>
                             <?php if(!empty($allsizes))
                             {?>
                             <div class="selectsize_block">
                                                <span class="title">Select Size</span>
                                                <span>
                                                    <select id="pro_size" class="csize" rel="<?php echo $i;  ?>">
                                                        <?php
                                                        $defaultSize = $allsizes[0];
                                                        $f=0; foreach($allsizes as $size=>$sizevalue)
                                                        { if($f==0){
                                                        $defaultSize = $sizevalue;
                                                        }
                                                        $f++;
                                                        ?>
                                                        <option><?php echo $sizevalue ?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </span>
                                            </div>

                              <!-- Product availability -->
                                        <?php
                             }if($valuestock['stock'.($key+1)] !='true')
                                        {  $finalavailability=$availability;




                                        }
                                        else
                                        {  $finalavailability='out of stock';
//                                            ?>
<!--                                            <td class="stock"> out of stock</td>-->
                                            //<?php
                                        }

                                        ?>
                             </div>
                             <div class="pull-right">
                        	<div class="linkcontainer">
                            	<div class="buttonblock">
                                     <?php   echo $link="<a id='rem_$post_id'  class='$class remove' href='?wpfpaction=remove&amp;page=1&amp;postid=". $post_id ."' title='".wpfp_get_option('rem')."' rel='nofollow' ><span class='can'></span> <span class='lid'></span>Remove from wishlist</a>";   ?>
<!--                            		<a href="#" class="remove"><span></span>Remove from wishlist</a>-->
                                </div>
                                <div class="buttonblock">
<!--                                <a href="#" class="addtowishlist">Add to Shopping Bag</a>-->
                              <form action="" method="post">

                            <input type="hidden" name="product_id" value="<?php echo $post_id ; ?>" />
                            <input type="hidden" name="postval" id="postval" value="1"/>
                             <input type="hidden" name="ivalue" id="ivalue<?php echo $i;?>" value="<?php echo $i;?>"/>
                            <input type="hidden" name="var[attribute_pa_color]" id="attribute_pa_color<?php echo $i;  ?>" value="<?php echo $defaultColor;  ?>"/>
                            <input type="hidden" name="var[attribute_pa_size]" id="attribute_pa_size<?php echo $i;  ?>" value="<?php echo $defaultSize;  ?>"/>
                            <?php if($finalavailability !='out of stock'){?>
                            <div class="movetowishlist_btn"><button type="submit" class="add_to_cart<?php echo $i;  ?> move_to_cart" rel="<?php echo $i; ?>"><span class='remove_bag'></span>Add to Shopping Bag</button></div>
                           <?php }
                           else
                               {
                                ?><div>Out of Stock </div><?php
                               }?>
                              </form>
                                </div>
                            </div>
                            </div>
                        </div>
                    </li>
                   
                 </ul>
                                                 
                                       
                                    <?php
                                          

                                     $i++;
                         
                            }

        else:
//                    $path=get_home_url();
//                    echo "<li class='register_empty_cart'>";
//                    echo "Your Wishlist is empty  . "."<a href=".$path.">Continue Shopping</a>"."  to add your favourites to your Wishlist.";
//                    echo "</li>";


                        ?>
                        <div class="emptymessage">You currently have no saved items.</div>
                        <?php
                        endif;

        ?>
                         <div class="continueshoppping_link"><a href="<?php echo get_home_url(); ?>">Continue shopping</a></div>
                        </div>
                     </div>
        </div>

          <?php if ( sizeof( $eshopbox->cart->get_cart() ) > 0 ) { ?>
        <div class="rightblock">
            <div class="headingtitle">Shopping bag (<?php echo sizeof( $eshopbox->cart->get_cart() ); ?>)</div>
            <div class="contentblock">
              
<?php

                    foreach ( $eshopbox->cart->get_cart() as $cart_item_key => $values ) {

                        $_product = $values['data'];

                        ?>
        		<ul>
                	<li>
                    	<div class="imageblock">

                            <?php
                                $thumbnail = apply_filters( 'eshopbox_in_cart_product_thumbnail', $_product->get_image(), $values, $cart_item_key );
                                if ( ! $_product->is_visible() || ( ! empty( $_product->variation_id ) && ! $_product->parent_is_visible() ) )
                                echo $thumbnail;
                                else
                                printf('<a href="%s">%s</a>', esc_url( get_permalink( apply_filters('eshopbox_in_cart_product_id', $values['product_id'] ) ) ), $thumbnail );
                            ?>
                        </div>
                        <div class="wishlistcontent">
                    		<div class="title">
                                    <?php
								if ( ! $_product->is_visible() || ( ! empty( $_product->variation_id ) && ! $_product->parent_is_visible() ) )
									echo apply_filters( 'eshopbox_in_cart_product_title', $_product->get_title(), $values, $cart_item_key );
								else
									printf('<a href="%s">%s</a>', esc_url( get_permalink( apply_filters('eshopbox_in_cart_product_id', $values['product_id'] ) ) ), apply_filters('eshopbox_in_cart_product_title', $_product->get_title(), $values, $cart_item_key ) );


                   				// Backorder notification
                   				if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $values['quantity'] ) )
                   					echo '<p class="backorder_notification">' . __( 'Available on backorder', 'eshopbox' ) . '</p>';
							?>
                                </div>
                            <div class="stylecode">Style Code : <?php echo $_product->get_sku(); ?></div>
                             <div class="pricecode">
                                 <?php
								$product_price = get_option('eshopbox_tax_display_cart') == 'excl' ? $_product->get_price_excluding_tax() : $_product->get_price_including_tax();

								echo apply_filters('eshopbox_cart_item_price_html', eshopbox_price( $product_price ), $values, $cart_item_key );
							?>
                             </div>
                        	<div class="linkcontainer">
                            	<div class="buttonblock">
<!--                                <a href="#" class="addtowishlist"><span class="beforeadd"></span>Add to wishlist</a>-->
                                    <span class="wishlist">
                                <?php
                                                            $_SESSION['post_id']=$_product->post->ID;
                                                            $_SESSION['cart_key']=$cart_item_key;
                                                            if(function_exists(wpfp_link)){
                                                            wpfp_link();
                                                            }
                                                        ?>
                                    </span>
                                </div>
                                <div class="buttonblock">
<!--                            		<<a href="#" class="remove"><span></span>Remove from wishlist</a>-->
                                   <span class="remove"> <?php
								echo apply_filters( 'eshopbox_cart_item_remove_link', sprintf('<a href="%s" class="remove" title="%s"><span class="can"></span> <span class="lid"></span>Remove from bag</a>', esc_url( $eshopbox->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'eshopbox' ) ), $cart_item_key );
							?>
                                   </span>
                                </div>
                            </div>
                        </div>
                    </li>

                </ul>

                <?php
                    } ?>
              </div>
                    </div>

                    <?php }
               ?>
    		
        
    </div> <?php
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
            


	
    	