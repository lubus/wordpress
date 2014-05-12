<?php
// Include wp-load from our localised script
include($_GET['abspath'].'/wp-load.php');

// Get the thumbnail template
$woo_thumbs = TEMPLATEPATH.'/woo_thumbs.php';
if (file_exists($woo_thumbs)) {
    $woo_thumbs = $woo_thumbs;
} else {
    $woo_thumbs = $_GET['plugin_path'].'/templates/woo_thumbs.php';
}

$attachments = get_post_meta($_GET['varid'], 'variation_image_gallery', true);
$attachments = array_filter(explode(',', $attachments));

if (!empty($attachments)) {
	global $attachments;
	include($woo_thumbs); // Include the woo_thumbs.php template
}