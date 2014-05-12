<?php
/**
 * Layered Navigation Fitlers Widget
 *
 * @author 		WooThemes
 * @category 	Widgets
 * @package 	EshopBox/Widgets
 * @version 	2.0.0
 * @extends 	WP_Widget
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WC_Widget_Layered_Nav_Filters extends WP_Widget {

	var $woo_widget_cssclass;
	var $woo_widget_description;
	var $woo_widget_idbase;
	var $woo_widget_name;

	/**
	 * constructor
	 *
	 * @access public
	 * @return void
	 */
	function WC_Widget_Layered_Nav_Filters() {

		/* Widget variable settings. */
		$this->woo_widget_cssclass 		= 'eshopbox widget_layered_nav_filters';
		$this->woo_widget_description	= __( 'Shows active layered nav filters so users can see and deactivate them.', 'eshopbox' );
		$this->woo_widget_idbase 		= 'eshopbox_layered_nav_filters';
		$this->woo_widget_name 			= __( 'EshopBox Layered Nav Filters', 'eshopbox' );

		/* Widget settings. */
		$widget_ops = array( 'classname' => $this->woo_widget_cssclass, 'description' => $this->woo_widget_description );

		/* Create the widget. */
		$this->WP_Widget( 'eshopbox_layered_nav_filters', $this->woo_widget_name, $widget_ops );
	}

	/**
	 * widget function.
	 *
	 * @see WP_Widget
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	function widget( $args, $instance ) {
		global $_chosen_attributes, $eshopbox, $_attributes_array;

		extract( $args );

		if ( ! is_post_type_archive( 'product' ) && is_array( $_attributes_array ) && ! is_tax( get_object_taxonomies( 'product' ) ) )
			return;

		$current_term 	= $_attributes_array && is_tax( $_attributes_array ) ? get_queried_object()->term_id : '';
		$current_tax 	= $_attributes_array && is_tax( $_attributes_array ) ? get_queried_object()->taxonomy : '';

		$title = ( ! isset( $instance['title'] ) ) ? __( 'Active filters', 'eshopbox' ) : $instance['title'];
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base);

		// Price
		$min_price = isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : 0;
		$max_price = isset( $_GET['max_price'] ) ? esc_attr( $_GET['max_price'] ) : 0;

		if ( count( $_chosen_attributes ) > 0 || $min_price > 0 || $max_price > 0 ) {

			echo $before_widget;
			if ( $title ) {
				echo $before_title . $title . $after_title;
			}

			echo "<ul>";

			// Attributes
			foreach ( $_chosen_attributes as $taxonomy => $data ) {

				foreach ( $data['terms'] as $term_id ) {
					$term 				= get_term( $term_id, $taxonomy );
					$taxonomy_filter 	= str_replace( 'pa_', '', $taxonomy );
					$current_filter 	= ! empty( $_GET[ 'filter_' . $taxonomy_filter ] ) ? $_GET[ 'filter_' . $taxonomy_filter ] : '';
					$new_filter			= array_map( 'absint', explode( ',', $current_filter ) );
					$new_filter			= array_diff( $new_filter, array( $term_id ) );

					$link = remove_query_arg( 'filter_' . $taxonomy_filter );

					if ( sizeof( $new_filter ) > 0 )
						$link = add_query_arg( 'filter_' . $taxonomy_filter, implode( ',', $new_filter ), $link );

					echo '<li class="chosen"><a title="' . __( 'Remove filter', 'eshopbox' ) . '" href="' . $link . '">' . $term->name . '</a></li>';
				}
			}

			if ( $min_price ) {
				$link = remove_query_arg( 'min_price' );
				echo '<li class="chosen"><a title="' . __( 'Remove filter', 'eshopbox' ) . '" href="' . $link . '">' . __( 'Min', 'eshopbox' ) . ' ' . eshopbox_price( $min_price ) . '</a></li>';
			}

			if ( $max_price ) {
				$link = remove_query_arg( 'max_price' );
				echo '<li class="chosen"><a title="' . __( 'Remove filter', 'eshopbox' ) . '" href="' . $link . '">' . __( 'Max', 'eshopbox' ) . ' ' . eshopbox_price( $max_price ) . '</a></li>';
			}

			echo "</ul>";

			echo $after_widget;
		}
	}
}