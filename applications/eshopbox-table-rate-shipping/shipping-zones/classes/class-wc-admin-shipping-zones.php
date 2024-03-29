<?php
/**
 * WC_Admin_Shipping_zones class.
 */
class WC_Admin_Shipping_zones {

	/**
	 * admin_page function.
	 *
	 * @access public
	 * @return void
	 */
	function admin_page() {
		global $eshopbox;

		if ( method_exists( $eshopbox, 'shipping' ) )
			$eshopbox->shipping();

		if ( ! class_exists( 'WP_List_Table' ) )
			require_once( ABSPATH . 'eshop-admin/includes/class-wp-list-table.php' );

		if ( ! empty( $_GET['edit_zone'] ) )
			$this->edit_zone_page();

		elseif ( isset( $_GET['zone'] ) )
			$this->view_zone_page();

		else
			$this->list_zone_page();

		$eshopbox->add_inline_js("
			jQuery('.select_all').live('click', function(){
				jQuery(this).closest('div').find('select option').attr(\"selected\",\"selected\");
				jQuery(this).closest('div').find('select').trigger('liszt:updated');
				return false;
			});

			jQuery('.select_none').live('click', function(){
				jQuery(this).closest('div').find('select option').removeAttr(\"selected\");
				jQuery(this).closest('div').find('select').trigger('liszt:updated');
				return false;
			});

			jQuery('.select_us_states').live('click', function(){
				jQuery(this).closest('div').find('option[value=\"US:AK\"], option[value=\"US:AL\"], option[value=\"US:AZ\"], option[value=\"US:AR\"], option[value=\"US:CA\"], option[value=\"US:CO\"], option[value=\"US:CT\"], option[value=\"US:DE\"], option[value=\"US:DC\"], option[value=\"US:FL\"], option[value=\"US:GA\"], option[value=\"US:HI\"], option[value=\"US:ID\"], option[value=\"US:IL\"], option[value=\"US:IN\"], option[value=\"US:IA\"], option[value=\"US:KS\"], option[value=\"US:KY\"], option[value=\"US:LA\"], option[value=\"US:ME\"], option[value=\"US:MD\"], option[value=\"US:MA\"], option[value=\"US:MI\"], option[value=\"US:MN\"], option[value=\"US:MS\"], option[value=\"US:MO\"], option[value=\"US:MT\"], option[value=\"US:NE\"], option[value=\"US:NV\"], option[value=\"US:NH\"], option[value=\"US:NJ\"], option[value=\"US:NM\"], option[value=\"US:NY\"], option[value=\"US:NC\"], option[value=\"US:ND\"], option[value=\"US:OH\"], option[value=\"US:OK\"], option[value=\"US:OR\"], option[value=\"US:PA\"], option[value=\"US:RI\"], option[value=\"US:SC\"], option[value=\"US:SD\"], option[value=\"US:TN\"], option[value=\"US:TX\"], option[value=\"US:UT\"], option[value=\"US:VT\"], option[value=\"US:VA\"], option[value=\"US:WA\"], option[value=\"US:WV\"], option[value=\"US:WI\"], option[value=\"US:WY\"]').attr(\"selected\",\"selected\");
				jQuery(this).closest('div').find('select').trigger('liszt:updated');
				return false;
			});

			jQuery('.select_europe').live('click', function(){
				jQuery(this).closest('div').find('option[value=\"AL\"], option[value=\"AD\"], option[value=\"AM\"], option[value=\"AT\"], option[value=\"BY\"], option[value=\"BE\"], option[value=\"BA\"], option[value=\"BG\"], option[value=\"CH\"], option[value=\"CY\"], option[value=\"CZ\"], option[value=\"DE\"], option[value=\"DK\"], option[value=\"EE\"], option[value=\"ES\"], option[value=\"FO\"], option[value=\"FI\"], option[value=\"FR\"], option[value=\"GB\"], option[value=\"GE\"], option[value=\"GI\"], option[value=\"GR\"], option[value=\"HU\"], option[value=\"HR\"], option[value=\"IE\"], option[value=\"IS\"], option[value=\"IT\"], option[value=\"LT\"], option[value=\"LU\"], option[value=\"LV\"], option[value=\"MC\"], option[value=\"MK\"], option[value=\"MT\"], option[value=\"NO\"], option[value=\"NL\"], option[value=\"PO\"], option[value=\"PT\"], option[value=\"RO\"], option[value=\"RU\"], option[value=\"SE\"], option[value=\"SI\"], option[value=\"SK\"], option[value=\"SM\"], option[value=\"TR\"], option[value=\"UA\"], option[value=\"VA\"]').attr(\"selected\",\"selected\");
				jQuery(this).closest('div').find('select').trigger('liszt:updated');
				return false;
			});
		");
	}

	/**
	 * edit_zone_page function.
	 *
	 * @access public
	 * @return void
	 */
	function edit_zone_page() {

		$this->process_edit_shipping_zone_form( (int) $_GET['edit_zone'] );

		$this->edit_shipping_zone_form( (int) $_GET['edit_zone'] );

	}

	/**
	 * view_zone_page function.
	 *
	 * @access public
	 * @return void
	 */
	function view_zone_page() {

		$this->shipping_zone_methods( (int) $_GET['zone'] );

	}

	/**
	 * list_zone_page function.
	 *
	 * @access public
	 * @return void
	 */
	function list_zone_page() {

		$this->process_add_shipping_zone_form();
		?>
		<div class="wrap eshopbox">
			<div class="icon32 icon32-eshopbox-delivery" id="icon-eshopbox"><br /></div>
			<h2><?php _e( 'Shipping Zones', 'wc_table_rate' ); ?></h2>
			<div class="wc-col-container">
				<div class="wc-col-right">
					<div class="wc-col-wrap">
						<?php $this->list_shipping_zones(); ?>
					</div>
				</div>
				<div class="wc-col-left">
					<div class="wc-col-wrap">
						<?php $this->add_shipping_zone_form(); ?>
					</div>
				</div>
			</div>
		</div>
		<?php

	}

	/**
	 * list_shipping_zones function.
	 *
	 * @access public
	 * @return void
	 */
	function list_shipping_zones() {
		global $eshopbox;

		if ( ! class_exists( 'WC_Shipping_Zones_Table' ) )
			require_once( 'class-wc-shipping-zones-table.php' );

		echo '<form method="post">';

	 	$WC_Shipping_Zones_Table = new WC_Shipping_Zones_Table();
		$WC_Shipping_Zones_Table->prepare_items();
		$WC_Shipping_Zones_Table->display();

		echo '</form>';

		$eshopbox->add_inline_js("
			// Sorting
			jQuery('table.shippingzones tbody').sortable({
				items:'tr:not(:last-child)',
				cursor:'move',
				axis:'y',
				handle: 'td',
				scrollSensitivity:40,
				helper:function(e,ui){
					ui.children().each(function(){
						jQuery(this).width(jQuery(this).width());
					});
					ui.css('left', '0');
					return ui;
				},
				start:function(event,ui){
					ui.item.css('background-color','#f6f6f6');
				},
				stop:function(event,ui){
					ui.item.removeAttr('style');
				},
				update: function(event, ui) {
					jQuery('table.shippingzones tbody td').css('cursor','default');
					jQuery('table.shippingzones tbody').sortable('disable');

					// show spinner
					ui.item.find('.check-column input').hide();
					ui.item.find('.check-column').append('<img alt=\"processing\" src=\"images/wpspin_light.gif\" class=\"waiting\" style=\"margin-left: 6px;\" />');

					// Parent
					var zone_ids = [];

					jQuery(this).closest('form').find('input.zone_id').each(function(){
						var zone_id = jQuery(this).val();
						zone_ids.push(zone_id);
					});

					// go do the sorting stuff via ajax
					jQuery.post( ajaxurl, { action: 'eshopbox_zone_ordering', zone_ids: zone_ids }, function(response) {
						ui.item.find('.check-column input').show();
						ui.item.find('.check-column').find('img').remove();
						jQuery('table.shippingzones tbody td').css('cursor','move');
						jQuery('table.shippingzones tbody').sortable('enable');

					});

					// fix cell colors
					jQuery('table.shippingzones tbody tr').each(function(){
						var i = jQuery('table.shippingzones tbody tr').index(this);
						if ( i%2 == 0 ) jQuery(this).addClass('alternate');
						else jQuery(this).removeClass('alternate');
					});
				}
			});
		");
	}

	/**
	 * add_shipping_zone_form function.
	 *
	 * @access public
	 * @return void
	 */
	function add_shipping_zone_form() {
		global $eshopbox, $wpdb;
	 	?>
		<h3><?php _e( 'Add Shipping Zone', 'wc_table_rate' ); ?></h3>

		<p><?php _e( 'Zones cover multiple countries and states and can have shipping methods assigned to them. Each customer will be assigned a single matching shipping zone in order of priority, and if no zones apply the "default zone" will be used.', 'wc_table_rate' ); ?></p>

		<p><?php _e( 'If you wish to disable shipping for a location, add a zone for it and assign no shipping methods.', 'wc_table_rate' ); ?></p>

		<div class="form-wrap">
			<form id="add-zone" method="post">
				<div class="form-field">
					<label for="zone_name"><?php _e( 'Zone Name', 'wc_table_rate' ); ?></label>
					<input type="text" name="zone_name" id="zone_name" class="input-text" placeholder="<?php

						// Get count of zones (so we can insert it at the end)
						$zone_count = $wpdb->get_var( "SELECT COUNT( zone_id ) FROM {$wpdb->prefix}eshopbox_shipping_zones;" );

						echo __( 'Zone', 'wc_table_rate' ) . ' ' . ( $zone_count + 1 );

					?>" />
				</div>
				<div class="form-field">
					<label><?php _e( 'Type of zone', 'wc_table_rate' ); ?></label>
					<fieldset>
						<legend class="screen-reader-text"><span><?php _e( 'Zone type', 'wc_table_rate' ); ?></span></legend>

						<p><label><input type="radio" name="zone_type" value="countries" id="zone_type" class="input-radio" checked="checked" /> <?php _e( 'This shipping zone is based on one or more countries', 'wc_table_rate' ); ?></label></p>

						<div class="zone_type_options zone_type_countries">
							<select multiple="multiple" name="zone_type_countries[]" data-placeholder="<?php _e('Choose countries&hellip;', 'wc_table_rate'); ?>" class="chosen_select">
					        	<?php
					        		$countries = $eshopbox->countries->get_allowed_countries();

					        		if ( $countries )
					        			foreach ( $countries as $key => $val )
		                    				echo '<option value="' . $key . '">' . $val . '</option>';
		                    	?>
					        </select>
					        <p><button class="select_all button"><?php _e('All', 'wc_table_rate'); ?></button><button class="select_none button"><?php _e('None', 'wc_table_rate'); ?></button><button class="button select_europe"><?php _e('EU States', 'wc_table_rate'); ?></button></p>
				        </div>

						<p><label><input type="radio" name="zone_type" value="states" id="zone_type" class="input-radio" /> <?php _e( 'This shipping zone is based on one of more states and counties', 'wc_table_rate' ); ?></label></p>

						<div class="zone_type_options zone_type_states">
							<select multiple="multiple" name="zone_type_states[]" data-placeholder="<?php _e('Choose states/counties&hellip;', 'wc_table_rate'); ?>"  class="chosen_select">
		                   		<?php
		                   			$countries = $eshopbox->countries->get_allowed_countries();

		                   			foreach ( $countries as $key => $val ) {

		                   				echo '<option value="' . $key . '">' . $val . '</option>';

					        			if ( $states =  $eshopbox->countries->get_states( $key ) ) {
					        				foreach ($states as $state_key => $state_value ) {

								    			echo '<option value="' . $key . ':' . $state_key . '">' . $val . ' &gt; ' . $state_value . '</option>';

								    		}
					        			}

		                    		}
		                   		?>
		                	</select>
		                	<p><button class="select_all button"><?php _e('All', 'wc_table_rate'); ?></button><button class="select_none button"><?php _e('None', 'wc_table_rate'); ?></button><button class="button select_us_states"><?php _e('US States', 'wc_table_rate'); ?></button><button class="button select_europe"><?php _e('EU States', 'wc_table_rate'); ?></button></p>
				        </div>

						<p><label><input type="radio" name="zone_type" value="postcodes" id="zone_type" class="input-radio" /> <?php _e( 'This shipping zone is based on one of more postcodes/zips', 'wc_table_rate' ); ?></label></p>

						<div class="zone_type_options zone_type_postcodes">
							<select name="zone_type_postcodes" data-placeholder="<?php _e('Choose countries&hellip;', 'wc_table_rate'); ?>" title="Country" class="chosen_select" style="width:95%">
					        	<?php
					        		$countries = $eshopbox->countries->get_allowed_countries();
					        		$base = $eshopbox->countries->get_base_country();

		                   			foreach ( $countries as $key => $val ) {

		                   				echo '<option value="' . $key . '" ' . selected( $key, $base, false ) . '>' . $val . '</option>';

					        			if ( $states =  $eshopbox->countries->get_states( $key ) ) {
					        				foreach ($states as $state_key => $state_value ) {

								    			echo '<option value="' . $key . ':' . $state_key . '">' . $val . ' &gt; ' . $state_value . '</option>';

								    		}
					        			}

		                    		}
		                    	?>
					        </select>

					        <label for="postcodes"><?php _e( 'Postcodes', 'wc_table_rate' ); ?> <img class="help_tip" width="16" data-tip='<?php _e('List 1 postcode per line. Wildcards (*) and ranges (for numeric postcodes) are supported.', 'wc_table_rate') ?>' src="<?php echo $eshopbox->plugin_url(); ?>/assets/images/help.png" /></label>
					        <textarea name="postcodes" id="postcodes" class="input-text large-text" cols="25" rows="5"></textarea>

				        </div>

					</fieldset>
				</div>
				<p class="submit"><input type="submit" class="button plus" name="add_zone" value="<?php _e('Add shipping zone', 'wc_table_rate'); ?>" /></p>
				<?php wp_nonce_field( 'eshopbox_add_zone', 'eshopbox_add_zone_nonce' ); ?>
			</form>
		</div>
		<script type="text/javascript">
			jQuery(function(){

				jQuery("select.chosen_select").chosen();
				jQuery('.zone_type_options').hide();

				jQuery('input[name=zone_type]').change(function(){

					if ( jQuery(this).is(':checked') ) {

						var value = jQuery(this).val();

						jQuery('.zone_type_options').slideUp('fast');
						jQuery('.zone_type_' + value).slideDown('fast');

					}

				}).change();

			});
		</script>
		<?php
	}

	/**
	 * process_add_shipping_zone_form function.
	 *
	 * @access public
	 * @return void
	 */
	function process_add_shipping_zone_form() {

		if ( ! empty( $_POST['add_zone'] ) ) {

			if ( empty( $_POST['eshopbox_add_zone_nonce'] ) || ! wp_verify_nonce( $_POST['eshopbox_add_zone_nonce'], 'eshopbox_add_zone' )) {
				echo '<div class="updated error"><p>' . __('Edit failed. Please try again.', 'wc_table_rate') . '</p></div>';
				return;
			}

			global $eshopbox, $wpdb;

			$fields = array(
				'zone_name',
				'zone_type',
				'zone_type_countries',
				'zone_type_states',
				'zone_type_postcodes',
				'postcodes'
			);

			$data = array();

			foreach ( $fields as $field )
				$data[$field] = ( empty( $_POST[ $field ] ) ) ? '' : $_POST[ $field ];

			// Get count of zones (so we can insert it at the end)
			$zone_count = $wpdb->get_var( "SELECT COUNT( zone_id ) FROM {$wpdb->prefix}eshopbox_shipping_zones;" );

			// If name is left blank...
			if ( empty( $data['zone_name'] ) ) {
				$data['zone_name'] = __( 'Zone', 'wc_table_rate' ) . ' ' . ( $zone_count + 1 );
			}

			// Check required fields
			if ( empty( $data['zone_type'] ) ) {
				echo '<div class="updated error"><p>' . __('Zone type is required', 'wc_table_rate') . '</p></div>';
				return;
			}

			// Get name
			$data['zone_name'] = esc_attr( stripslashes( trim ( $data['zone_name'] ) ) );

			// Determine field we are saving
			$locations_field =  'zone_type_' . $data['zone_type'];

			// Get the countries into a nicely formatted array
			if ( ! $data[ $locations_field ] )
				$data[ $locations_field ] = array();

			if ( is_array( $data[ $locations_field ] ) )
				$data[ $locations_field ] = array_filter( array_map( 'strtoupper', array_map( 'esc_attr',  array_map( 'trim', $data[ $locations_field ] ) ) ) );
			else
				$data[ $locations_field ] = array( strtoupper( esc_attr( trim ( $data[ $locations_field ] ) ) ) );

			// Any set?
			if ( sizeof( $data[ $locations_field ] ) == 0 ) {
				echo '<div class="updated error"><p>' . __('You must choose at least 1 country to add a zone.', 'wc_table_rate') . '</p></div>';
				return;
			}

			// If dealing with a postcode, grab that field too
			if ( $data['zone_type'] == 'postcodes' ) {

				$data['postcodes'] = array_filter( array_unique( array_map( 'strtoupper', array_map( 'esc_attr', array_map( 'trim', explode( "\n", $data['postcodes'] ) ) ) ) ) );

				if ( sizeof( $data['postcodes'] ) == 0 ) {
					echo '<div class="updated error"><p>' . __('You must choose at least 1 postcode to add postcode zone.', 'wc_table_rate') . '</p></div>';
					return;
				}

			}

			// Insert zone
			$wpdb->insert(
				$wpdb->prefix . 'eshopbox_shipping_zones',
				array(
					'zone_name'			=> $data['zone_name'],
					'zone_enabled' 		=> 1,
					'zone_type'			=> $data['zone_type'],
					'zone_order'		=> ( $zone_count + 1 )
				),
				array(
					'%s',
					'%d',
					'%s',
					'%d'
				)
			);

			$zone_id = $wpdb->insert_id;

			if ( $zone_id > 0 ) {

				// Insert locations which apply to this zone
				foreach ( $data[ $locations_field ] as $code ) {

					if ( ! $code )
						continue;

					$wpdb->insert(
						$wpdb->prefix . 'eshopbox_shipping_zone_locations',
						array(
							'location_code'		=> $code,
							'location_type' 	=> strstr( $code, ':' ) ? 'state' : 'country',
							'zone_id'			=> $zone_id,
						),
						array(
							'%s',
							'%s',
							'%d'
						)
					);

				}

				// Save postcodes
				if ( $data['zone_type'] == 'postcodes' ) {

					foreach ( $data['postcodes'] as $code ) {

						if ( ! $code )
							continue;

						$wpdb->insert(
							$wpdb->prefix . 'eshopbox_shipping_zone_locations',
							array(
								'location_code'		=> $code,
								'location_type' 	=> 'postcode',
								'zone_id'			=> $zone_id,
							),
							array(
								'%s',
								'%s',
								'%d'
							)
						);

					}

				}

				echo '<div class="updated success"><p>' . __('Zone successfully added.', 'wc_table_rate') . '</p></div>';

			} else {
				echo '<div class="updated error"><p>' . __('Error inserting zone.', 'wc_table_rate') . '</p></div>';
				return;
			}

		}
	}

	/**
	 * edit_shipping_zone_form function.
	 *
	 * @access public
	 * @param mixed $zone_id
	 * @return void
	 */
	function edit_shipping_zone_form( $zone_id ) {
		global $eshopbox, $wpdb;

		// Load details to edit
		$zone = $wpdb->get_row( $wpdb->prepare( "
			SELECT * FROM {$wpdb->prefix}eshopbox_shipping_zones
			WHERE zone_id = %d LIMIT 1
		", $zone_id ) );

		$location_counties = $wpdb->get_col( $wpdb->prepare( "
			SELECT location_code FROM {$wpdb->prefix}eshopbox_shipping_zone_locations
			WHERE zone_id = %d AND location_type = 'country'
		", $zone_id ) );

		$location_states = $wpdb->get_col( $wpdb->prepare( "
			SELECT location_code FROM {$wpdb->prefix}eshopbox_shipping_zone_locations
			WHERE zone_id = %d AND location_type = 'state'
		", $zone_id ) );

		$location_postcodes = $wpdb->get_col( $wpdb->prepare( "
			SELECT location_code FROM {$wpdb->prefix}eshopbox_shipping_zone_locations
			WHERE zone_id = %d AND location_type = 'postcode'
		", $zone_id ) );

		$selected_states = array_merge( $location_states, $location_counties );

	 	?>
	 	<div class="wrap eshopbox">
			<div class="icon32 icon32-eshopbox-delivery" id="icon-eshopbox"><br /></div>
			<h2><?php _e( 'Edit Shipping Zone', 'wc_table_rate' ); ?> &mdash; <?php echo $zone->zone_name ?></h2><br class="clear" />
			<div class="form-wrap">
				<form id="add-zone" method="post">
					<table class="form-table">
						<tr>
							<th>
								<label for="zone_name"><?php _e( 'Name', 'wc_table_rate' ); ?></label>
							</th>
							<td>
								<input type="text" name="zone_name" id="zone_name" class="input-text" placeholder="<?php _e( 'Enter a name which describes this zone', 'wc_table_rate' ); ?>" value="<?php echo $zone->zone_name ?>" />
							</td>
						</tr>
						<tr>
							<th>
								<label for="zone_name"><?php _e( 'Enable', 'wc_table_rate' ); ?></label>
							</th>
							<td>
								<label><input type="checkbox" name="zone_enabled" value="1" id="zone_enabled" class="input-checkbox" <?php checked( $zone->zone_enabled, 1 ); ?> /> <?php _e( 'Enable this zone', 'wc_table_rate' ); ?></label>
							</td>
						</tr>
						<tr>
							<th>
								<?php _e( 'Type of zone', 'wc_table_rate' ); ?>
							</th>
							<td>
								<fieldset>
									<legend class="screen-reader-text"><span><?php _e( 'Zone type', 'wc_table_rate' ); ?></span></legend>

									<p><label><input type="radio" name="zone_type" value="countries" id="zone_type" class="input-radio" <?php checked( $zone->zone_type, 'countries' ); ?> /> <?php _e( 'This shipping zone is based on one or more countries', 'wc_table_rate' ); ?></label></p>

									<div class="zone_type_options zone_type_countries">
										<select multiple="multiple" name="zone_type_countries[]" style="width:450px;" data-placeholder="<?php _e('Choose countries&hellip;', 'wc_table_rate'); ?>" class="chosen_select">
								        	<?php
								        		$countries = $eshopbox->countries->get_allowed_countries();

								        		foreach ( $countries as $key => $val )
					                    			echo '<option value="' . $key . '" ' . selected( in_array( $key, $location_counties ) ) . '>' . $val . '</option>';
					                    	?>
								        </select>
								        <p><button class="select_all button"><?php _e('All', 'wc_table_rate'); ?></button><button class="select_none button"><?php _e('None', 'wc_table_rate'); ?></button><button class="button select_europe"><?php _e('EU States', 'wc_table_rate'); ?></button></p>
							        </div>

									<p><label><input type="radio" name="zone_type" value="states" id="zone_type" class="input-radio" <?php checked( $zone->zone_type, 'states' ); ?> /> <?php _e( 'This shipping zone is based on one of more states/counties', 'wc_table_rate' ); ?></label></p>

									<div class="zone_type_options zone_type_states">
										<select multiple="multiple" name="zone_type_states[]" style="width:450px;" data-placeholder="<?php _e('Choose states/counties&hellip;', 'wc_table_rate'); ?>"  class="chosen_select">
					                   		<?php
					                   			$countries = $eshopbox->countries->get_allowed_countries();

					                   			foreach ( $countries as $key => $val ) {

					                   				echo '<option value="' . $key . '" ' . selected( in_array( $key, $selected_states ), true, false ) . '>' . $val . '</option>';

								        			if ( $states =  $eshopbox->countries->get_states( $key ) ) {
								        				foreach ($states as $state_key => $state_value ) {

											    			echo '<option value="' . $key . ':' . $state_key . '" ' . selected( in_array( $key . ':' . $state_key, $selected_states ), true, false ) . '>' . $val . ' &gt; ' . $state_value . '</option>';

											    		}
								        			}

					                    		}
					                   		?>
					                	</select>
					                	<p><button class="select_all button"><?php _e('All', 'wc_table_rate'); ?></button><button class="select_none button"><?php _e('None', 'wc_table_rate'); ?></button><button class="button select_us_states"><?php _e('US States', 'wc_table_rate'); ?></button><button class="button select_europe"><?php _e('EU States', 'wc_table_rate'); ?></button></p>
							        </div>

									<p><label><input type="radio" name="zone_type" value="postcodes" id="zone_type" class="input-radio" <?php checked( $zone->zone_type, 'postcodes' ); ?> /> <?php _e( 'This shipping zone is based on one of more postcodes/zips', 'wc_table_rate' ); ?></label></p>

									<div class="zone_type_options zone_type_postcodes">
										<select name="zone_type_postcodes" style="width:450px;" data-placeholder="<?php _e('Choose countries&hellip;', 'wc_table_rate'); ?>" title="Country" class="chosen_select">
								        	<?php
								        		$countries = $eshopbox->countries->get_allowed_countries();

								                foreach ( $countries as $key => $val ) {

					                   				echo '<option value="' . $key . '" ' . selected( in_array( $key, $selected_states ), true, false ) . '>' . $val . '</option>';

								        			if ( $states =  $eshopbox->countries->get_states( $key ) ) {
								        				foreach ($states as $state_key => $state_value ) {

											    			echo '<option value="' . $key . ':' . $state_key . '" ' . selected( in_array( $key . ':' . $state_key, $selected_states ), true, false ) . '>' . $val . ' &gt; ' . $state_value . '</option>';

											    		}
								        			}

					                    		}
					                    	?>
								        </select>

								        <p>
								        	<label for="postcodes"><?php _e( 'Postcodes', 'wc_table_rate' ); ?> <img class="help_tip" width="16" data-tip='<?php _e('List 1 postcode per line. Wildcards (*) and ranges (for numeric postcodes) are supported.', 'wc_table_rate') ?>' src="<?php echo $eshopbox->plugin_url(); ?>/assets/images/help.png" /></label>
								        	<textarea name="postcodes" id="postcodes" class="input-text large-text" cols="25" rows="5"><?php
								        		foreach ( $location_postcodes as $location ) {
									        		echo $location . "\n";
								        		}
								        	?></textarea>
								        </p>
							        </div>

								</fieldset>
							</td>
						</tr>
					</table>
					<p class="submit">
						<input type="submit" class="button" name="edit_zone" value="<?php _e('Save changes', 'wc_table_rate'); ?>" />
						<?php wp_nonce_field( 'eshopbox_edit_zone', 'eshopbox_edit_zone_nonce' ); ?>
					</p>
				</form>
			</div>
			<script type="text/javascript">
				jQuery(function(){

					jQuery("select.chosen_select").chosen();
					jQuery('.zone_type_options').hide();

					jQuery('input[name=zone_type]').change(function(){

						if ( jQuery(this).is(':checked') ) {

							var value = jQuery(this).val();

							jQuery('.zone_type_options').slideUp('fast');
							jQuery('.zone_type_' + value).slideDown('fast');

						}

					}).change();

				});
			</script>
	 	</div>
		<?php
	}

	/**
	 * process_edit_shipping_zone_form function.
	 *
	 * @access public
	 * @return void
	 */
	function process_edit_shipping_zone_form( $zone_id ) {
		if ( ! empty( $_POST['edit_zone'] ) ) {

			if ( empty( $_POST['eshopbox_edit_zone_nonce'] ) || ! wp_verify_nonce( $_POST['eshopbox_edit_zone_nonce'], 'eshopbox_edit_zone' )) {
				echo '<div class="updated error"><p>' . __('Edit failed. Please try again.', 'wc_table_rate') . '</p></div>';
				return;
			}

			global $eshopbox, $wpdb;

			$fields = array(
				'zone_name',
				'zone_type',
				'zone_enabled',
				'zone_type_countries',
				'zone_type_states',
				'zone_type_postcodes',
				'postcodes'
			);

			$data = array();

			foreach ( $fields as $field )
				$data[$field] = ( empty( $_POST[ $field ] ) ) ? '' : $_POST[ $field ];

			// Enabled
			$data['zone_enabled'] = $data['zone_enabled'] ? 1 : 0;

			// If name is left blank...
			if ( empty( $data['zone_name'] ) ) {
				echo '<div class="updated error"><p>' . __('Zone name is required', 'wc_table_rate') . '</p></div>';
				return;
			}

			// Check required fields
			if ( empty( $data['zone_type'] ) ) {
				echo '<div class="updated error"><p>' . __('Zone type is required', 'wc_table_rate') . '</p></div>';
				return;
			}

			// Get name
			$data['zone_name'] = esc_attr( stripslashes( trim ( $data['zone_name'] ) ) );

			// Determine field we are saving
			$locations_field =  'zone_type_' . $data['zone_type'];

			// Get the countries into a nicely formatted array
			if ( ! $data[ $locations_field ] )
				$data[ $locations_field ] = array();

			if ( is_array( $data[ $locations_field ] ) )
				$data[ $locations_field ] = array_filter( array_map( 'strtoupper', array_map( 'esc_attr',  array_map( 'trim', $data[ $locations_field ] ) ) ) );
			else
				$data[ $locations_field ] = array( strtoupper( esc_attr( trim ( $data[ $locations_field ] ) ) ) );

			// Any set?
			if ( sizeof( $data[ $locations_field ] ) == 0 ) {
				echo '<div class="updated error"><p>' . __('You must choose at least 1 country to add a zone.', 'wc_table_rate') . '</p></div>';
				return;
			}

			// If dealing with a postcode, grab that field too
			if ( $data['zone_type'] == 'postcodes' ) {

				$data['postcodes'] = array_filter( array_unique( array_map( 'strtoupper', array_map( 'esc_attr', array_map( 'trim', explode( "\n", $data['postcodes'] ) ) ) ) ) );

				if ( sizeof( $data['postcodes'] ) == 0 ) {
					echo '<div class="updated error"><p>' . __('You must choose at least 1 postcode to add postcode zone.', 'wc_table_rate') . '</p></div>';
					return;
				}

			} else {
				$data['postcodes'] = array();
			}

			// Update zone
			$wpdb->update(
				$wpdb->prefix . 'eshopbox_shipping_zones',
				array(
					'zone_name' 		=> $data['zone_name'],
					'zone_enabled' 		=> $data['zone_enabled'],
					'zone_type' 		=> $data['zone_type'],
				),
				array( 'zone_id' => $zone_id ),
				array(
					'%s',
					'%d',
					'%s'
				),
				array( '%d' )
			);

			$locations = $wpdb->get_col( $wpdb->prepare( "
				SELECT location_code FROM {$wpdb->prefix}eshopbox_shipping_zone_locations
				WHERE zone_id = %d
			", $zone_id ) );

			$new_locations = array_merge( $data[ $locations_field ], $data['postcodes'] );

			if ( array_diff( $locations, $new_locations ) || array_diff( $new_locations, $locations ) ) {

				// Remove locations
				$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}eshopbox_shipping_zone_locations WHERE zone_id = %s", $zone_id ) );

				// Insert locations which apply to this zone
				foreach ( $data[ $locations_field ] as $code ) {

					if ( ! $code )
						continue;

					$wpdb->insert(
						$wpdb->prefix . 'eshopbox_shipping_zone_locations',
						array(
							'location_code'		=> $code,
							'location_type' 	=> strstr( $code, ':' ) ? 'state' : 'country',
							'zone_id'			=> $zone_id,
						),
						array(
							'%s',
							'%s',
							'%d'
						)
					);

				}

				// Save postcodes
				if ( $data['zone_type'] == 'postcodes' ) {

					foreach ( $data['postcodes'] as $code ) {

						if ( ! $code )
							continue;

						$wpdb->insert(
							$wpdb->prefix . 'eshopbox_shipping_zone_locations',
							array(
								'location_code'		=> $code,
								'location_type' 	=> 'postcode',
								'zone_id'			=> $zone_id,
							),
							array(
								'%s',
								'%s',
								'%d'
							)
						);

					}

				}

			}

			wp_redirect( remove_query_arg( 'edit_zone' ) );
			exit;

		}
	}

	/**
	 * shipping_zone_methods function.
	 *
	 * @access public
	 * @return void
	 */
	function shipping_zone_methods( $zone_id ) {
		global $eshopbox, $wpdb;

		$zone = new WC_Shipping_Zone( $zone_id );

		if ( ! $zone->exists() ) {
			wp_redirect( remove_query_arg( 'zone' ) );
			exit;
		}

		if ( ! empty( $_GET['add_method'] ) && ! empty( $_GET['method_type'] ) && ! empty( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'eshopbox_add_method' ) ) {

			$type = strtolower( trim( $_GET['method_type'] ) );

			if ( $method_id = $zone->add_shipping_method( $type ) ) {

				wp_redirect( add_query_arg( 'method', $method_id, add_query_arg( 'zone', $zone_id, admin_url( 'admin.php?page=shipping_zones' ) ) ) );
				exit;

				//echo '<div class="updated success"><p>' . __( 'Shipping method successfully added', 'wc_table_rate' ) . ' <a href="' . add_query_arg( 'method', $method_id, add_query_arg( 'zone', $zone_id, admin_url( 'admin.php?page=shipping_zones' ) ) ) . '" class="button" style="margin-left: 10px;">' . __( 'Configure method', 'wc_table_rate' ) . '</a></p></div>';

			}
		}

		if ( ! empty( $_GET['delete_method'] ) && ! empty( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'eshopbox_delete_method' ) ) {

			$method_id = absint( $_GET['delete_method'] );

			if ( $zone->delete_shipping_method( $method_id ) ) {

				echo '<div class="updated success"><p>' . __( 'Shipping method successfully deleted', 'wc_table_rate' ) . '</p></div>';

			}

		}

		if ( ! empty( $_GET['method'] ) && $_GET['method'] > 0 ) {

			$method_id = (int) $_GET['method'];

			$this->shipping_zone_method_settings( $zone, $method_id );

			return;
		}
		?>
		<div class="wrap eshopbox">
			<div class="icon32 icon32-eshopbox-delivery" id="icon-eshopbox"><br /></div>
			<h2><a href="<?php echo admin_url('admin.php?page=shipping_zones'); ?>"><?php _e( 'Shipping Zones', 'wc_table_rate' ); ?></a> &gt; <?php echo $zone->zone_name ?> <a href="<?php echo wp_nonce_url( add_query_arg( 'method_type', 'table_rate', add_query_arg( 'add_method', 'true' ) ), 'eshopbox_add_method' ) ?>" class="add-new-h2"><?php _e( 'Add Shipping Method', 'wc_table_rate' ); ?></a></h2>

			<?php $this->list_shipping_zone_methods(); ?>

		</div>
		<?php
		$eshopbox->add_inline_js("
			// Delete
			jQuery('a.submitdelete').click( function(){
				var answer = confirm('" . __('Are you sure you want to delete this method?', 'wc_table_rate') . "');
				if ( answer ) {
					return true;
				}
				return false;
			});
		");

	}

	/**
	 * list_shipping_zone_methods function.
	 *
	 * @access public
	 * @return void
	 */
	function list_shipping_zone_methods() {
		global $eshopbox;

		if ( ! class_exists( 'WC_Shipping_Zone_Methods_Table' ) ) require_once( 'class-wc-shipping-zone-methods-table.php' );

		echo '<form method="post">';

	 	$WC_Shipping_Zone_Methods_Table = new WC_Shipping_Zone_Methods_Table();
		$WC_Shipping_Zone_Methods_Table->prepare_items();
		$WC_Shipping_Zone_Methods_Table->display();

		echo '</form>';
	}

	/**
	 * shipping_zone_method_settings function.
	 *
	 * @access public
	 * @param mixed $zone
	 * @param mixed $method_id
	 * @return void
	 */
	function shipping_zone_method_settings( $zone, $method_id ) {
		global $eshopbox, $wpdb;

		// Get method
		$method = $wpdb->get_row( $wpdb->prepare( "
			SELECT * FROM {$wpdb->prefix}eshopbox_shipping_zone_shipping_methods WHERE shipping_method_id = %s
		", $method_id ) );

		$callback = 'eshopbox_get_shipping_method_' . $method->shipping_method_type;

		if ( ! function_exists( $callback ) ) return;

		// Construct method instance
		$shipping_method = $callback( $method_id );

		if ( ! empty( $_POST['save_method'] ) ) {

			if ( empty( $_POST['eshopbox_save_method_nonce'] ) || ! wp_verify_nonce( $_POST['eshopbox_save_method_nonce'], 'eshopbox_save_method' )) {

				echo '<div class="updated error"><p>' . __('Edit failed. Please try again.', 'wc_table_rate') . '</p></div>';

			} elseif ( $shipping_method->process_instance_options() ) {

				// re-init so we re-load settings
				unset( $shipping_method );
				$shipping_method = $callback( $method_id );

				echo '<div class="updated success"><p>' . __('Shipping method saved successfully.', 'wc_table_rate') . '</p></div>';
			}

		}
		?>
		<div class="wrap eshopbox">
			<div class="icon32 icon32-eshopbox-delivery" id="icon-eshopbox"><br /></div>
			<h2><a href="<?php echo admin_url('admin.php?page=shipping_zones'); ?>"><?php _e( 'Shipping Zones', 'wc_table_rate' ); ?></a> &gt; <a href="<?php echo remove_query_arg( 'method' ); ?>"><?php echo $zone->zone_name ?></a> &gt; <?php echo $shipping_method->title; ?></h2><br class="clear" />
			<form id="add-method" method="post">
				<?php $shipping_method->instance_options(); ?>
				<p class="submit"><input type="submit" class="button" name="save_method" value="<?php _e('Save shipping method', 'wc_table_rate'); ?>" /></p>
				<?php wp_nonce_field( 'eshopbox_save_method', 'eshopbox_save_method_nonce' ); ?>
			</form>
		</div>
		<?php
	}

}