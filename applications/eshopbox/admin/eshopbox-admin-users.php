<?php
/**
 * Admin user functions
 *
 * Functions used for modifying the users panel in admin.
 *
 * @author 		WooThemes
 * @category 	Admin
 * @package 	EshopBox/Admin/Users
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Define columns to show on the users page.
 *
 * @access public
 * @param array $columns Columns on the manage users page
 * @return array The modified columns
 */
function eshopbox_user_columns( $columns ) {
	if ( ! current_user_can( 'manage_eshopbox' ) )
		return $columns;

	$columns['eshopbox_billing_address'] = __( 'Billing Address', 'eshopbox' );
	$columns['eshopbox_shipping_address'] = __( 'Shipping Address', 'eshopbox' );
	$columns['eshopbox_paying_customer'] = __( 'Paying Customer?', 'eshopbox' );
	$columns['eshopbox_order_count'] = __( 'Completed Orders', 'eshopbox' );
	return $columns;
}

add_filter( 'manage_users_columns', 'eshopbox_user_columns', 10, 1 );


/**
 * Define values for custom columns.
 *
 * @access public
 * @param mixed $value The value of the column being displayed
 * @param mixed $column_name The name of the column being displayed
 * @param mixed $user_id The ID of the user being displayed
 * @return string Value for the column
 */
function eshopbox_user_column_values( $value, $column_name, $user_id ) {
	global $eshopbox, $wpdb;
	switch ( $column_name ) :
		case "eshopbox_order_count" :

			if ( ! $count = get_user_meta( $user_id, '_order_count', true ) ) {

				$count = $wpdb->get_var( "SELECT COUNT(*)
					FROM $wpdb->posts as posts

					LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
					LEFT JOIN {$wpdb->term_relationships} AS rel ON posts.ID=rel.object_ID
					LEFT JOIN {$wpdb->term_taxonomy} AS tax USING( term_taxonomy_id )
					LEFT JOIN {$wpdb->terms} AS term USING( term_id )

					WHERE 	meta.meta_key 		= '_customer_user'
					AND 	posts.post_type 	= 'shop_order'
					AND 	posts.post_status 	= 'publish'
					AND 	tax.taxonomy		= 'shop_order_status'
					AND		term.slug			IN ( 'completed' )
					AND 	meta_value 			= $user_id
				" );

				update_user_meta( $user_id, '_order_count', $count );
			}

			$value = '<a href="' . admin_url( 'edit.php?post_status=all&post_type=shop_order&shop_order_status=completed&_customer_user=' . absint( $user_id ) . '' ) . '">' . absint( $count ) . '</a>';

		break;
		case "eshopbox_billing_address" :
			$address = apply_filters( 'eshopbox_user_column_billing_address', array(
				'first_name' 	=> get_user_meta( $user_id, 'billing_first_name', true ),
				'last_name'		=> get_user_meta( $user_id, 'billing_last_name', true ),
				'company'		=> get_user_meta( $user_id, 'billing_company', true ),
				'address_1'		=> get_user_meta( $user_id, 'billing_address_1', true ),
				'address_2'		=> get_user_meta( $user_id, 'billing_address_2', true ),
				'city'			=> get_user_meta( $user_id, 'billing_city', true ),
				'state'			=> get_user_meta( $user_id, 'billing_state', true ),
				'postcode'		=> get_user_meta( $user_id, 'billing_postcode', true ),
				'country'		=> get_user_meta( $user_id, 'billing_country', true )
			), $user_id );

			$formatted_address = $eshopbox->countries->get_formatted_address( $address );

			if ( ! $formatted_address )
				$value = __( 'N/A', 'eshopbox' );
			else
				$value = $formatted_address;

			$value = wpautop( $value );
		break;
		case "eshopbox_shipping_address" :
			$address = apply_filters( 'eshopbox_user_column_shipping_address', array(
				'first_name' 	=> get_user_meta( $user_id, 'shipping_first_name', true ),
				'last_name'		=> get_user_meta( $user_id, 'shipping_last_name', true ),
				'company'		=> get_user_meta( $user_id, 'shipping_company', true ),
				'address_1'		=> get_user_meta( $user_id, 'shipping_address_1', true ),
				'address_2'		=> get_user_meta( $user_id, 'shipping_address_2', true ),
				'city'			=> get_user_meta( $user_id, 'shipping_city', true ),
				'state'			=> get_user_meta( $user_id, 'shipping_state', true ),
				'postcode'		=> get_user_meta( $user_id, 'shipping_postcode', true ),
				'country'		=> get_user_meta( $user_id, 'shipping_country', true )
			), $user_id );

			$formatted_address = $eshopbox->countries->get_formatted_address( $address );

			if ( ! $formatted_address )
				$value = __( 'N/A', 'eshopbox' );
			else
				$value = $formatted_address;

			$value = wpautop( $value );
		break;
		case "eshopbox_paying_customer" :

			$paying_customer = get_user_meta( $user_id, 'paying_customer', true );

			if ( $paying_customer )
				$value = '<img src="' . $eshopbox->plugin_url() . '/assets/images/success@2x.png" alt="yes" width="16px" />';
			else
				$value = '-';

		break;
	endswitch;
	return $value;
}

add_action( 'manage_users_custom_column', 'eshopbox_user_column_values', 10, 3 );


/**
 * Get Address Fields for the edit user pages.
 *
 * @access public
 * @return array Fields to display which are filtered through eshopbox_customer_meta_fields before being returned
 */
function eshopbox_get_customer_meta_fields() {
	$show_fields = apply_filters('eshopbox_customer_meta_fields', array(
		'billing' => array(
			'title' => __( 'Customer Billing Address', 'eshopbox' ),
			'fields' => array(
				'billing_first_name' => array(
						'label' => __( 'First name', 'eshopbox' ),
						'description' => ''
					),
				'billing_last_name' => array(
						'label' => __( 'Last name', 'eshopbox' ),
						'description' => ''
					),
				'billing_company' => array(
						'label' => __( 'Company', 'eshopbox' ),
						'description' => ''
					),
				'billing_address_1' => array(
						'label' => __( 'Address 1', 'eshopbox' ),
						'description' => ''
					),
				'billing_address_2' => array(
						'label' => __( 'Address 2', 'eshopbox' ),
						'description' => ''
					),
				'billing_city' => array(
						'label' => __( 'City', 'eshopbox' ),
						'description' => ''
					),
				'billing_postcode' => array(
						'label' => __( 'Postcode', 'eshopbox' ),
						'description' => ''
					),
				'billing_state' => array(
						'label' => __( 'State/County', 'eshopbox' ),
						'description' => __( 'Country or state code', 'eshopbox' ),
					),
				'billing_country' => array(
						'label' => __( 'Country', 'eshopbox' ),
						'description' => __( '2 letter Country code', 'eshopbox' ),
					),
				'billing_phone' => array(
						'label' => __( 'Telephone', 'eshopbox' ),
						'description' => ''
					),
				'billing_email' => array(
						'label' => __( 'Email', 'eshopbox' ),
						'description' => ''
					)
			)
		),
		'shipping' => array(
			'title' => __( 'Customer Shipping Address', 'eshopbox' ),
			'fields' => array(
				'shipping_first_name' => array(
						'label' => __( 'First name', 'eshopbox' ),
						'description' => ''
					),
				'shipping_last_name' => array(
						'label' => __( 'Last name', 'eshopbox' ),
						'description' => ''
					),
				'shipping_company' => array(
						'label' => __( 'Company', 'eshopbox' ),
						'description' => ''
					),
				'shipping_address_1' => array(
						'label' => __( 'Address 1', 'eshopbox' ),
						'description' => ''
					),
				'shipping_address_2' => array(
						'label' => __( 'Address 2', 'eshopbox' ),
						'description' => ''
					),
				'shipping_city' => array(
						'label' => __( 'City', 'eshopbox' ),
						'description' => ''
					),
				'shipping_postcode' => array(
						'label' => __( 'Postcode', 'eshopbox' ),
						'description' => ''
					),
				'shipping_state' => array(
						'label' => __( 'State/County', 'eshopbox' ),
						'description' => __( 'State/County or state code', 'eshopbox' )
					),
				'shipping_country' => array(
						'label' => __( 'Country', 'eshopbox' ),
						'description' => __( '2 letter Country code', 'eshopbox' )
					)
			)
		)
	));
	return $show_fields;
}


/**
 * Show Address Fields on edit user pages.
 *
 * @access public
 * @param mixed $user User (object) being displayed
 * @return void
 */
function eshopbox_customer_meta_fields( $user ) {
	if ( ! current_user_can( 'manage_eshopbox' ) )
		return;

	$show_fields = eshopbox_get_customer_meta_fields();

	foreach( $show_fields as $fieldset ) :
		?>
		<h3><?php echo $fieldset['title']; ?></h3>
		<table class="form-table">
			<?php
			foreach( $fieldset['fields'] as $key => $field ) :
				?>
				<tr>
					<th><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ); ?></label></th>
					<td>
						<input type="text" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( get_user_meta( $user->ID, $key, true ) ); ?>" class="regular-text" /><br/>
						<span class="description"><?php echo wp_kses_post( $field['description'] ); ?></span>
					</td>
				</tr>
				<?php
			endforeach;
			?>
		</table>
		<?php
	endforeach;
}

add_action( 'show_user_profile', 'eshopbox_customer_meta_fields' );
add_action( 'edit_user_profile', 'eshopbox_customer_meta_fields' );


/**
 * Save Address Fields on edit user pages
 *
 * @access public
 * @param mixed $user_id User ID of the user being saved
 * @return void
 */
function eshopbox_save_customer_meta_fields( $user_id ) {
	if ( ! current_user_can( 'manage_eshopbox' ) )
		return $columns;

 	$save_fields = eshopbox_get_customer_meta_fields();

 	foreach( $save_fields as $fieldset )
 		foreach( $fieldset['fields'] as $key => $field )
 			if ( isset( $_POST[ $key ] ) )
 				update_user_meta( $user_id, $key, eshopbox_clean( $_POST[ $key ] ) );
}

add_action( 'personal_options_update', 'eshopbox_save_customer_meta_fields' );
add_action( 'edit_user_profile_update', 'eshopbox_save_customer_meta_fields' );