<?php
/*
Plugin Name: eshopbox Print Invoice/Packing list
Plugin URI: http://woothemes.com/eshopbox
Description: This plugin provides invoice/packing list printing possibility from the backend.
Version: 2.2.6
Author: Ilari Mäkelä
Author URI: http://i28.fi/
*/

/*  Copyright 2011  Ilari Mäkelä  (email : ilari@i28.fi)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

/**
 * Required functions
 */
if ( ! function_exists( 'woothemes_queue_update' ) )
	require_once( 'woo-includes/woo-functions.php' );

/**
 * Plugin updates
 **/
woothemes_queue_update( plugin_basename( __FILE__ ), '465de1126817cdfb42d97ebca7eea717', '18666' );

//if (!(is_eshopbox_active())) {

  register_activation_hook( __FILE__, 'eshopbox_pip_activate');

  /**
   * Localisation
   */
  load_plugin_textdomain('eshopbox-pip', false, dirname( plugin_basename( __FILE__ ) ) . '/languages');

  /**
   * Add needed action and filter hooks.
   */
  add_action('manage_shop_order_posts_custom_column', 'eshopbox_pip_alter_order_actions', 3);
  add_action('admin_init', 'eshopbox_pip_window');
  add_action('init', 'eshopbox_pip_client_window');
  add_action('wp_enqueue_scripts', 'eshopbox_pip_client_scripts');
  add_action('admin_menu', 'eshopbox_pip_admin_menu');
  add_action('add_meta_boxes', 'eshopbox_pip_add_box');
  add_action('admin_print_scripts-edit.php', 'eshopbox_pip_scripts');
  add_action('admin_print_scripts-post.php', 'eshopbox_pip_scripts');
  add_action('admin_enqueue_scripts', 'eshopbox_pip_admin_scripts');
  add_action('eshopbox_payment_complete', 'eshopbox_pip_send_email');
  add_action('eshopbox_order_status_on-hold_to_completed', 'eshopbox_pip_send_email');
  add_action('eshopbox_order_status_failed_to_completed', 'eshopbox_pip_send_email');
  add_action('admin_footer', 'eshopbox_pip_bulk_admin_footer', 10);
  add_action('load-edit.php', 'eshopbox_pip_order_bulk_action');
  add_filter('eshopbox_my_account_my_orders_actions', 'eshopbox_pip_my_orders_action', 10, 2);


  /**
   * Initialize settings
   */
  function eshopbox_pip_activate() {
    if (!get_option('eshopbox_pip_invoice_start')) {
      update_option('eshopbox_pip_invoice_start', '1');
    }
  }

  /**
	 * Plugin specific admin side scripts
	 */
  function eshopbox_pip_scripts() {
    // Version number for scripts
    $version = '2.2';
    wp_register_script( 'eshopbox-pip-js', plugins_url( '/js/eshopbox-pip.js', __FILE__ ), array('jquery'), $version );
	  wp_enqueue_script( 'eshopbox-pip-js');
	}

	/**
	 * Plugin specific client side scripts
	 */
	function eshopbox_pip_client_scripts() {
  	// Version number for scripts
  	$version = '2.2';
	  wp_register_script( 'eshopbox-pip-client-js', plugins_url( '/js/eshopbox-pip-client.js', __FILE__ ), array('jquery'), $version, true );
	  if (is_page( get_option( 'eshopbox_view_order_page_id' ) ) ) {
	    wp_enqueue_script( 'eshopbox-pip-client-js');
	  }
	}

  /**
   * Plugin specific settings page scripts
   */
  function eshopbox_pip_admin_scripts($hook) {
    global $pip_settings_page;

    if( $hook != $pip_settings_page )
      return;

    // Version number for scripts
    $version = '2.2';
    wp_register_script( 'eshopbox-pip-admin-js', plugins_url( '/js/eshopbox-pip-admin.js', __FILE__ ), array('jquery'), $version );
    wp_register_script( 'eshopbox-pip-validate', plugins_url( '/js/jquery.validate.min.js', __FILE__ ), array('jquery'), $version );
    wp_enqueue_script( 'eshopbox-pip-admin-js');
    wp_enqueue_script( 'eshopbox-pip-validate');
  }

  /**
	 * WordPress Administration Menu
	 */
	function eshopbox_pip_admin_menu() {
    global $pip_settings_page;
		$pip_settings_page = add_submenu_page('eshopbox', __( 'PIP settings', 'eshopbox-pip' ), __( 'PIP settings', 'eshopbox-pip' ), 'manage_eshopbox', 'eshopbox_pip', 'eshopbox_pip_page' );

	}

  /**
   * Add extra bulk action options to print invoices and packing lists.
   * Using Javascript until WordPress core fixes: http://core.trac.wordpress.org/ticket/16031
   */
  function eshopbox_pip_bulk_admin_footer() {
    global $post_type;

    if ( 'shop_order' == $post_type ) {
      ?>
      <script type="text/javascript">
      jQuery(document).ready(function() {
        jQuery('<option>').val('print_invoice').text('<?php _e( 'Print invoice', 'eshopbox-pip' )?>').appendTo("select[name='action']");
        jQuery('<option>').val('print_invoice').text('<?php _e( 'Print invoice', 'eshopbox-pip' )?>').appendTo("select[name='action2']");

        jQuery('<option>').val('print_packing').text('<?php _e( 'Print packing list', 'eshopbox-pip' )?>').appendTo("select[name='action']");
        jQuery('<option>').val('print_packing').text('<?php _e( 'Print packing list', 'eshopbox-pip' )?>').appendTo("select[name='action2']");
      });
      </script>
      <?php
    }
  }

  /**
   * Add HTML invoice button to my orders page so customers can view their invoices.
   */
  function eshopbox_pip_my_orders_action($actions, $order) {
    if ( in_array( $order->status, array( 'processing', 'completed' ) ) ) {
      $actions[] = array(
        'url'  => wp_nonce_url(site_url('?print_pip_invoice=true&post='.$order->id), 'client-print-pip'),
        'name' => __( 'HTML invoice', 'eshopbox-pip' )
      );
    }
    return $actions;
  }

	/**
	 * WordPress Settings Page
	 */
	function eshopbox_pip_page() {
	  // Check the user capabilities
		if ( !current_user_can( 'manage_eshopbox' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.', 'eshopbox-pip' ) );
		}
		// Load needed WP resources for media uploader
		wp_enqueue_media();

		// Save the field values
		if ( isset( $_POST['pip_fields_submitted'] ) && $_POST['pip_fields_submitted'] == 'submitted' ) {
			foreach ( $_POST as $key => $value ) {
			  if ($key == 'eshopbox_pip_invoice_start') {
			    if ($_POST['eshopbox_pip_reset_start'] == 'Yes') {
			      update_option( $key, ltrim($value, "0") );
			    }
			  }
			  elseif ($key == 'eshopbox_pip_reset_start') { }
			  else {
				  if ( get_option( $key ) != $value ) {
					  update_option( $key, $value );
				  }
				  else {
					  add_option( $key, $value, '', 'no' );
				  }
				}
			}
		}
		?>
		<div class="wrap">
			<div id="icon-options-general" class="icon32">
				<br />
			</div>
			<h2><?php _e( 'WooCommerce - Print invoice/packing list settings', 'eshopbox-pip' ); ?></h2>
			<?php if ( isset( $_POST['pip_fields_submitted'] ) && $_POST['pip_fields_submitted'] == 'submitted' ) { ?>
			<div id="message" class="updated fade"><p><strong><?php _e( 'Your settings have been saved.', 'eshopbox-pip' ); ?></strong></p></div>
			<?php } ?>
			<p><?php _e( 'Change settings for print invoice/packing list.', 'eshopbox-pip' ); ?></p>
			<div id="content">
			  <form method="post" action="" id="pip_settings">
				  <input type="hidden" name="pip_fields_submitted" value="submitted">
				  <div id="poststuff">
						<div class="postbox">
							<h3 class="hndle"><?php _e( 'Settings', 'eshopbox-pip' ); ?></h3>
							<div class="inside pip-preview">
							  <table class="form-table">
							    <tr>
    								<th>
    									<label for="eshopbox_pip_company_name"><b><?php _e( 'Company name:', 'eshopbox-pip' ); ?></b></label>
    								</th>
    								<td>
    									<input type="text" name="eshopbox_pip_company_name" class="regular-text" value="<?php echo stripslashes(get_option( 'eshopbox_pip_company_name' )); ?>" /><br />
    									<span class="description"><?php
    										echo __( 'Your custom company name for the print.', 'eshopbox-pip' );
    										echo '<br /><strong>' . __( 'Note:', 'eshopbox-pip' ) . '</strong> ';
    										echo __( 'Leave blank to not to print a company name.', 'eshopbox-pip' );
    									?></span>
    								</td>
    							</tr>
    							<tr>
    								<th>
    									<label for="eshopbox_pip_logo"><b><?php _e( 'Custom logo:', 'eshopbox-pip' ); ?></b></label>
    								</th>
    								<td>
    									<input id="eshopbox_pip_logo" type="text" size="36" name="eshopbox_pip_logo" value="<?php echo get_option( 'eshopbox_pip_logo' ); ?>" />
    									<input id="upload_image_button" type="button" value="<?php _e( 'Upload Image', 'eshopbox-pip' ); ?>" />
                      <br />
    									<span class="description"><?php
    										echo __( 'Your custom logo for the print.', 'eshopbox-pip' );
    										echo '<br /><strong>' . __( 'Note:', 'eshopbox-pip' ) . '</strong> ';
    										echo __( 'Leave blank to not to use a custom logo.', 'eshopbox-pip' );
    									?></span>
    								</td>
    							</tr>
    							<tr>
    								<th>
    									<label for="eshopbox_pip_company_extra"><b><?php _e( 'Company extra info:', 'eshopbox-pip' ); ?></b></label>
    								</th>
    								<td>
    									<textarea name="eshopbox_pip_company_extra" cols="45" rows="3" class="regular-text"><?php echo stripslashes(get_option( 'eshopbox_pip_company_extra' )); ?></textarea><br />
    									<span class="description"><?php
    										echo __( 'Some extra info that is displayed under company name.', 'eshopbox-pip' );
    										echo '<br /><strong>' . __( 'Note:', 'eshopbox-pip' ) . '</strong> ';
    										echo __( 'Leave blank to not to print the info.', 'eshopbox-pip' );
    									?></span>
    								</td>
    							</tr>
    							<tr>
    								<th>
    									<label for="eshopbox_pip_return_policy"><b><?php _e( 'Returns Policy, Conditions, etc.:', 'eshopbox-pip' ); ?></b></label>
    								</th>
    								<td>
    								  <textarea name="eshopbox_pip_return_policy" cols="45" rows="3" class="regular-text"><?php echo stripslashes(get_option( 'eshopbox_pip_return_policy' )); ?></textarea><br />
    									<span class="description"><?php
    										echo __( 'Here you can add some policies, conditions etc. For example add a returns policy in case the client would like to send back some goods.', 'eshopbox-pip' );
    										echo '<br /><strong>' . __( 'Note:', 'eshopbox-pip' ) . '</strong> ';
    										echo __( 'Leave blank to not to print any policy.', 'eshopbox-pip' );
    									?></span>
    								</td>
    							</tr>

                                                            							<tr>
    								<th>
    									<label for="eshopbox_pip_return_address"><b><?php _e( 'Returns Address:', 'eshopbox-pip' ); ?></b></label>
    								</th>
    								<td>
    								  <textarea name="eshopbox_pip_return_address" cols="45" rows="3" class="regular-text"><?php echo stripslashes(get_option( 'eshopbox_pip_return_address' )); ?></textarea><br />
    									<span class="description"><?php
    										echo __( 'Return address as displayed on your packing slip.', 'eshopbox-pip' );
    										echo '<br /><strong>' . __( 'Note:', 'eshopbox-pip' ) . '</strong> ';
    										//echo __( 'Leave blank to not to print any policy.', 'eshopbox-pip' );
    									?></span>
    								</td>
    							</tr>

    							<tr>
    								<th>
    									<label for="eshopbox_pip_footer"><b><?php _e( 'Custom footer:', 'eshopbox-pip' ); ?></b></label>
    								</th>
    								<td>
    									<textarea name="eshopbox_pip_footer" cols="45" rows="3" class="regular-text"><?php echo stripslashes(get_option( 'eshopbox_pip_footer' )); ?></textarea><br />
    									<span class="description"><?php
    										echo __( 'Your custom footer for the print.', 'eshopbox-pip' );
    										echo '<br /><strong>' . __( 'Note:', 'eshopbox-pip' ) . '</strong> ';
    										echo __( 'Leave blank to not to print a footer.', 'eshopbox-pip' );
    									?></span>
    								</td>
    							</tr>
                                                        
                                                        <tr>
    								<th>
    									<label for="eshopbox_pip_footer"><b><?php _e( 'Custom footer:', 'eshopbox-pip' ); ?></b></label>
    								</th>
    								<td>
    									<textarea name="eshopbox_pip_footer" cols="45" rows="3" class="regular-text"><?php echo stripslashes(get_option( 'eshopbox_pip_footer' )); ?></textarea><br />
    									<span class="description"><?php
    										echo __( 'Your custom footer for the print.', 'eshopbox-pip' );
    										echo '<br /><strong>' . __( 'Note:', 'eshopbox-pip' ) . '</strong> ';
    										echo __( 'Leave blank to not to print a footer.', 'eshopbox-pip' );
    									?></span>
    								</td>
    							</tr>


    							<tr>
    								<th>
    									<label for="eshopbox_pip_invoice_start"><b><?php _e( 'Invoice counter start:', 'eshopbox-pip' ); ?></b></label>
    								</th>
    								<td>
    								  <input type="checkbox" id="eshopbox_pip_reset_start" name="eshopbox_pip_reset_start" value="Yes" /> <?php _e( 'Reset invoice numbering', 'eshopbox-pip' ); ?><br />
    									<input type="text" readonly="true" id="eshopbox_pip_invoice_start" name="eshopbox_pip_invoice_start" class="regular-text" value="<?php echo wp_kses_stripslashes( get_option( 'eshopbox_pip_invoice_start' ) ); ?>" /><br />
    									<span class="description"><?php
    										echo __( 'Reset the invoice counter to start your custom position for example 103. Leading zeros will be trimmed. Use prefix instead.', 'eshopbox-pip' );
    										echo '<br /><strong>' . __( 'Note:', 'eshopbox-pip' ) . '</strong> ';
    										echo __( 'You need to check the checkbox to actually reset the value.', 'eshopbox-pip' );
    									?></span>
    								</td>
    							</tr>
    							<tr>
    								<th>
    									<label for="eshopbox_pip_invoice_prefix"><b><?php _e( 'Invoice numbering prefix:', 'eshopbox-pip' ); ?></b></label>
    								</th>
    								<td>
    									<input type="text" name="eshopbox_pip_invoice_prefix" class="regular-text" value="<?php echo stripslashes(get_option( 'eshopbox_pip_invoice_prefix' )); ?>" /><br />
    									<span class="description"><?php
    										echo __( 'Set your custom prefix for the invoice numbering.', 'eshopbox-pip' );
    									?></span>
    								</td>
    							</tr>
    							<tr>
    								<th>
    									<label for="eshopbox_pip_invoice_suffix"><b><?php _e( 'Invoice numbering suffix:', 'eshopbox-pip' ); ?></b></label>
    								</th>
    								<td>
    									<input type="text" name="eshopbox_pip_invoice_suffix" class="regular-text" value="<?php echo stripslashes(get_option( 'eshopbox_pip_invoice_suffix' )); ?>" /><br />
    									<span class="description"><?php
    										echo __( 'Set your custom suffix for the invoice numbering.', 'eshopbox-pip' );
    									?></span>
    								</td>
    							</tr>

                                                        <tr>
    								<th>
    									<label for="eshopbox_pip_cst"><b><?php _e( 'CST:', 'eshopbox-pip' ); ?></b></label>
    								</th>
    								<td>
    									<input type="text" name="eshopbox_pip_cst" class="regular-text" value="<?php echo stripslashes(get_option( 'eshopbox_pip_cst' )); ?>" /><br />
    									<span class="description"><?php
    										echo __( 'Set your cst number.', 'eshopbox-pip' );
    									?></span>
    								</td>
    							</tr>

                                                        <tr>
    								<th>
    									<label for="eshopbox_pip_tin"><b><?php _e( 'TIN:', 'eshopbox-pip' ); ?></b></label>
    								</th>
    								<td>
    									<input type="text" name="eshopbox_pip_tin" class="regular-text" value="<?php echo stripslashes(get_option( 'eshopbox_pip_tin' )); ?>" /><br />
    									<span class="description"><?php
    										echo __( 'Set your TIN number.', 'eshopbox-pip' );
    									?></span>
    								</td>
    							</tr>

									<tr>
									  <th>
    									<label for="preview"><b><?php _e( 'Preview before printing:', 'eshopbox-pip' ); ?></b></label>
    								</th>
										<td>
										    <?php if (get_option('eshopbox_pip_preview') == 'enabled') { ?>
										    <input type="radio" name="eshopbox_pip_preview" value="enabled" id="pip-preview" class="input-radio" checked="yes" />
										    <label for="eshopbox_pip_preview"><?php _e( 'Enabled', 'eshopbox-pip' ); ?></label><br />
										    <input type="radio" name="eshopbox_pip_preview" value="disabled" id="pip-preview" class="input-radio" />
										    <label for="eshopbox_pip_preview"><?php _e( 'Disabled', 'eshopbox-pip' ); ?></label><br />
										    <?php } else { ?>
										    <input type="radio" name="eshopbox_pip_preview" value="enabled" id="pip-preview" class="input-radio" />
										    <label for="eshopbox_pip_preview"><?php _e( 'Enabled', 'eshopbox-pip' ); ?></label><br />
										    <input type="radio" name="eshopbox_pip_preview" value="disabled" id="pip-preview" class="input-radio" checked="yes" />
										    <label for="eshopbox_pip_preview"><?php _e( 'Disabled', 'eshopbox-pip' ); ?></label><br />
										    <?php } ?>
										</td>
									</tr>
									<tr>
									  <th>
    									<label for="preview"><b><?php _e( 'Send invoice as HTML email:', 'eshopbox-pip' ); ?></b></label>
    								</th>
										<td>
										    <?php if (get_option('eshopbox_pip_send_email') == 'enabled') { ?>
										    <input type="radio" name="eshopbox_pip_send_email" value="enabled" id="pip-send-email" class="input-radio" checked="yes" />
										    <label for="eshopbox_pip_send_email"><?php _e( 'Enabled', 'eshopbox-pip' ); ?></label><br />
										    <input type="radio" name="eshopbox_pip_send_email" value="disabled" id="pip-send-email" class="input-radio" />
										    <label for="eshopbox_pip_send_email"><?php _e( 'Disabled', 'eshopbox-pip' ); ?></label><br />
										    <?php } else { ?>
										    <input type="radio" name="eshopbox_pip_send_email" value="enabled" id="pip-send-email" class="input-radio" />
										    <label for="eshopbox_pip_preview"><?php _e( 'Enabled', 'eshopbox-pip' ); ?></label><br />
										    <input type="radio" name="eshopbox_pip_send_email" value="disabled" id="pip-send-email" class="input-radio" checked="yes" />
										    <label for="eshopbox_pip_send_email"><?php _e( 'Disabled', 'eshopbox-pip' ); ?></label><br />
										    <?php } ?>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
			  <p class="submit">
				<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'eshopbox-pip' ); ?>" />
			  </p>
		    </form>
		  </div>
		</div>
		<?php
	}

  /**
	 * Add the meta box on the single order page
	 */
	function eshopbox_pip_add_box() {
		add_meta_box( 'eshopbox-pip-box', __( 'Print invoice/packing list', 'eshopbox-pip' ), 'eshopbox_pip_create_box_content', 'shop_order', 'side', 'default' );
	}

	/**
	 * Create the meta box content on the single order page
	 */
	function eshopbox_pip_create_box_content() {
		global $post_id;

		?>
		<table class="form-table">
		  <?php if(get_post_meta($post_id, '_pip_invoice_number', true)) { ?>
		  <tr>
		    <td><?php _e('Invoice: #', 'eshopbox-pip'); echo get_post_meta($post_id, '_pip_invoice_number', true); ?></td>
		  </tr>
		  <?php } ?>
			<tr>
				<td><a class="button pip-link" href="<?php echo wp_nonce_url(admin_url('?print_pip=true&post='.$post_id.'&type=print_invoice'), 'print-pip'); ?>"><?php _e('Print invoice', 'eshopbox-pip'); ?></a>
          <a class="button pip-link" href="<?php echo wp_nonce_url(admin_url('?print_pip=true&post='.$post_id.'&type=print_packing'), 'print-pip'); ?>"><?php _e('Print packing list', 'eshopbox-pip'); ?></a></td>
			</tr>
		</table>
		<?php
	}

  /**
	 * Insert buttons to orders page
	 */
  function eshopbox_pip_alter_order_actions($column) {
    global $post;
    $order = new WC_Order( $post->ID );

    switch ($column) {
      case "order_actions" :

  			?><p>
  				<a class="button pip-link" href="<?php echo wp_nonce_url(admin_url('?print_pip=true&post='.$post->ID.'&type=print_invoice'), 'print-pip'); ?>"><?php _e('Print invoice', 'eshopbox-pip'); ?></a>
  				<a class="button pip-link" href="<?php echo wp_nonce_url(admin_url('?print_pip=true&post='.$post->ID.'&type=print_packing'), 'print-pip'); ?>"><?php _e('Print packing list', 'eshopbox-pip'); ?></a>
  			</p><?php

  		  break;
    }
  }

  /**
   * Output items for display
   */
	function eshopbox_pip_order_items_table( $order, $show_price = FALSE ) {

		$return = '';

		foreach($order->get_items() as $item) {
                     //  echo '<pre>';
                      //  print_r($item);
			$_product = $order->get_product_from_item( $item );
                        $pM = get_post_meta($item['variation_id']);
                       // echo '<pre>';
                      // print_r($pM);
			$sku = $variation = '';

			$sku = $_product->get_sku();

			$item_meta = new WC_Order_Item_Meta( $item['item_meta'] );
			//$variation = '<br/><small>' . $item_meta->display( TRUE, TRUE ) . '</small>';
                        $valsize=get_term_by( 'slug', $pM["attribute_pa_size"]["0"], 'pa_size');
                        if($valsize->name){
                            $sizetext='Size :'.$valsize->name;
                        }
                        if($pM["attribute_pa_color"]["0"]){
                            $colorname='Color :'.$pM["attribute_pa_color"]["0"];
                        }
                        $variation = '<br/><small>'.$sizetext.' '.$colorname.'</small>';    
                        
                     //   attribute_pa_size  attribute_pa_color
                        if($_SERVER['REMOTE_ADDR']=='203.92.41.3'){
                         //   echo '<pre>';
                          //  print_r($item_meta);  
                        }
                        
			$return .= '<tr>
			  <td style="text-align:left; padding: 3px;">' . $sku . '</td>
				<td style="text-align:left; padding: 3px;">' . apply_filters('eshopbox_order_product_title', $item['name'], $_product) . $variation . '</td>
				<td style="text-align:left; padding: 3px;">'.$item['qty'].'</td>';
			if ($show_price) {
			 $return .= '<td style="text-align:left; padding: 3px;">';
                         //echo $order->prices_include_tax;
					if ( $order->display_cart_ex_tax || $order->prices_include_tax ) : 
						$ex_tax_label = ( $order->prices_include_tax ) ? 1 : 0;
                                              //  echo 'ureka';
						$return .= eshopbox_price( round($order->get_line_subtotal( $item )), array('ex_tax_label' => $ex_tax_label ));
					else :
                                               // echo 'no ureka';
						$return .= eshopbox_price( round($order->get_line_subtotal( $item, TRUE ) ));
					endif;

			$return .= '
				</td>';
                        $return .= '<td style="text-align:left; padding: 3px;">';
                            if($item_meta->meta['_tax_class'][0]!=''){
                                $explodefornum = explode('-',$item_meta->meta['_tax_class'][0]);
                                $return .= $explodefornum[0].'%';
                            } else {
                                 $return .= '5%';
                            }
                        
                        	$return .= '
				</td>';
      
                             $return .= '<td style="text-align:left; padding: 3px;">';
                            if($item_meta->meta['_line_tax'][0]!=''){
                               
                                $return .= $item_meta->meta['_line_tax'][0];
                            }
                        
                        	$return .= '
				</td>';       
                                
                                
		  }
		  else {
  		  $return .= '<td style="text-align:left; padding: 3px;">';
  		  $return .= ($_product->get_weight()) ? $_product->get_weight() . ' ' . get_option('eshopbox_weight_unit') : __( '0.2', 'eshopbox-pip' );
  		  $return .= '</td>';
		  }
			$return .= '</tr>';

		}

		$return = apply_filters( 'eshopbox_pip_order_items_table', $return );

		return $return;

	}

	/**
   * Get template directory
   */
	function eshopbox_pip_template($type, $template) {
	  $templates = array();
		if (file_exists( trailingslashit( get_stylesheet_directory() ) . 'eshopbox/eshopbox-pip-template/' . $template )) {
		  $templates['uri']	= trailingslashit( get_stylesheet_directory_uri() ) . 'eshopbox/eshopbox-pip-template/';
		  $templates['dir']	= trailingslashit( get_stylesheet_directory() ) . 'eshopbox/eshopbox-pip-template/';
		}
		else {
		  $templates['uri']	= plugin_dir_url( __FILE__ ) . 'eshopbox-pip-template/';
		  $templates['dir']	= plugin_dir_path( __FILE__ ) . 'eshopbox-pip-template/';
		}

		return $templates[$type];
	}

	/**
   * Output preview if needed
   */
	function eshopbox_pip_preview() {
	  if (get_option('eshopbox_pip_preview') != 'enabled') {
	    return 'onload="window.print()"';
	  }
	}

	/**
   * Output logo if needed
   */
	function eshopbox_pip_print_logo() {
	  if (get_option('eshopbox_pip_logo') != '') {
	    return '<img src="' . get_option('eshopbox_pip_logo') . '" /><br />';
	  }else{
              return '<img src="http://speed.eshopbox.com/phiveriverc6069967bc60284e457a9fe095ba49/logo_phive.png" /><br />';
          }
	}

	/**
   * Output company name if needed
   */
	function eshopbox_pip_print_company_name() {
	  if (get_option('eshopbox_pip_company_name') != '') {
	    return get_option('eshopbox_pip_company_name') . '<br />';
	  }
	}

	/**
   * Output company extra if needed
   */
	function eshopbox_pip_print_company_extra() {
	  if (get_option('eshopbox_pip_company_extra') != '') {
	    return nl2br(stripslashes(get_option('eshopbox_pip_company_extra')));
	  }
	}

	/**
   * Output return policy if needed
   */
	function eshopbox_pip_print_return_policy() {
	  if (get_option('eshopbox_pip_return_policy') != '') {
	    return nl2br(stripslashes(get_option('eshopbox_pip_return_policy')));
	  }
	}

	/**
   * Output footer if needed
   */
	function eshopbox_pip_print_footer() {
	  if (get_option('eshopbox_pip_footer') != '') {
	    return nl2br(stripslashes(get_option('eshopbox_pip_footer')));
	  }
	}

         	function eshopbox_pip_return_address() {
	  if (get_option('eshopbox_pip_return_address') != '') {
	    return nl2br(stripslashes(get_option('eshopbox_pip_return_address')));
	  }
	}
	/**

   * Output invoice number if needed
   */
	function eshopbox_pip_invoice_number( $order_id ) {
		$invoice_number = get_option('eshopbox_pip_invoice_start');

		if ( add_post_meta( $order_id, '_pip_invoice_number', get_option('eshopbox_pip_invoice_prefix') . $invoice_number . get_option( 'eshopbox_pip_invoice_suffix' ), true) ) {
                {
			update_option( 'eshopbox_pip_invoice_start', $invoice_number + 1 );
                   //     add_post_meta( $order_id, '_pip_invoice_date',date('d-m-Y') , true); 
                        
                }        

      	}
	    return get_post_meta( $order_id, '_pip_invoice_number', true );
	}
        
               	function eshopbox_pip_invoice_date( $order_id ) {

            if(get_post_meta( $order_id, '_pip_invoice_date', true )==''){
                add_post_meta( $order_id, '_pip_invoice_date',date('d-m-Y') , true); 
            }
        
	    return get_post_meta( $order_id, '_pip_invoice_date', true );
	}

  /**
   * Helper function to check access rights.
   * Support for 1.6.6 and 2.0.
   */
   function eshopbox_pip_user_access() {
     $access = (current_user_can('edit_shop_orders') || current_user_can('manage_eshopbox_orders')) ? false : true;
     return $access;
   }

  /**
   * Function to output the printing window for single item and bulk printing.
   */
  function eshopbox_pip_window() {
  	if (isset($_GET['print_pip'])) {
  		$nonce = $_REQUEST['_wpnonce'];
  		global $eshopbox;
  		// Check that current user has needed access rights.
  		if (!wp_verify_nonce($nonce, 'print-pip') || !is_user_logged_in() || eshopbox_pip_user_access()) die('You are not allowed to view this page.');

    	$orders = explode(',', $_GET['post']);
      $action = $_GET['type'];
      $number_of_orders = count($orders);
      $order_loop = 0;

      // Build the output.
		  ob_start();
      require_once eshopbox_pip_template('dir', 'template-header.php') . 'template-header.php';
      $content = ob_get_clean();

      // Loop through all orders (bulk printing).
      foreach ($orders as $order_id) {
        $order_loop++;
        $order = new WC_Order($order_id);
  		  ob_start();
  		  include eshopbox_pip_template('dir', 'template-body.php') . 'template-body.php';
  		  $content .= ob_get_clean();
  		  if($number_of_orders > 1 && $order_loop < $number_of_orders) {
  		    $content .= '<p class="pagebreak"></p>';
  		  }
      }

		  ob_start();
      require_once eshopbox_pip_template('dir', 'template-footer.php') . 'template-footer.php';
      $content .= ob_get_clean();

  		echo $content;
  		exit;
    }
  }

  /**
  * Function to output the printing window for single item for customers.
  */
  function eshopbox_pip_client_window() {
    if (isset($_GET['print_pip_invoice']) && isset($_GET['post'])) {
      $nonce = $_REQUEST['_wpnonce'];
      global $eshopbox;
      $order_id = $_GET['post'];
      $order = new WC_Order($order_id);
      $current_user = wp_get_current_user();
      $action = 'print_invoice';
      $client = true;

      // Check that current user has needed access rights.
      if (!wp_verify_nonce($nonce, 'client-print-pip') || !is_user_logged_in() || $order->user_id != $current_user->ID) die('You are not allowed to view this page.');

      // Build the output.
      ob_start();
      require_once eshopbox_pip_template('dir', 'template-header.php') . 'template-header.php';
      $content = ob_get_clean();

      ob_start();
      include eshopbox_pip_template('dir', 'template-body.php') . 'template-body.php';
      $content .= ob_get_clean();

      ob_start();
      require_once eshopbox_pip_template('dir', 'template-footer.php') . 'template-footer.php';
      $content .= ob_get_clean();

      echo $content;
      exit;
    }
  }

  /**
   * Process the new bulk actions for printing invoices and packing lists.
   */
  function eshopbox_pip_order_bulk_action() {
    $wp_list_table = _get_list_table('WP_Posts_List_Table');
    $action = $wp_list_table->current_action();
    if ($action=='print_invoice' || $action=='print_packing') {
      $posts = '';

      foreach($_REQUEST['post'] as $post_id) {
        if(empty($posts)) {
          $posts = $post_id;
        }
        else {
          $posts .= ','.$post_id;
        }
      }

      $forward = wp_nonce_url(admin_url(), 'print-pip');
      $forward = add_query_arg(array('print_pip' => 'true', 'post' => $posts, 'type' => $action), $forward);
      wp_redirect($forward);
      exit();
    }
  }

  /**
   * Function to send invoice as email
   */
  function eshopbox_pip_send_email($order_id) {
    if (get_option('eshopbox_pip_send_email') == 'enabled') {
      // Build email information
      $order = new WC_Order( $order_id );
      $to = $order->billing_email;
      $subject = __('Order invoice', 'eshopbox-pip');
      $subject = '[' . get_bloginfo('name') . '] ' . $subject;
      $attachments = '';

      // Read the file
		  ob_start();
		  require_once eshopbox_pip_template('dir', 'email-template.php') . 'email-template.php';
		  $message = ob_get_clean();

  	  // Send the mail
		  eshopbox_mail($to, $subject, $message, $headers = "Content-Type: text/html\r\n", $attachments);
		}
  }
  	function eshopbox_pip_extra_display() {
           if($_GET['type']=='print_packing') {
                     global $eshopbox;
      $order_id = $_GET['post'];
      $order = new WC_Order($order_id);
               // echo '<pre>';
               // print_r($order); 
      if($order->payment_method=='cod'){
	  	  ?>
		  <div style="clear:none; float:left; overflow:hidden; width:50%;">
		  <div style="float:left; border:2px solid #000; padding:5px; font-size:17px;">
		  <?php	
          echo '<strong>Total Collectibles:</strong> INR &nbsp;'.$order->order_total;
		  ?>
		  </div>
		  <div style="clear:both; float:left; width:60px; border:2px solid #000; padding:20px; margin-top:20px;">
		  <?php
          echo '<h1>COD</h1>';
         
      }
	  
		?>	
		</div>
		</div>
			  <div style="float:right;">
			  <?php	
       		  	echo '<div style="font-size:17px; padding-bottom:10px; font-weight:bold;">If undelivered, please return to:</br></div>';
          	  	echo eshopbox_pip_return_address();
			  	?>
			  </div>
<?php			  
           }
	}
        
        
        function returnRoutingCodes($type,$pincode){
      global $wpdb;
      switch($type){
          
          case 'cod':
          $myrows = $wpdb->get_row( "SELECT * FROM ".$wpdb->prefix."cod_routing where PINCODE=$pincode" );
            return $myrows->DSTARCD;
          break;
      
          default:
              $myrows = $wpdb->get_row( "SELECT * FROM ".$wpdb->prefix."prepaid_routing where PINCODE=$pincode" );
            return $myrows->DSTARCD;
              
          break;   
          
      }
      
      
  }
  
  function returnRoutingCodes2($type,$pincode){ 
      global $wpdb;
      switch($type){
          
          case 'cod':
          $myrows = $wpdb->get_row( "SELECT * FROM ".$wpdb->prefix."cod_routing where PINCODE=$pincode" );
            //return $myrows->DSTARCD.'/'.$myrows->RETURN_LOC;
              return $myrows->RETURN_LOC.' '.$myrows->RETLOC;
          break;
      
          default:
              $myrows = $wpdb->get_row( "SELECT * FROM ".$wpdb->prefix."prepaid_routing where PINCODE=$pincode" );
            return $myrows->RETURN_LOC.' '.$myrows->RETLOC;
              
          break;   
          
      }
      
      
  }

//}

?>