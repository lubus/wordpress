<?php /*Template Name:Home Template */ ?>
<?php get_header (); ?>

<div class="home_container">
  <div class="banner_div">
    <?php dynamic_sidebar ('banner_home'); ?>
  </div>
<ul class="box white three specials clear">
 <li class="cover"> <a href="/product-category/sale/"> <img src="http://speed.eshopbox.com/phiveriverc6069967bc60284e457a9fe095ba49/save-40-phiverivers.jpg" width="303" height="348" alt=""> </a> </li>
    <li class="cover"> <a href="/product-tag/luxury-collection/"> <img src="http://speed.eshopbox.com/phiveriverc6069967bc60284e457a9fe095ba49/luxury-phiverivers.jpg" width="303" height="348" alt=""> </a> </li>
    <li class="cover"> <a href="/product-tag/signature-collection/"> <img src="http://speed.eshopbox.com/phiveriverc6069967bc60284e457a9fe095ba49/Smaill-Banner.jpg" width="303" height="348" alt=""> </a> </li>
</ul>
   <div class="box white white1 space products clear">
    <article>
      <h6> What's Hot </h6>
      <h2> Newest customer trends </h2>
    </article>
    <?php
					$args = array( 'post_type' => 'product', 'taxonomy' => 'product_cat', 'term'=>'satchels', 'orderby'=>'id', 'order'=>'DESC', 'posts_per_page'=>'4', 'meta_value' => 'yes', 'meta_key' => '_featured', 'orderby' => 'rand');
					$the_query = new WP_Query( $args );
					//echo "<pre>";print_r($the_query);echo "</pre>";
				?>
            	<ul class="clear">
                 <?php                                                                             
					$mencat_arr = array();
					$count=0;
					while ($the_query->have_posts()) : $the_query->the_post();$count++;
					$id = get_the_ID();
					$mencat_arr[] = $id;
					
					$classvalue=$count==4?'lastone':'';
					?>
                	<li class="first  mpo_post_title item <?php echo $classvalue;?>">
                    <div class="wrap">
                    	<?php do_action('woocommerce_before_shop_loop_item'); ?>
                    	
                        	<?php if($count_total_stock == 0){ ?>
								 <a href="<?php the_permalink(); ?>" id="<?php echo $post->ID; ?>" title="<?php the_title(); ?>"><?php echo get_the_post_thumbnail( $post->ID, 'shop_catalog' ); ?> </a>
                            <?php }else{ ?>
                                <a href="<?php the_permalink(); ?>" id="<?php echo $post->ID; ?>" title="<?php the_title(); ?>">
                                    <?php //echo get_the_post_thumbnail( $post->ID, 'swatches_image_size' ); ?>
                                    <?php //echo $home_img_size = get_the_post_thumbnail( $post->ID, 'shop_single' ); 
                                                               
                                    ?>
                                </a>
                            <?php } ?>
                       <p> <em class="new"> New </em> <a href="<?php the_permalink(); ?>"><?php the_title() ?> </a> <span> <?php echo $product->get_price_html(); ?> </span> </p>
                        

                        </div>
                    </li>
                   
                    <?php endwhile; ?>
                </ul>
        		   <?php  wp_reset_query(); // Remember to reset ?>
    
    <?php
					$args = array( 'post_type' => 'product', 'taxonomy' => 'product_cat', 'term'=>'hobo', 'orderby'=>'id', 'order'=>'DESC', 'posts_per_page'=>'4', 'meta_value' => 'yes', 'meta_key' => '_featured', 'orderby' => 'rand');
					$the_query = new WP_Query( $args );
					//echo "<pre>";print_r($the_query);echo "</pre>";
				?>
            	<ul class="clear" id="hidden-products" style="overflow: hidden; display: none;">
                 <?php                                                                             
					$mencat_arr = array();
					$count=0;
					while ($the_query->have_posts()) : $the_query->the_post();$count++;
					$id = get_the_ID();
					$mencat_arr[] = $id;
					
					$classvalue=$count==4?'lastone':'';
					?>
                	<li class="first  mpo_post_title item <?php echo $classvalue;?>">
                    <div class="wrap">
                    	<?php do_action('woocommerce_before_shop_loop_item'); ?>
                    	
                        	<?php if($count_total_stock == 0){ ?>
								 <a href="<?php the_permalink(); ?>" id="<?php echo $post->ID; ?>" title="<?php the_title(); ?>"><?php echo get_the_post_thumbnail( $post->ID, 'shop_catalog' ); ?> </a>
                            <?php }else{ ?>
                                <a href="<?php the_permalink(); ?>" id="<?php echo $post->ID; ?>" title="<?php the_title(); ?>">
                                    <?php //echo get_the_post_thumbnail( $post->ID, 'swatches_image_size' ); ?>
                                    <?php //echo $home_img_size = get_the_post_thumbnail( $post->ID, 'shop_single' ); 
                                                               
                                    ?>
                                </a>
                            <?php } ?>
                       <p> <em class="new"> New </em> <a href="<?php the_permalink(); ?>"><?php the_title() ?> </a> <span> <?php echo $product->get_price_html(); ?> </span> </p>
                        

                        </div>
                    </li>
                   
                    <?php endwhile; ?>
                </ul>
        		   <?php  wp_reset_query(); // Remember to reset ?>
      
      <?php
					$args = array( 'post_type' => 'product', 'taxonomy' => 'product_cat', 'term'=>'hobo', 'orderby'=>'id', 'order'=>'DESC', 'posts_per_page'=>'4', 'meta_value' => 'yes', 'meta_key' => '_featured', 'orderby' => 'rand');
					$the_query = new WP_Query( $args );
					//echo "<pre>";print_r($the_query);echo "</pre>";
				?>
            	<ul class="clear second_featured_prod" style="display:none;">
                 <?php                                                                             
					$mencat_arr = array();
					$count=0;
					while ($the_query->have_posts()) : $the_query->the_post();$count++;
					$id = get_the_ID();
					$mencat_arr[] = $id;
					
					$classvalue=$count==4?'lastone':'';
					?>
                	<li class="first  mpo_post_title item <?php echo $classvalue;?>">
                    <div class="wrap">
                    	<?php do_action('woocommerce_before_shop_loop_item'); ?>
                    	
                        	<?php if($count_total_stock == 0){ ?>
								 <a href="<?php the_permalink(); ?>" id="<?php echo $post->ID; ?>" title="<?php the_title(); ?>"><?php echo get_the_post_thumbnail( $post->ID, 'shop_catalog' ); ?> </a>
                            <?php }else{ ?>
                                <a href="<?php the_permalink(); ?>" id="<?php echo $post->ID; ?>" title="<?php the_title(); ?>">
                                    <?php //echo get_the_post_thumbnail( $post->ID, 'swatches_image_size' ); ?>
                                    <?php //echo $home_img_size = get_the_post_thumbnail( $post->ID, 'shop_single' ); 
                                                               
                                    ?>
                                </a>
                            <?php } ?>
                       <p> <em class="new"> New </em> <a href="<?php the_permalink(); ?>"><?php the_title() ?> </a> <span> <?php echo $product->get_price_html(); ?> </span> </p>
                        

                        </div>
                    </li>
                   
                    <?php endwhile; ?>
                </ul>
        		   <?php  wp_reset_query(); // Remember to reset ?>
    
    <?php
					$args = array( 'post_type' => 'product', 'taxonomy' => 'product_cat', 'term'=>'hobo', 'orderby'=>'id', 'order'=>'DESC', 'posts_per_page'=>'4', 'meta_value' => 'yes', 'meta_key' => '_featured', 'orderby' => 'rand');
					$the_query = new WP_Query( $args );
					//echo "<pre>";print_r($the_query);echo "</pre>";
				?>
            	<ul class="clear" id="hidden-products" style="overflow: hidden; display: none;">
                 <?php                                                                             
					$mencat_arr = array();
					$count=0;
					while ($the_query->have_posts()) : $the_query->the_post();$count++;
					$id = get_the_ID();
					$mencat_arr[] = $id;
					
					$classvalue=$count==4?'lastone':'';
					?>
                	<li class="first  mpo_post_title item <?php echo $classvalue;?>">
                    <div class="wrap">
                    	<?php do_action('woocommerce_before_shop_loop_item'); ?>
                    	
                        	<?php if($count_total_stock == 0){ ?>
								 <a href="<?php the_permalink(); ?>" id="<?php echo $post->ID; ?>" title="<?php the_title(); ?>"><?php echo get_the_post_thumbnail( $post->ID, 'shop_catalog' ); ?> </a>
                            <?php }else{ ?>
                                <a href="<?php the_permalink(); ?>" id="<?php echo $post->ID; ?>" title="<?php the_title(); ?>">
                                    <?php //echo get_the_post_thumbnail( $post->ID, 'swatches_image_size' ); ?>
                                    <?php //echo $home_img_size = get_the_post_thumbnail( $post->ID, 'shop_single' ); 
                                                               
                                    ?>
                                </a>
                            <?php } ?>
                       <p> <em class="new"> New </em> <a href="<?php the_permalink(); ?>"><?php the_title() ?> </a> <span> <?php echo $product->get_price_html(); ?> </span> </p>
                        

                        </div>
                    </li>
                   
                    <?php endwhile; ?>
                </ul>
        		   <?php  wp_reset_query(); // Remember to reset ?>
      
    
    <a href="javascript://bet" class="expand closed" data-hide="#hidden-products"> <span class="more less_up">View more</span><span class="less">View less</span> </a> </div>
   <div class="box white white2"> 
    <div class="title_news">    <h2>Phive Rivers NewsLetter</h2>    </div>
    <div class="newsletter">
    <?php dynamic_sidebar('newsletter'); ?>
    
    </div>
    </div>
</div>
<?php get_footer (); ?>

<?php
					$args = array( 'post_type' => 'product', 'taxonomy' => 'product_cat', 'term'=>'sandals', 'orderby'=>'id', 'order'=>'DESC', 'posts_per_page'=>'4', 'meta_value' => 'yes', 'meta_key' => '_featured', 'orderby' => 'rand');
					$the_query = new WP_Query( $args );
					//echo "<pre>";print_r($the_query);echo "</pre>";
				?>
            	<ul class="clear">
                 <?php                                                                             
					$mencat_arr = array();
					$count=0;
					while ($the_query->have_posts()) : $the_query->the_post();$count++;
					$id = get_the_ID();
					$mencat_arr[] = $id;
					
					$classvalue=$count==4?'lastone':'';
					?>
                	<li class="first  mpo_post_title item <?php echo $classvalue;?>">
                    <div class="wrap">
                    	<?php do_action('woocommerce_before_shop_loop_item'); ?>
                    	
                        	<?php if($count_total_stock == 0){ ?>
								 <a href="<?php the_permalink(); ?>" id="<?php echo $post->ID; ?>" title="<?php the_title(); ?>"><?php echo get_the_post_thumbnail( $post->ID, 'shop_catalog' ); ?> </a>
                            <?php }else{ ?>
                                <a href="<?php the_permalink(); ?>" id="<?php echo $post->ID; ?>" title="<?php the_title(); ?>">
                                    <?php //echo get_the_post_thumbnail( $post->ID, 'swatches_image_size' ); ?>
                                    <?php //echo $home_img_size = get_the_post_thumbnail( $post->ID, 'shop_single' ); 
                                                               
                                    ?>
                                </a>
                            <?php } ?>
                       <p> <em class="new"> New </em> <a href="<?php the_permalink(); ?>"><?php the_title() ?> </a> <span> <?php echo $product->get_price_html(); ?> </span> </p>
                        

                        </div>
                    </li>
                   
                    <?php endwhile; ?>
                </ul>
        		   <?php  wp_reset_query(); // Remember to reset ?>