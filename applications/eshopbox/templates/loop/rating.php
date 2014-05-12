<?php
/**
 * Loop Rating
 *
 * @author 		WooThemes
 * @package 	EshopBox/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product;

if ( get_option( 'eshopbox_enable_review_rating' ) == 'no' )
	return;
?>

<?php if ( $rating_html = $product->get_rating_html() ) : ?>
	<?php echo $rating_html; ?>
<?php endif; ?>