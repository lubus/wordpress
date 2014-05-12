<?php require_once($_GET['abspath'].'/wp-load.php'); ?>

<?php
$variations = explode("~", $_GET['variations']);
$variations = array_filter($variations);

$cleanvariations = array();
foreach($variations as $variation) {
	$keyvalue = explode("__",$variation);
	$cleanvariations[$keyvalue[0]] = sanitize_title($keyvalue[1]);
}
//print_r($cleanvariations);
?>

<?php
$args = array(
	'post_type' => 'attachment',
	'posts_per_page' => -1,
	'post_status' => 'inherit', 
	'post_parent' => $_GET['postid'],
	'orderby' => 'menu_order',
	'order' => 'ASC'
);

$the_query = new WP_Query( $args );
if($the_query->have_posts()) { ?>

	<?php $imgs = array(); // A blank array to hold all the images we find ?>

	<?php $i = 0; while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
	
		<?php
		
		$chosen = get_post_meta( $post->ID, 'be_variations', true );
		
		//print_r($chosen);
		
		if($chosen) { // If our image has any variation data applied to it
		
			$showimg = array(); // Create blank array
			
			foreach($cleanvariations as $key => $val) {
				$showimg[] = $jck_woo_variations->comparearrs($chosen, $key, $val); // compare the values in my arrays, assign true/false
			}
			
			if(!in_array(0, $showimg)){ // if the new array contains a false, don't show the img
				$imgs[$i] = $post->ID;
			}
		
		} else { // If our image has no variation data (show for all variations)
		
			$imgs['100'.$i] = $post->ID; // Set images with no variation data to show at the end of our array
			
		}
		
		?>
	
	<?php $i++; endwhile; ?>
	
	<?php ksort($imgs); // Sort our $imgs array by key, so that images set to show for all variations are at the end ?>
	
	<?php
		$firstImage = array_shift($imgs);
	?>
	
<?php } /*End if(posts) */ wp_reset_postdata(); ?>
   
   
<div id="newImages">

	<?php if(isset($firstImage) && $firstImage) { ?>
		
		<a itemprop="image" href="<?php echo wp_get_attachment_url( $firstImage ); ?>" class="zoom" rel="thumbnails" title="<?php echo get_the_title( $firstImage ); ?>">
		   <?php echo wp_get_attachment_image($firstImage, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' )); ?>
		</a>
		
	<?php } else { ?>
	
		<?php if ( has_post_thumbnail($_GET['postid']) ) : ?>

			<a itemprop="image" href="<?php echo wp_get_attachment_url( get_post_thumbnail_id($_GET['postid']) ); ?>" class="zoom" rel="thumbnails" title="<?php echo get_the_title( get_post_thumbnail_id($_GET['postid']) ); ?>"><?php echo get_the_post_thumbnail( $_GET['postid'], apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) ) ?></a>
	
		<?php else : ?>
		
			<img src="<?php echo eshopbox_placeholder_img_src(); ?>" alt="Placeholder" />
		
		<?php endif; ?>
	
	<?php } ?>
	
	<?php if(isset($imgs) && $imgs) { ?>
	
	<div class="thumbnails">
		<?php 
		
		$loop = 0;
		$columns = apply_filters( 'eshopbox_product_thumbnails_columns', 3 );
		
		foreach($imgs as $img) { ?>
		
			<?php
			$classes = array( 'zoom' );
				
			if ( $loop == 0 || $loop % $columns == 0 ) 
				$classes[] = 'first';
			
			if ( ( $loop + 1 ) % $columns == 0 ) 
				$classes[] = 'last';
		
			printf( '<a href="%s" title="%s" rel="thumbnails" class="%s">%s</a>', wp_get_attachment_url( $img ), esc_attr( get_the_title($img) ), implode(' ', $classes), wp_get_attachment_image( $img, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) ) ); 
			
			$loop++;
		
		} ?>
	</div>
	
	<?php } ?>
	
</div>