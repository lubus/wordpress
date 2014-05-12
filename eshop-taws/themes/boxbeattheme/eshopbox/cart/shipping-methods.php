<?php
/**
 * Shipping Methods Display
 *
 * @author 		WooThemes
 * @package 	eshopbox/Templates
 * @version     2.0.0
 */

global $eshopbox;

// If at least one shipping method is available
if ( $available_methods ) {

	// Prepare text labels with price for each shipping method
	foreach ( $available_methods as $method ) {
		$method->full_label = $method->label;

		if ( $method->cost > 0 ) {
			if ( $eshopbox->cart->tax_display_cart == 'excl' ) {
				$method->full_label .= ': ' . eshopbox_price( $method->cost );
				if ( $method->get_shipping_tax() > 0 && $eshopbox->cart->prices_include_tax ) {
					$method->full_label .= ' <small>' . $eshopbox->countries->ex_tax_or_vat() . '</small>';
				}
			} else {
				$method->full_label .= ': ' . eshopbox_price( $method->cost + $method->get_shipping_tax() );
				if ( $method->get_shipping_tax() > 0 && ! $eshopbox->cart->prices_include_tax ) {
					$method->full_label .= ' <small>' . $eshopbox->countries->inc_tax_or_vat() . '</small>';
				}
			}
		} elseif ( $method->id !== 'free_shipping' ) {
			$method->full_label .= ' (' . __( 'Free', 'eshopbox' ) . ')';
		}
		$method->full_label = apply_filters( 'eshopbox_cart_shipping_method_full_label', $method->full_label, $method );
	}

	// Print a single available shipping method as plain text
	if ( 1 === count( $available_methods ) ) {

		echo wp_kses_post( $method->full_label ) . '<input type="hidden" name="shipping_method" id="shipping_method" value="' . esc_attr( $method->id ) . '" />';

	// Show select boxes for methods
	} elseif ( get_option('eshopbox_shipping_method_format') == 'select' ) {

		echo '<select name="shipping_method" id="shipping_method">';

		foreach ( $available_methods as $method )
			echo '<option value="' . esc_attr( $method->id ) . '" ' . selected( $method->id, $eshopbox->session->chosen_shipping_method, false ) . '>' . wp_kses_post( $method->full_label ) . '</option>';

		echo '</select>';

	// Show radio buttons for methods
	} else {

		echo '<ul id="shipping_method">';

		foreach ( $available_methods as $method )
			echo '<li><input type="radio" name="shipping_method" id="shipping_method_' . sanitize_title( $method->id ) . '" value="' . esc_attr( $method->id ) . '" ' . checked( $method->id, $eshopbox->session->chosen_shipping_method, false) . ' /> <label for="shipping_method_' . sanitize_title( $method->id ) . '">' . wp_kses_post( $method->full_label ) . '</label></li>';

		echo '</ul>';
	}

// No shipping methods are available
} else {

	if ( ! $eshopbox->customer->get_shipping_country() || ! $eshopbox->customer->get_shipping_state() || ! $eshopbox->customer->get_shipping_postcode() ) {

		echo '<p>' . __( 'Please fill in your details to see available shipping methods.', 'eshopbox' ) . '</p>';

	} else {

		$customer_location = $eshopbox->countries->countries[ $eshopbox->customer->get_shipping_country() ];

		echo apply_filters( 'eshopbox_no_shipping_available_html',
			'<p>' .
			sprintf( __( 'Sorry, it seems that there are no available shipping methods for your location (%s).', 'eshopbox' ) . ' ' . __( 'If you require assistance or wish to make alternate arrangements please contact us.', 'eshopbox' ), $customer_location ) .
			'</p>'
		);

	}

}