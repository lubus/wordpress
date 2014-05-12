<?php
// Include wp-load from our localised script
include($_GET['abspath'].'/wp-load.php');

$attachments = get_post_meta($_GET['varID'], 'variation_image_gallery', true);
$attachmentsExp = array_filter(explode(',', $attachments));
$imgIDs = array(); ?>

<ul class="wooThumbs">

	<?php if(!empty($attachmentsExp)) { ?>

		<?php foreach($attachmentsExp as $id) { $imgIDs[] = $id; ?>
			<li class="image" data-attachment_id="<?php echo $id; ?>">
				<a href="#" class="delete" title="Delete image"><?php echo wp_get_attachment_image( $id, 'thumbnail' ); ?></a>
			</li>
		<?php } ?>

	<?php } ?>

</ul>
<input type="hidden" class="variation_image_gallery" name="variation_image_gallery[<?php echo $_GET['varID']; ?>]" value="<?php echo $attachments; ?>">