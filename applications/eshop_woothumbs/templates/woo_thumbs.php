<?php 
/*
If you need to change this template, duplicate the file
and add it to /eshop-content/themes/your-theme/jck/woo_thumbs.php
*/
global $attachments; // DO NOT EDIT

// ===== Edit below this line after copying to your theme folder (if required) ===== //

$loop = 0;
$columns = apply_filters( 'eshopbox_product_thumbnails_columns', 3 );

foreach ( $attachments as $attachmentId ) {

	if ( get_post_meta( $attachmentId, '_eshopbox_exclude_image', true ) == 1 )
		continue;

	$classes = array( 'zoom' );

	if ( $loop == 0 || $loop % $columns == 0 )
		$classes[] = 'first';

	if ( ( $loop + 1 ) % $columns == 0 )
		$classes[] = 'last';

	printf( '<a href="%s" rel="prettyPhoto[product-gallery]" class="%s">%s</a>', wp_get_attachment_url( $attachmentId ), implode(' ', $classes), wp_get_attachment_image( $attachmentId, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) ) );

	$loop++;

}