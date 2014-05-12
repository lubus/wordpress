<?php
/**
 * Order Data
 *
 * Functions for displaying the order data meta box.
 *
 * @author 		WooThemes
 * @category 	Admin
 * @package 	EshopBox/Admin/WritePanels
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Displays the order data meta box.
 *
 * @access public
 * @param mixed $post
 * @return void
 */
function eshopbox_order_data_meta_box($post) {
	global $post, $wpdb, $thepostid, $theorder, $order_status, $eshopbox;

	$thepostid = absint( $post->ID );

	if ( ! is_object( $theorder ) )
		$theorder = new WC_Order( $thepostid );

	$order = $theorder;

	wp_nonce_field( 'eshopbox_save_data', 'eshopbox_meta_nonce' );

	// Custom user
	$customer_user = absint( get_post_meta( $post->ID, '_customer_user', true ) );

	// Order status
	$order_status = wp_get_post_terms( $post->ID, 'shop_order_status' );
	if ( $order_status ) {
		$order_status = current( $order_status );
		$order_status = sanitize_title( $order_status->slug );
	} else {
		$order_status = sanitize_title( apply_filters( 'eshopbox_default_order_status', 'pending' ) );
	}

	if ( empty( $post->post_title ) )
		$order_title = 'Order';
	else
		$order_title = $post->post_title;
	?>
	<style type="text/css">
		#post-body-content, #titlediv, #major-publishing-actions, #minor-publishing-actions, #visibility, #submitdiv { display:none }
	</style>
	<div class="panel-wrap eshopbox">
		<input name="post_title" type="hidden" value="<?php echo esc_attr( $order_title ); ?>" />
		<input name="post_status" type="hidden" value="publish" />
		<div id="order_data" class="panel">

			<h2><?php _e( 'Order Details', 'eshopbox' ); ?></h2>
			<p class="order_number"><?php

				echo __( 'Order number', 'eshopbox' ) . ' ' . esc_html( $order->get_order_number() ) . '. ';

				$ip_address = get_post_meta( $post->ID, '_customer_ip_address', true );

				if ( $ip_address )
					echo __( 'Customer IP:', 'eshopbox' ) . ' ' . esc_html( $ip_address );

			?></p>

			<div class="order_data_column_container">
				<div class="order_data_column">

					<h4><?php _e( 'General Details', 'eshopbox' ); ?></h4>

					<p class="form-field"><label for="order_status"><?php _e( 'Order status:', 'eshopbox' ) ?></label>
					<select id="order_status" name="order_status" class="chosen_select">
						<?php
							$statuses = (array) get_terms( 'shop_order_status', array( 'hide_empty' => 0, 'orderby' => 'id' ) );
							foreach ( $statuses as $status ) {
								echo '<option value="' . esc_attr( $status->slug ) . '" ' . selected( $status->slug, $order_status, false ) . '>' . esc_html__( $status->name, 'eshopbox' ) . '</option>';
							}
						?>
					</select></p>

					<p class="form-field last"><label for="order_date"><?php _e( 'Order Date:', 'eshopbox' ) ?></label>
						<input type="text" class="date-picker-field" name="order_date" id="order_date" maxlength="10" value="<?php echo date_i18n( 'Y-m-d', strtotime( $post->post_date ) ); ?>" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" /> @ <input type="text" class="hour" placeholder="<?php _e( 'h', 'eshopbox' ) ?>" name="order_date_hour" id="order_date_hour" maxlength="2" size="2" value="<?php echo date_i18n( 'H', strtotime( $post->post_date ) ); ?>" pattern="\-?\d+(\.\d{0,})?" />:<input type="text" class="minute" placeholder="<?php _e( 'm', 'eshopbox' ) ?>" name="order_date_minute" id="order_date_minute" maxlength="2" size="2" value="<?php echo date_i18n( 'i', strtotime( $post->post_date ) ); ?>" pattern="\-?\d+(\.\d{0,})?" />
					</p>

					<p class="form-field form-field-wide">
						<label for="customer_user"><?php _e( 'Customer:', 'eshopbox' ) ?></label>
						<select id="customer_user" name="customer_user" class="ajax_chosen_select_customer">
							<option value=""><?php _e( 'Guest', 'eshopbox' ) ?></option>
							<?php
								if ( $customer_user ) {
									$user = get_user_by( 'id', $customer_user );
									echo '<option value="' . esc_attr( $user->ID ) . '" ' . selected( 1, 1, false ) . '>' . esc_html( $user->display_name ) . ' (#' . absint( $user->ID ) . ' &ndash; ' . esc_html( $user->user_email ) . ')</option>';
								}
							?>
						</select>
						<?php

						// Ajax Chosen Customer Selectors JS
						$eshopbox->add_inline_js( "
							jQuery('select.ajax_chosen_select_customer').ajaxChosen({
							    method: 		'GET',
							    url: 			'" . admin_url('admin-ajax.php') . "',
							    dataType: 		'json',
							    afterTypeDelay: 100,
							    minTermLength: 	1,
							    data:		{
							    	action: 	'eshopbox_json_search_customers',
									security: 	'" . wp_create_nonce("search-customers") . "'
							    }
							}, function (data) {

								var terms = {};

							    $.each(data, function (i, val) {
							        terms[i] = val;
							    });

							    return terms;
							});
						" );
						?>
					</p>

					<?php if ( get_option( 'eshopbox_enable_order_comments' ) != 'no' ) : ?>

						<p class="form-field form-field-wide"><label for="excerpt"><?php _e( 'Customer Note:', 'eshopbox' ) ?></label>
						<textarea rows="1" cols="40" name="excerpt" tabindex="6" id="excerpt" placeholder="<?php _e( 'Customer\'s notes about the order', 'eshopbox' ); ?>"><?php echo wp_kses_post( $post->post_excerpt ); ?></textarea></p>

					<?php endif; ?>

					<?php do_action( 'eshopbox_admin_order_data_after_order_details', $order ); ?>

				</div>
				<div class="order_data_column">
					<h4><?php _e( 'Billing Details', 'eshopbox' ); ?> <a class="edit_address" href="#">(<?php _e( 'Edit', 'eshopbox' ) ;?>)</a></h4>
					<?php
						$billing_data = apply_filters('eshopbox_admin_billing_fields', array(
							'first_name' => array(
								'label' => __( 'First Name', 'eshopbox' ),
								'show'	=> false
								),
							'last_name' => array(
								'label' => __( 'Last Name', 'eshopbox' ),
								'show'	=> false
								),
							'company' => array(
								'label' => __( 'Company', 'eshopbox' ),
								'show'	=> false
								),
							'address_1' => array(
								'label' => __( 'Address 1', 'eshopbox' ),
								'show'	=> false
								),
							'address_2' => array(
								'label' => __( 'Address 2', 'eshopbox' ),
								'show'	=> false
								),
							'city' => array(
								'label' => __( 'City', 'eshopbox' ),
								'show'	=> false
								),
							'postcode' => array(
								'label' => __( 'Postcode', 'eshopbox' ),
								'show'	=> false
								),
							'country' => array(
								'label' => __( 'Country', 'eshopbox' ),
								'show'	=> false,
								'type'	=> 'select',
								'options' => array( '' => __( 'Select a country&hellip;', 'eshopbox' ) ) + $eshopbox->countries->get_allowed_countries()
								),
							'state' => array(
								'label' => __( 'State/County', 'eshopbox' ),
								'show'	=> false
								),
							'email' => array(
								'label' => __( 'Email', 'eshopbox' ),
								),
							'phone' => array(
								'label' => __( 'Phone', 'eshopbox' ),
								),
							) );

						// Display values
						echo '<div class="address">';

							if ( $order->get_formatted_billing_address() )
								echo '<p><strong>' . __( 'Address', 'eshopbox' ) . ':</strong><br/> ' . $order->get_formatted_billing_address() . '</p>';
							else
								echo '<p class="none_set"><strong>' . __( 'Address', 'eshopbox' ) . ':</strong> ' . __( 'No billing address set.', 'eshopbox' ) . '</p>';

							foreach ( $billing_data as $key => $field ) {
								if ( isset( $field['show'] ) && $field['show'] === false )
									continue;
								$field_name = 'billing_' . $key;
								if ( $order->$field_name )
									echo '<p><strong>' . esc_html( $field['label'] ) . ':</strong> ' . esc_html( $order->$field_name ) . '</p>';
							}

						echo '</div>';

						// Display form
						echo '<div class="edit_address"><p><button class="button load_customer_billing">'.__( 'Load billing address', 'eshopbox' ).'</button></p>';

						foreach ( $billing_data as $key => $field ) {
							if ( ! isset( $field['type'] ) )
								$field['type'] = 'text';
							switch ( $field['type'] ) {
								case "select" :
									eshopbox_wp_select( array( 'id' => '_billing_' . $key, 'label' => $field['label'], 'options' => $field['options'] ) );
								break;
								default :
									eshopbox_wp_text_input( array( 'id' => '_billing_' . $key, 'label' => $field['label'] ) );
								break;
							}
						}

						echo '</div>';

						do_action( 'eshopbox_admin_order_data_after_billing_address', $order );
					?>
				</div>
				<div class="order_data_column">

					<h4><?php _e( 'Shipping Details', 'eshopbox' ); ?> <a class="edit_address" href="#">(<?php _e( 'Edit', 'eshopbox' ) ;?>)</a></h4>
					<?php
						$shipping_data = apply_filters('eshopbox_admin_shipping_fields', array(
							'first_name' => array(
								'label' => __( 'First Name', 'eshopbox' ),
								'show'	=> false
								),
							'last_name' => array(
								'label' => __( 'Last Name', 'eshopbox' ),
								'show'	=> false
								),
							'company' => array(
								'label' => __( 'Company', 'eshopbox' ),
								'show'	=> false
								),
							'address_1' => array(
								'label' => __( 'Address 1', 'eshopbox' ),
								'show'	=> false
								),
							'address_2' => array(
								'label' => __( 'Address 2', 'eshopbox' ),
								'show'	=> false
								),
							'city' => array(
								'label' => __( 'City', 'eshopbox' ),
								'show'	=> false
								),
							'postcode' => array(
								'label' => __( 'Postcode', 'eshopbox' ),
								'show'	=> false
								),
							'country' => array(
								'label' => __( 'Country', 'eshopbox' ),
								'show'	=> false,
								'type'	=> 'select',
								'options' => array( '' => __( 'Select a country&hellip;', 'eshopbox' ) ) + $eshopbox->countries->get_allowed_countries()
								),
							'state' => array(
								'label' => __( 'State/County', 'eshopbox' ),
								'show'	=> false
								),
							) );

						// Display values
						echo '<div class="address">';

							if ( $order->get_formatted_shipping_address() )
								echo '<p><strong>' . __( 'Address', 'eshopbox' ) . ':</strong><br/> ' . $order->get_formatted_shipping_address() . '</p>';
							else
								echo '<p class="none_set"><strong>' . __( 'Address', 'eshopbox' ) . ':</strong> ' . __( 'No shipping address set.', 'eshopbox' ) . '</p>';

							if ( $shipping_data ) foreach ( $shipping_data as $key => $field ) {
								if ( isset( $field['show'] ) && $field['show'] === false )
									continue;
								$field_name = 'shipping_' . $key;
								if ( $order->$field_name )
									echo '<p><strong>' . esc_html( $field['label'] ) . ':</strong> ' . esc_html( $order->$field_name ) . '</p>';
							}

						echo '</div>';

						// Display form
						echo '<div class="edit_address"><p><button class="button load_customer_shipping">' . __( 'Load shipping address', 'eshopbox' ) . '</button> <button class="button billing-same-as-shipping">'. __( 'Copy from billing', 'eshopbox' ) . '</button></p>';

						if ( $shipping_data ) foreach ( $shipping_data as $key => $field ) {
							if ( ! isset( $field['type'] ) )
								$field['type'] = 'text';
							switch ( $field['type'] ) {
								case "select" :
									eshopbox_wp_select( array( 'id' => '_shipping_' . $key, 'label' => $field['label'], 'options' => $field['options'] ) );
								break;
								default :
									eshopbox_wp_text_input( array( 'id' => '_shipping_' . $key, 'label' => $field['label'] ) );
								break;
							}
						}

						echo '</div>';

						do_action( 'eshopbox_admin_order_data_after_shipping_address', $order );
					?>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php
}

/**
 * Order items meta box.
 *
 * Displays the order items meta box - for showing individual items in the order.
 */
function eshopbox_order_items_meta_box( $post ) {
	global $wpdb, $thepostid, $theorder, $eshopbox;

	if ( ! is_object( $theorder ) )
		$theorder = new WC_Order( $thepostid );

	$order = $theorder;

	$data = get_post_meta( $post->ID );
	?>
	<div class="eshopbox_order_items_wrapper">
		<table cellpadding="0" cellspacing="0" class="eshopbox_order_items">
			<thead>
				<tr>
					<th><input type="checkbox" class="check-column" /></th>
					<th class="item" colspan="2"><?php _e( 'Item', 'eshopbox' ); ?></th>

					<?php do_action( 'eshopbox_admin_order_item_headers' ); ?>

					<?php if ( get_option( 'eshopbox_calc_taxes' ) == 'yes' ) : ?>
						<th class="tax_class"><?php _e( 'Tax Class', 'eshopbox' ); ?>&nbsp;<a class="tips" data-tip="<?php _e( 'Tax class for the line item', 'eshopbox' ); ?>." href="#">[?]</a></th>
					<?php endif; ?>

					<th class="quantity"><?php _e( 'Qty', 'eshopbox' ); ?></th>

					<th class="line_cost"><?php _e( 'Totals', 'eshopbox' ); ?>&nbsp;<a class="tips" data-tip="<?php _e( 'Line subtotals are before pre-tax discounts, totals are after.', 'eshopbox' ); ?>" href="#">[?]</a></th>

					<?php if ( get_option( 'eshopbox_calc_taxes' ) == 'yes' ) : ?>
						<th class="line_tax"><?php _e( 'Tax', 'eshopbox' ); ?></th>
					<?php endif; ?>
				</tr>
			</thead>
			<tbody id="order_items_list">

				<?php
					// List order items
					$order_items = $order->get_items( apply_filters( 'eshopbox_admin_order_item_types', array( 'line_item', 'fee' ) ) );

					foreach ( $order_items as $item_id => $item ) {

						switch ( $item['type'] ) {
							case 'line_item' :
								$_product 	= $order->get_product_from_item( $item );
								$item_meta 	= $order->get_item_meta( $item_id );

								include( 'order-item-html.php' );
							break;
							case 'fee' :
								include( 'order-fee-html.php' );
							break;
						}

						do_action( 'eshopbox_order_item_' . $item['type'] . '_html', $item_id, $item );

					}
				?>
			</tbody>
		</table>
	</div>

	<p class="bulk_actions">
		<select>
			<option value=""><?php _e( 'Actions', 'eshopbox' ); ?></option>
			<optgroup label="<?php _e( 'Edit', 'eshopbox' ); ?>">
				<option value="delete"><?php _e( 'Delete Lines', 'eshopbox' ); ?></option>
			</optgroup>
			<optgroup label="<?php _e( 'Stock Actions', 'eshopbox' ); ?>">
				<option value="reduce_stock"><?php _e( 'Reduce Line Stock', 'eshopbox' ); ?></option>
				<option value="increase_stock"><?php _e( 'Increase Line Stock', 'eshopbox' ); ?></option>
			</optgroup>
		</select>

		<button type="button" class="button do_bulk_action wc-reload" title="<?php _e( 'Apply', 'eshopbox' ); ?>"><span><?php _e( 'Apply', 'eshopbox' ); ?></span></button>
	</p>

	<p class="add_items">
		<select id="add_item_id" class="ajax_chosen_select_products_and_variations" multiple="multiple" data-placeholder="<?php _e( 'Search for a product&hellip;', 'eshopbox' ); ?>" style="width: 400px"></select>

		<button type="button" class="button add_order_item"><?php _e( 'Add item(s)', 'eshopbox' ); ?></button>
		<button type="button" class="button add_order_fee"><?php _e( 'Add fee', 'eshopbox' ); ?></button>
	</p>
	<div class="clear"></div>
	<?php
}


/**
 * Display the order actions meta box.
 *
 * Displays the order actions meta box - buttons for managing order stock and sending the customer an invoice.
 *
 * @access public
 * @param mixed $post
 * @return void
 */
function eshopbox_order_actions_meta_box( $post ) {
	global $eshopbox, $theorder, $wpdb;

	if ( ! is_object( $theorder ) )
		$theorder = new WC_Order( $post->ID );

	$order = $theorder;
	?>
	<ul class="order_actions submitbox">

		<?php do_action( 'eshopbox_order_actions_start', $post->ID ); ?>

		<li class="wide" id="actions">
			<select name="wc_order_action">
				<option value=""><?php _e( 'Actions', 'eshopbox' ); ?></option>
				<optgroup label="<?php _e( 'Resend order emails', 'eshopbox' ); ?>">
					<?php
					global $eshopbox;
					$mailer = $eshopbox->mailer();

					$available_emails = apply_filters( 'eshopbox_resend_order_emails_available', array( 'new_order', 'customer_processing_order', 'customer_completed_order', 'customer_invoice' ) );
					$mails = $mailer->get_emails();

					if ( ! empty( $mails ) ) {
						foreach ( $mails as $mail ) {
							if ( in_array( $mail->id, $available_emails ) ) {
								echo '<option value="send_email_'. esc_attr( $mail->id ) .'">' . esc_html( $mail->title ) . '</option>';
							}
						}
					}
					?><option value="send_email_order_shipped">Send Shipment sms and mail</option>
				</optgroup>
				<?php foreach( apply_filters( 'eshopbox_order_actions', array() ) as $action => $title ) { ?>
					<option value="<?php echo $action; ?>"><?php echo $title; ?></option>
				<?php } ?>
			</select>

			<button class="button wc-reload" title="<?php _e( 'Apply', 'eshopbox' ); ?>"><span><?php _e( 'Apply', 'eshopbox' ); ?></span></button>
		</li>

		<li class="wide">
			<div id="delete-action"><?php
				if ( current_user_can( "delete_post", $post->ID ) ) {
					if ( ! EMPTY_TRASH_DAYS )
						$delete_text = __( 'Delete Permanently', 'eshopbox' );
					else
						$delete_text = __( 'Move to Trash', 'eshopbox' );
					?><a class="submitdelete deletion" href="<?php echo esc_url( get_delete_post_link( $post->ID ) ); ?>"><?php echo $delete_text; ?></a><?php
				}
			?></div>

			<input type="submit" class="button save_order button-primary tips" name="save" value="<?php _e( 'Save Order', 'eshopbox' ); ?>" data-tip="<?php _e( 'Save/update the order', 'eshopbox' ); ?>" />
		</li>

		<?php do_action( 'eshopbox_order_actions_end', $post->ID ); ?>

	</ul>
	<?php
}


/**
 * Displays the order totals meta box.
 *
 * @access public
 * @param mixed $post
 * @return void
 */
function eshopbox_order_totals_meta_box( $post ) {
	global $eshopbox, $theorder, $wpdb;

	if ( ! is_object( $theorder ) )
		$theorder = new WC_Order( $post->ID );

	$order = $theorder;

	$data = get_post_meta( $post->ID );
	?>
	<div class="totals_group">
		<h4><span class="discount_total_display inline_total"></span><?php _e( 'Discounts', 'eshopbox' ); ?></h4>
		<ul class="totals">

			<li class="left">
				<label><?php _e( 'Cart Discount:', 'eshopbox' ); ?>&nbsp;<a class="tips" data-tip="<?php _e( 'Discounts before tax - calculated by comparing subtotals to totals.', 'eshopbox' ); ?>" href="#">[?]</a></label>
				<input type="number" step="any" min="0" id="_cart_discount" name="_cart_discount" placeholder="0.00" value="<?php
					if ( isset( $data['_cart_discount'][0] ) )
						echo esc_attr( $data['_cart_discount'][0] );
				?>" class="calculated" />
			</li>

			<li class="right">
				<label><?php _e( 'Order Discount:', 'eshopbox' ); ?>&nbsp;<a class="tips" data-tip="<?php _e( 'Discounts after tax - user defined.', 'eshopbox' ); ?>" href="#">[?]</a></label>
				<input type="number" step="any" min="0" id="_order_discount" name="_order_discount" placeholder="0.00" value="<?php
					if ( isset( $data['_order_discount'][0] ) )
						echo esc_attr( $data['_order_discount'][0] );
				?>" />
			</li>

		</ul>

		<ul class="wc_coupon_list">

		<?php
			$coupons = $order->get_items( array( 'coupon' ) );

			foreach ( $coupons as $item_id => $item ) {

				$post_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_title = %s AND post_type = 'shop_coupon' AND post_status = 'publish' LIMIT 1;", $item['name'] ) );

				$link = $post_id ? admin_url( 'post.php?post=' . $post_id . '&action=edit' ) : admin_url( 'edit.php?s=' . esc_url( $item['name'] ) . '&post_status=all&post_type=shop_coupon' );

				echo '<li class="tips code" data-tip="' . esc_attr( eshopbox_price( $item['discount_amount'] ) ) . '"><a href="' . $link . '"><span>' . esc_html( $item['name'] ). '</span></a></li>';

			}
		?>

		</ul>

	</div>
	<div class="totals_group">
		<h4><?php _e( 'Shipping', 'eshopbox' ); ?></h4>
		<ul class="totals">

			<li class="wide">
				<label><?php _e( 'Label:', 'eshopbox' ); ?></label>
				<input type="text" id="_shipping_method_title" name="_shipping_method_title" placeholder="<?php _e( 'The shipping title the customer sees', 'eshopbox' ); ?>" value="<?php
					if ( isset( $data['_shipping_method_title'][0] ) )
						echo esc_attr( $data['_shipping_method_title'][0] );
				?>" class="first" />
			</li>

			<li class="left">
				<label><?php _e( 'Cost:', 'eshopbox' ); ?></label>
				<input type="number" step="any" min="0" id="_order_shipping" name="_order_shipping" placeholder="0.00 <?php _e( '(ex. tax)', 'eshopbox' ); ?>" value="<?php
					if ( isset( $data['_order_shipping'][0] ) )
						echo esc_attr( $data['_order_shipping'][0] );
				?>" class="first" />
			</li>

			<li class="right">
				<label><?php _e( 'Method:', 'eshopbox' ); ?></label>
				<select name="_shipping_method" id="_shipping_method" class="first">
					<option value=""><?php _e( 'N/A', 'eshopbox' ); ?></option>
					<?php
						$chosen_method 	= ! empty( $data['_shipping_method'][0] ) ? $data['_shipping_method'][0] : '';
						$found_method 	= false;

						if ( $eshopbox->shipping() ) {
							foreach ( $eshopbox->shipping->load_shipping_methods() as $method ) {

								if ( strpos( $chosen_method, $method->id ) === 0 )
									$value = $chosen_method;
								else
									$value = $method->id;

								echo '<option value="' . esc_attr( $value ) . '" ' . selected( $chosen_method == $value, true, false ) . '>' . esc_html( $method->get_title() ) . '</option>';
								if ( $chosen_method == $value )
									$found_method = true;
							}
						}

						if ( ! $found_method && ! empty( $chosen_method ) ) {
							echo '<option value="' . esc_attr( $chosen_method ) . '" selected="selected">' . __( 'Other', 'eshopbox' ) . '</option>';
						} else {
							echo '<option value="other">' . __( 'Other', 'eshopbox' ) . '</option>';
						}
					?>
				</select>
			</li>

		</ul>
		<?php do_action( 'eshopbox_admin_order_totals_after_shipping', $post->ID ) ?>
		<div class="clear"></div>
	</div>

	<?php if ( get_option( 'eshopbox_calc_taxes' ) == 'yes' ) : ?>

	<div class="totals_group tax_rows_group">
		<h4><?php _e( 'Tax Rows', 'eshopbox' ); ?></h4>
		<div id="tax_rows" class="total_rows">
			<?php
				global $wpdb;

				$rates = $wpdb->get_results( "SELECT tax_rate_id, tax_rate_country, tax_rate_state, tax_rate_name, tax_rate_priority FROM {$wpdb->prefix}eshopbox_tax_rates ORDER BY tax_rate_name" );

				$tax_codes = array();

				foreach( $rates as $rate ) {
					$code = array();

					$code[] = $rate->tax_rate_country;
					$code[] = $rate->tax_rate_state;
					$code[] = $rate->tax_rate_name ? sanitize_title( $rate->tax_rate_name ) : 'TAX';
					$code[] = absint( $rate->tax_rate_priority );

					$tax_codes[ $rate->tax_rate_id ] = strtoupper( implode( '-', array_filter( $code ) ) );
				}

				foreach ( $order->get_taxes() as $item_id => $item ) {
					include( 'order-tax-html.php' );
				}
			?>
		</div>
		<h4><a href="#" class="add_tax_row"><?php _e( '+ Add tax row', 'eshopbox' ); ?> <span class="tips" data-tip="<?php _e( 'These rows contain taxes for this order. This allows you to display multiple or compound taxes rather than a single total.', 'eshopbox' ); ?>">[?]</span></a></a></h4>
		<div class="clear"></div>
	</div>
	<div class="totals_group">
		<h4><span class="tax_total_display inline_total"></span><?php _e( 'Tax Totals', 'eshopbox' ); ?></h4>
		<ul class="totals">

			<li class="left">
				<label><?php _e( 'Sales Tax:', 'eshopbox' ); ?>&nbsp;<a class="tips" data-tip="<?php _e( 'Total tax for line items + fees.', 'eshopbox' ); ?>" href="#">[?]</a></label>
				<input type="number" step="any" min="0" id="_order_tax" name="_order_tax" placeholder="0.00" value="<?php
					if ( isset( $data['_order_tax'][0] ) )
						echo esc_attr( $data['_order_tax'][0] );
				?>" class="calculated" />
			</li>

			<li class="right">
				<label><?php _e( 'Shipping Tax:', 'eshopbox' ); ?></label>
				<input type="number" step="any" min="0" id="_order_shipping_tax" name="_order_shipping_tax" placeholder="0.00" value="<?php
					if ( isset( $data['_order_shipping_tax'][0] ) )
						echo esc_attr( $data['_order_shipping_tax'][0] );
				?>" />
			</li>

		</ul>
		<div class="clear"></div>
	</div>

	<?php endif; ?>

	<div class="totals_group">
		<h4><?php _e( 'Order Totals', 'eshopbox' ); ?></h4>
		<ul class="totals">

			<li class="left">
				<label><?php _e( 'Order Total:', 'eshopbox' ); ?></label>
				<input type="number" step="any" min="0" id="_order_total" name="_order_total" placeholder="0.00" value="<?php
					if ( isset( $data['_order_total'][0] ) )
						echo esc_attr( $data['_order_total'][0] );
				?>" class="calculated" />
			</li>

			<li class="right">
				<label><?php _e( 'Payment Method:', 'eshopbox' ); ?></label>
				<select name="_payment_method" id="_payment_method" class="first">
					<option value=""><?php _e( 'N/A', 'eshopbox' ); ?></option>
					<?php
						$chosen_method 	= ! empty( $data['_payment_method'][0] ) ? $data['_payment_method'][0] : '';
						$found_method 	= false;

						if ( $eshopbox->payment_gateways() ) {
							foreach ( $eshopbox->payment_gateways->payment_gateways() as $gateway ) {
								if ( $gateway->enabled == "yes" ) {
									echo '<option value="' . esc_attr( $gateway->id ) . '" ' . selected( $chosen_method, $gateway->id, false ) . '>' . esc_html( $gateway->get_title() ) . '</option>';
									if ( $chosen_method == $gateway->id )
										$found_method = true;
								}
							}
						}

						if ( ! $found_method && ! empty( $chosen_method ) ) {
							echo '<option value="' . esc_attr( $chosen_method ) . '" selected="selected">' . __( 'Other', 'eshopbox' ) . '</option>';
						} else {
							echo '<option value="other">' . __( 'Other', 'eshopbox' ) . '</option>';
						}
					?>
				</select>
			</li>

		</ul>
		<div class="clear"></div>
	</div>
	<p class="buttons">
		<?php if ( get_option( 'eshopbox_calc_taxes' ) == 'yes' ) : ?>
			<button type="button" class="button calc_line_taxes"><?php _e( 'Calc taxes', 'eshopbox' ); ?></button>
		<?php endif; ?>
		<button type="button" class="button calc_totals button-primary"><?php _e( 'Calc totals', 'eshopbox' ); ?></button>
	</p>
	<?php
}


/**
 * Save the order data meta box.
 *
 * @access public
 * @param mixed $post_id
 * @param mixed $post
 * @return void
 */
function eshopbox_process_shop_order_meta( $post_id, $post ) {
	global $wpdb, $eshopbox, $eshopbox_errors;

	// Add key
	add_post_meta( $post_id, '_order_key', uniqid('order_'), true );

	// Update post data
	update_post_meta( $post_id, '_billing_first_name', eshopbox_clean( $_POST['_billing_first_name'] ) );
	update_post_meta( $post_id, '_billing_last_name', eshopbox_clean( $_POST['_billing_last_name'] ) );
	update_post_meta( $post_id, '_billing_company', eshopbox_clean( $_POST['_billing_company'] ) );
	update_post_meta( $post_id, '_billing_address_1', eshopbox_clean( $_POST['_billing_address_1'] ) );
	update_post_meta( $post_id, '_billing_address_2', eshopbox_clean( $_POST['_billing_address_2'] ) );
	update_post_meta( $post_id, '_billing_city', eshopbox_clean( $_POST['_billing_city'] ) );
	update_post_meta( $post_id, '_billing_postcode', eshopbox_clean( $_POST['_billing_postcode'] ) );
	update_post_meta( $post_id, '_billing_country', eshopbox_clean( $_POST['_billing_country'] ) );
	update_post_meta( $post_id, '_billing_state', eshopbox_clean( $_POST['_billing_state'] ) );
	update_post_meta( $post_id, '_billing_email', eshopbox_clean( $_POST['_billing_email'] ) );
	update_post_meta( $post_id, '_billing_phone', eshopbox_clean( $_POST['_billing_phone'] ) );
	update_post_meta( $post_id, '_shipping_first_name', eshopbox_clean( $_POST['_shipping_first_name'] ) );
	update_post_meta( $post_id, '_shipping_last_name', eshopbox_clean( $_POST['_shipping_last_name'] ) );
	update_post_meta( $post_id, '_shipping_company', eshopbox_clean( $_POST['_shipping_company'] ) );
	update_post_meta( $post_id, '_shipping_address_1', eshopbox_clean( $_POST['_shipping_address_1'] ) );
	update_post_meta( $post_id, '_shipping_address_2', eshopbox_clean( $_POST['_shipping_address_2'] ) );
	update_post_meta( $post_id, '_shipping_city', eshopbox_clean( $_POST['_shipping_city'] ) );
	update_post_meta( $post_id, '_shipping_postcode', eshopbox_clean( $_POST['_shipping_postcode'] ) );
	update_post_meta( $post_id, '_shipping_country', eshopbox_clean( $_POST['_shipping_country'] ) );
	update_post_meta( $post_id, '_shipping_state', eshopbox_clean( $_POST['_shipping_state'] ) );
	update_post_meta( $post_id, '_order_shipping', eshopbox_clean( $_POST['_order_shipping'] ) );
	update_post_meta( $post_id, '_cart_discount', eshopbox_clean( $_POST['_cart_discount'] ) );
	update_post_meta( $post_id, '_order_discount', eshopbox_clean( $_POST['_order_discount'] ) );
	update_post_meta( $post_id, '_order_total', eshopbox_clean( $_POST['_order_total'] ) );
	update_post_meta( $post_id, '_customer_user', absint( $_POST['customer_user'] ) );

	if ( isset( $_POST['_order_tax'] ) )
		update_post_meta( $post_id, '_order_tax', eshopbox_clean( $_POST['_order_tax'] ) );

	if ( isset( $_POST['_order_shipping_tax'] ) )
		update_post_meta( $post_id, '_order_shipping_tax', eshopbox_clean( $_POST['_order_shipping_tax'] ) );

	// Shipping method handling
	if ( get_post_meta( $post_id, '_shipping_method', true ) !== stripslashes( $_POST['_shipping_method'] ) ) {

		$shipping_method = eshopbox_clean( $_POST['_shipping_method'] );

		update_post_meta( $post_id, '_shipping_method', $shipping_method );
	}

	if ( get_post_meta( $post_id, '_shipping_method_title', true ) !== stripslashes( $_POST['_shipping_method_title'] ) ) {

		$shipping_method_title = eshopbox_clean( $_POST['_shipping_method_title'] );

		if ( ! $shipping_method_title ) {

			$shipping_method = esc_attr( $_POST['_shipping_method'] );
			$methods = $eshopbox->shipping->load_shipping_methods();

			if ( isset( $methods ) && isset( $methods[ $shipping_method ] ) )
				$shipping_method_title = $methods[ $shipping_method ]->get_title();
		}

		update_post_meta( $post_id, '_shipping_method_title', $shipping_method_title );
	}

	// Payment method handling
	if ( get_post_meta( $post_id, '_payment_method', true ) !== stripslashes( $_POST['_payment_method'] ) ) {

		$methods 				= $eshopbox->payment_gateways->payment_gateways();
		$payment_method 		= eshopbox_clean( $_POST['_payment_method'] );
		$payment_method_title 	= $payment_method;

		if ( isset( $methods) && isset( $methods[ $payment_method ] ) )
			$payment_method_title = $methods[ $payment_method ]->get_title();

		update_post_meta( $post_id, '_payment_method', $payment_method );
		update_post_meta( $post_id, '_payment_method_title', $payment_method_title );
	}

	// Update date
	if ( empty( $_POST['order_date'] ) ) {
		$date = current_time('timestamp');
	} else {
		$date = strtotime( $_POST['order_date'] . ' ' . (int) $_POST['order_date_hour'] . ':' . (int) $_POST['order_date_minute'] . ':00' );
	}

	$date = date_i18n( 'Y-m-d H:i:s', $date );

	$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->posts SET post_date = %s, post_date_gmt = %s WHERE ID = %s", $date, get_gmt_from_date( $date ), $post_id ) );


	// Tax rows
	if ( isset( $_POST['order_taxes_id'] ) ) {

		$get_values = array( 'order_taxes_id', 'order_taxes_rate_id', 'order_taxes_amount', 'order_taxes_shipping_amount' );

		foreach( $get_values as $value )
			$$value = isset( $_POST[ $value ] ) ? $_POST[ $value ] : array();

		foreach( $order_taxes_id as $item_id ) {

			$item_id  = absint( $item_id );
			$rate_id  = absint( $order_taxes_rate_id[ $item_id ] );

			if ( $rate_id ) {
				$rate     = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}eshopbox_tax_rates WHERE tax_rate_id = %s", $rate_id ) );
				$label    = $rate->tax_rate_name ? $rate->tax_rate_name : $eshopbox->countries->tax_or_vat();
				$compound = $rate->tax_rate_compound ? 1 : 0;

				$code = array();

				$code[] = $rate->tax_rate_country;
				$code[] = $rate->tax_rate_state;
				$code[] = $rate->tax_rate_name ? $rate->tax_rate_name : 'TAX';
				$code[] = absint( $rate->tax_rate_priority );
				$code   = strtoupper( implode( '-', array_filter( $code ) ) );
			} else {
				$code  = '';
				$label = $eshopbox->countries->tax_or_vat();
			}

			$wpdb->update(
				$wpdb->prefix . "eshopbox_order_items",
				array( 'order_item_name' => eshopbox_clean( $code ) ),
				array( 'order_item_id' => $item_id ),
				array( '%s' ),
				array( '%d' )
			);

			eshopbox_update_order_item_meta( $item_id, 'rate_id', $rate_id );
			eshopbox_update_order_item_meta( $item_id, 'label', $label );
			eshopbox_update_order_item_meta( $item_id, 'compound', $compound );

			if ( isset( $order_taxes_amount[ $item_id ] ) )
		 		eshopbox_update_order_item_meta( $item_id, 'tax_amount', eshopbox_clean( $order_taxes_amount[ $item_id ] ) );

		 	if ( isset( $order_taxes_shipping_amount[ $item_id ] ) )
		 		eshopbox_update_order_item_meta( $item_id, 'shipping_tax_amount', eshopbox_clean( $order_taxes_shipping_amount[ $item_id ] ) );
		}
	}


	// Order items + fees
	if ( isset( $_POST['order_item_id'] ) ) {

		$get_values = array( 'order_item_id', 'order_item_name', 'order_item_qty', 'line_subtotal', 'line_subtotal_tax', 'line_total', 'line_tax', 'order_item_tax_class' );

		foreach( $get_values as $value )
			$$value = isset( $_POST[ $value ] ) ? $_POST[ $value ] : array();

		foreach ( $order_item_id as $item_id ) {

			$item_id = absint( $item_id );

			if ( isset( $order_item_name[ $item_id ] ) )
				$wpdb->update(
					$wpdb->prefix . "eshopbox_order_items",
					array( 'order_item_name' => eshopbox_clean( $order_item_name[ $item_id ] ) ),
					array( 'order_item_id' => $item_id ),
					array( '%s' ),
					array( '%d' )
				);

			if ( isset( $order_item_qty[ $item_id ] ) )
		 		eshopbox_update_order_item_meta( $item_id, '_qty', apply_filters( 'eshopbox_stock_amount', $order_item_qty[ $item_id ] ) );

		 	if ( isset( $item_tax_class[ $item_id ] ) )
		 		eshopbox_update_order_item_meta( $item_id, '_tax_class', eshopbox_clean( $item_tax_class[ $item_id ] ) );

		 	if ( isset( $line_subtotal[ $item_id ] ) )
		 		eshopbox_update_order_item_meta( $item_id, '_line_subtotal', eshopbox_clean( $line_subtotal[ $item_id ] ) );

		 	if ( isset(  $line_subtotal_tax[ $item_id ] ) )
		 		eshopbox_update_order_item_meta( $item_id, '_line_subtotal_tax', eshopbox_clean( $line_subtotal_tax[ $item_id ] ) );

		 	if ( isset( $line_total[ $item_id ] ) )
		 		eshopbox_update_order_item_meta( $item_id, '_line_total', eshopbox_clean( $line_total[ $item_id ] ) );

		 	if ( isset( $line_tax[ $item_id ] ) )
		 		eshopbox_update_order_item_meta( $item_id, '_line_tax', eshopbox_clean( $line_tax[ $item_id ] ) );

		 	// Clear meta cache
		 	wp_cache_delete( $item_id, 'order_item_meta' );
		}
	}

	// Save meta
	$meta_keys 		= isset( $_POST['meta_key'] ) ? $_POST['meta_key'] : array();
	$meta_values 	= isset( $_POST['meta_value'] ) ? $_POST['meta_value'] : array();

	foreach ( $meta_keys as $id => $meta_key ) {
		$meta_value = ( empty( $meta_values[ $id ] ) && ! is_numeric( $meta_values[ $id ] ) ) ? '' : $meta_values[ $id ];
		$wpdb->update(
			$wpdb->prefix . "eshopbox_order_itemmeta",
			array(
				'meta_key' => $meta_key,
				'meta_value' => $meta_value
			),
			array( 'meta_id' => $id ),
			array( '%s', '%s' ),
			array( '%d' )
		);
	}

	// Order data saved, now get it so we can manipulate status
	$order = new WC_Order( $post_id );

	// Order status
	$order->update_status( $_POST['order_status'] );

	// Handle button actions
	if ( ! empty( $_POST['wc_order_action'] ) ) {

		$action = eshopbox_clean( $_POST['wc_order_action'] );

		if ( strstr( $action, 'send_email_' ) ) {

			do_action( 'eshopbox_before_resend_order_emails', $order );

			$mailer = $eshopbox->mailer();

			$email_to_send = str_replace( 'send_email_', '', $action );

			$mails = $mailer->get_emails();

			if ( ! empty( $mails ) ) {
				foreach ( $mails as $mail ) {
					if ( $mail->id == $email_to_send ) {
						$mail->trigger( $order->id );
					}
				}
			}

			do_action( 'eshopbox_after_resend_order_email', $order, $email_to_send );

		} else {

			do_action( 'eshopbox_order_action_' . sanitize_title( $action ), $order );

		}
	}

	delete_transient( 'eshopbox_processing_order_count' );
}

add_action( 'eshopbox_process_shop_order_meta', 'eshopbox_process_shop_order_meta', 10, 2 );
