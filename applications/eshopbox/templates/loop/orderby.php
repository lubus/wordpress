<?php
/**
 * Show options for ordering
 *
 * @author 		WooThemes
 * @package 	EshopBox/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $eshopbox, $wp_query;

if ( 1 == $wp_query->found_posts || ! eshopbox_products_will_display() )
	return;
?>
<form class="eshopbox-ordering" method="get">
	<select name="orderby" class="orderby">
		<?php
			$catalog_orderby = apply_filters( 'eshopbox_catalog_orderby', array(
				'menu_order' => __( 'Default sorting', 'eshopbox' ),
				'popularity' => __( 'Sort by popularity', 'eshopbox' ),
				'rating'     => __( 'Sort by average rating', 'eshopbox' ),
				'date'       => __( 'Sort by newness', 'eshopbox' ),
				'price'      => __( 'Sort by price: low to high', 'eshopbox' ),
				'price-desc' => __( 'Sort by price: high to low', 'eshopbox' )
			) );

			if ( get_option( 'eshopbox_enable_review_rating' ) == 'no' )
				unset( $catalog_orderby['rating'] );

			foreach ( $catalog_orderby as $id => $name )
				echo '<option value="' . esc_attr( $id ) . '" ' . selected( $orderby, $id, false ) . '>' . esc_attr( $name ) . '</option>';
		?>
	</select>
	<?php
		// Keep query string vars intact
		foreach ( $_GET as $key => $val ) {
			if ( 'orderby' == $key )
				continue;
			
			if (is_array($val)) {
				foreach($val as $innerVal) {
					echo '<input type="hidden" name="' . esc_attr( $key ) . '[]" value="' . esc_attr( $innerVal ) . '" />';
				}
			
			} else {
				echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" />';
			}
		}
	?>
</form>
