<?php
/**
 * Debug/Status page
 *
 * @author 		WooThemes
 * @category 	Admin
 * @package 	EshopBox/Admin/System Status
 * @version     1.6.4
 */

/**
 * Output the content of the debugging page.
 *
 * @access public
 * @return void
 */
function eshopbox_status() {
	global $eshopbox, $wpdb;

	$current_tab = ! empty( $_REQUEST['tab'] ) ? $_REQUEST['tab'] : 'status';
    ?>
	<div class="wrap eshopbox">
		<div class="icon32 icon32-eshopbox-status" id="icon-eshopbox"><br /></div><h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
			<?php
				$tabs = array(
					'status' => __( 'System Status', 'eshopbox' ),
					'tools'  => __( 'Tools', 'eshopbox' ),
				);
				foreach ( $tabs as $name => $label ) {
					echo '<a href="' . admin_url( 'admin.php?page=eshopbox_status&tab=' . $name ) . '" class="nav-tab ';
					if ( $current_tab == $name ) echo 'nav-tab-active';
					echo '">' . $label . '</a>';
				}
			?>
		</h2><br/>
		<?php
			switch ( $current_tab ) {
				case "tools" :
					eshopbox_status_tools();
				break;
				default :
					eshopbox_status_report();
				break;
			}
		?>
	</div>
	<?php
}

/**
 * eshopbox_status_report function.
 *
 * @access public
 * @return void
 */
function eshopbox_status_report() {
	global $eshopbox, $wpdb;

	?>
	<div class="eshopbox-message">
		<div class="squeezer">
			<h4><?php _e( 'Please include this information when requesting support:', 'eshopbox' ); ?> </h4>
			<p class="submit"><a href="#" download="wc_report.txt" class="button-primary debug-report"><?php _e( 'Download System Report File', 'eshopbox' ); ?></a></p>
		</div>
	</div>
	<br/>
	<table class="wc_status_table widefat" cellspacing="0">

		<thead>
			<tr>
				<th colspan="2"><?php _e( 'Environment', 'eshopbox' ); ?></th>
			</tr>
		</thead>

		<tbody>
			<tr>
                <td><?php _e( 'Home URL','eshopbox' ); ?>:</td>
                <td><?php echo home_url(); ?></td>
            </tr>
            <tr>
                <td><?php _e( 'Site URL','eshopbox' ); ?>:</td>
                <td><?php echo site_url(); ?></td>
            </tr>
            <tr>
                <td><?php _e( 'WC Version','eshopbox' ); ?>:</td>
                <td><?php echo esc_html( $eshopbox->version ); ?></td>
            </tr>
            <tr>
                <td><?php _e( 'WC Database Version','eshopbox' ); ?>:</td>
                <td><?php echo esc_html( get_option( 'eshopbox_db_version' ) ); ?></td>
            </tr>
            <tr>
                <td><?php _e( 'WP Version','eshopbox' ); ?>:</td>
                <td><?php if ( is_multisite() ) echo 'WPMU'; else echo 'WP'; ?> <?php bloginfo('version'); ?></td>
            </tr>
            <tr>
                <td><?php _e( 'Web Server Info','eshopbox' ); ?>:</td>
                <td><?php echo esc_html( $_SERVER['SERVER_SOFTWARE'] );  ?></td>
            </tr>
            <tr>
                <td><?php _e( 'PHP Version','eshopbox' ); ?>:</td>
                <td><?php if ( function_exists( 'phpversion' ) ) echo esc_html( phpversion() ); ?></td>
            </tr>
            <tr>
                <td><?php _e( 'MySQL Version','eshopbox' ); ?>:</td>
                <td><?php if ( function_exists( 'mysql_get_server_info' ) ) echo esc_html( mysql_get_server_info() ); ?></td>
            </tr>
            <tr>
                <td><?php _e( 'WP Memory Limit','eshopbox' ); ?>:</td>
                <td><?php
                	$memory = eshopbox_let_to_num( WP_MEMORY_LIMIT );

                	if ( $memory < 67108864 ) {
                		echo '<mark class="error">' . sprintf( __( '%s - We recommend setting memory to at least 64MB. See: <a href="%s">Increasing memory allocated to PHP</a>', 'eshopbox' ), size_format( $memory ), 'http://codex.boxbeat.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP' ) . '</mark>';
                	} else {
                		echo '<mark class="yes">' . size_format( $memory ) . '</mark>';
                	}
                ?></td>
            </tr>
            <tr>
                <td><?php _e( 'WP Debug Mode','eshopbox' ); ?>:</td>
                <td><?php if ( defined('WP_DEBUG') && WP_DEBUG ) echo '<mark class="yes">' . __( 'Yes', 'eshopbox' ) . '</mark>'; else echo '<mark class="no">' . __( 'No', 'eshopbox' ) . '</mark>'; ?></td>
            </tr>
            <tr>
                <td><?php _e( 'WP Max Upload Size','eshopbox' ); ?>:</td>
                <td><?php echo size_format( wp_max_upload_size() ); ?></td>
            </tr>
            <tr>
                <td><?php _e('PHP Post Max Size','eshopbox' ); ?>:</td>
                <td><?php if ( function_exists( 'ini_get' ) ) echo size_format( eshopbox_let_to_num( ini_get('post_max_size') ) ); ?></td>
            </tr>
            <tr>
                <td><?php _e('PHP Time Limit','eshopbox' ); ?>:</td>
                <td><?php if ( function_exists( 'ini_get' ) ) echo ini_get('max_execution_time'); ?></td>
            </tr>
            <tr>
                <td><?php _e( 'WC Logging','eshopbox' ); ?>:</td>
                <td><?php
                	if ( @fopen( $eshopbox->plugin_path() . '/logs/paypal.txt', 'a' ) )
                		echo '<mark class="yes">' . __( 'Log directory is writable.', 'eshopbox' ) . '</mark>';
                	else
                		echo '<mark class="error">' . __( 'Log directory (<code>eshopbox/logs/</code>) is not writable. Logging will not be possible.', 'eshopbox' ) . '</mark>';
                ?></td>
            </tr>
			<tr>
				<td><?php _e( 'Default Timezone','eshopbox' ); ?>:</td>
				<td><?php
					$default_timezone = date_default_timezone_get();
					if ( 'UTC' !== $default_timezone ) {
						echo '<mark class="error">' . sprintf( __( 'Default timezone is %s - it should be UTC', 'eshopbox' ), $default_timezone ) . '</mark>';
					} else {
						echo '<mark class="yes">' . sprintf( __( 'Default timezone is %s', 'eshopbox' ), $default_timezone ) . '</mark>';
					} ?>
				</td>
			</tr>
            <?php
				$posting = array();

				// fsockopen/cURL
				$posting['fsockopen_curl']['name'] = __( 'fsockopen/cURL','eshopbox');
				if ( function_exists( 'fsockopen' ) || function_exists( 'curl_init' ) ) {
					if ( function_exists( 'fsockopen' ) && function_exists( 'curl_init' )) {
						$posting['fsockopen_curl']['note'] = __('Your server has fsockopen and cURL enabled.', 'eshopbox' );
					} elseif ( function_exists( 'fsockopen' )) {
						$posting['fsockopen_curl']['note'] = __( 'Your server has fsockopen enabled, cURL is disabled.', 'eshopbox' );
					} else {
						$posting['fsockopen_curl']['note'] = __( 'Your server has cURL enabled, fsockopen is disabled.', 'eshopbox' );
					}
					$posting['fsockopen_curl']['success'] = true;
				} else {
	        		$posting['fsockopen_curl']['note'] = __( 'Your server does not have fsockopen or cURL enabled - PayPal IPN and other scripts which communicate with other servers will not work. Contact your hosting provider.', 'eshopbox' ). '</mark>';
	        		$posting['fsockopen_curl']['success'] = false;
	        	}

	        	// SOAP
	        	$posting['soap_client']['name'] = __( 'SOAP Client','eshopbox' );
				if ( class_exists( 'SoapClient' ) ) {
					$posting['soap_client']['note'] = __('Your server has the SOAP Client class enabled.', 'eshopbox' );
					$posting['soap_client']['success'] = true;
				} else {
	        		$posting['soap_client']['note'] = sprintf( __( 'Your server does not have the <a href="%s">SOAP Client</a> class enabled - some gateway plugins which use SOAP may not work as expected.', 'eshopbox' ), 'http://php.net/manual/en/class.soapclient.php' ) . '</mark>';
	        		$posting['soap_client']['success'] = false;
	        	}

	        	// WP Remote Post Check
				$posting['wp_remote_post']['name'] = __( 'WP Remote Post','eshopbox');
				$request['cmd'] = '_notify-validate';
				$params = array(
					'sslverify' 	=> false,
		        	'timeout' 		=> 60,
		        	'user-agent'	=> 'EshopBox/' . $eshopbox->version,
		        	'body'			=> $request
				);
				$response = wp_remote_post( 'https://www.paypal.com/cgi-bin/webscr', $params );

				if ( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) {
	        		$posting['wp_remote_post']['note'] = __('wp_remote_post() was successful - PayPal IPN is working.', 'eshopbox' );
	        		$posting['wp_remote_post']['success'] = true;
	        	} elseif ( is_wp_error( $response ) ) {
	        		$posting['wp_remote_post']['note'] = __( 'wp_remote_post() failed. PayPal IPN won\'t work with your server. Contact your hosting provider. Error:', 'eshopbox' ) . ' ' . $response->get_error_message();
	        		$posting['wp_remote_post']['success'] = false;
	        	} else {
	            	$posting['wp_remote_post']['note'] = __( 'wp_remote_post() failed. PayPal IPN may not work with your server.', 'eshopbox' );
	        		$posting['wp_remote_post']['success'] = false;
	        	}

	        	$posting = apply_filters( 'eshopbox_debug_posting', $posting );

	        	foreach( $posting as $post ) { $mark = ( isset( $post['success'] ) && $post['success'] == true ) ? 'yes' : 'error';
	        		?>
					<tr>
		                <td><?php echo esc_html( $post['name'] ); ?>:</td>
		                <td>
		                	<mark class="<?php echo $mark; ?>">
		                    	<?php echo wp_kses_data( $post['note'] ); ?>
		                	</mark>
		                </td>
		            </tr>
		            <?php
	            }
	        ?>
		</tbody>

		<thead>
			<tr>
				<th colspan="2"><?php _e( 'Plugins', 'eshopbox' ); ?></th>
			</tr>
		</thead>

		<tbody>
         	<tr>
         		<td><?php _e( 'Installed Plugins','eshopbox' ); ?>:</td>
         		<td><?php
         			$active_plugins = (array) get_option( 'active_plugins', array() );

         			if ( is_multisite() )
						$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );

					$wc_plugins = array();

					foreach ( $active_plugins as $plugin ) {

						$plugin_data    = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
						$dirname        = dirname( $plugin );
						$version_string = '';

						if ( ! empty( $plugin_data['Name'] ) ) {

							if ( strstr( $dirname, 'eshopbox' ) ) {

								if ( false === ( $version_data = get_transient( $plugin . '_version_data' ) ) ) {
									$changelog = wp_remote_get( 'http://dzv365zjfbd8v.cloudfront.net/changelogs/' . $dirname . '/changelog.txt' );
									$cl_lines  = explode( "\n", wp_remote_retrieve_body( $changelog ) );
									if ( ! empty( $cl_lines ) ) {
										foreach ( $cl_lines as $line_num => $cl_line ) {
											if ( preg_match( '/^[0-9]/', $cl_line ) ) {

												$date         = str_replace( '.' , '-' , trim( substr( $cl_line , 0 , strpos( $cl_line , '-' ) ) ) );
												$version      = preg_replace( '~[^0-9,.]~' , '' ,stristr( $cl_line , "version" ) );
												$update       = trim( str_replace( "*" , "" , $cl_lines[ $line_num + 1 ] ) );
												$version_data = array( 'date' => $date , 'version' => $version , 'update' => $update , 'changelog' => $changelog );
												set_transient( $plugin . '_version_data', $version_data , 60*60*12 );
												break;
											}
										}
									}
								}

								if ( ! empty( $version_data['version'] ) && version_compare( $version_data['version'], $plugin_data['Version'], '!=' ) )
									$version_string = ' &ndash; <strong style="color:red;">' . $version_data['version'] . ' ' . __( 'is available', 'eshopbox' ) . '</strong>';
							}

							$wc_plugins[] = $plugin_data['Name'] . ' ' . __( 'by', 'eshopbox' ) . ' ' . $plugin_data['Author'] . ' ' . __( 'version', 'eshopbox' ) . ' ' . $plugin_data['Version'] . $version_string;

						}
					}

					if ( sizeof( $wc_plugins ) == 0 )
						echo '-';
					else
						echo implode( ', <br/>', $wc_plugins );

         		?></td>
         	</tr>
		</tbody>

		<thead>
			<tr>
				<th colspan="2"><?php _e( 'Settings', 'eshopbox' ); ?></th>
			</tr>
		</thead>

		<tbody>

            <tr>
                <td><?php _e( 'Force SSL','eshopbox' ); ?>:</td>
				<td><?php echo get_option( 'eshopbox_force_ssl_checkout' ) === 'yes' ? '<mark class="yes">'.__( 'Yes', 'eshopbox' ).'</mark>' : '<mark class="no">'.__( 'No', 'eshopbox' ).'</mark>'; ?></td>
            </tr>
		</tbody>

		<thead>
			<tr>
				<th colspan="2"><?php _e( 'WC Pages', 'eshopbox' ); ?></th>
			</tr>
		</thead>

		<tbody>
			<?php
				$check_pages = array(
					__( 'Shop Base', 'eshopbox' ) => array(
							'option' => 'eshopbox_shop_page_id',
							'shortcode' => ''
						),
					__( 'Cart', 'eshopbox' ) => array(
							'option' => 'eshopbox_cart_page_id',
							'shortcode' => '[eshopbox_cart]'
						),
					__( 'Checkout', 'eshopbox' ) => array(
							'option' => 'eshopbox_checkout_page_id',
							'shortcode' => '[eshopbox_checkout]'
						),
					__( 'Pay', 'eshopbox' ) => array(
							'option' => 'eshopbox_pay_page_id',
							'shortcode' => '[eshopbox_pay]'
						),
					__( 'Thanks', 'eshopbox' ) => array(
							'option' => 'eshopbox_thanks_page_id',
							'shortcode' => '[eshopbox_thankyou]'
						),
					__( 'My Account', 'eshopbox' ) => array(
							'option' => 'eshopbox_myaccount_page_id',
							'shortcode' => '[eshopbox_my_account]'
						),
					__( 'Edit Address', 'eshopbox' ) => array(
							'option' => 'eshopbox_edit_address_page_id',
							'shortcode' => '[eshopbox_edit_address]'
						),
					__( 'View Order', 'eshopbox' ) => array(
							'option' => 'eshopbox_view_order_page_id',
							'shortcode' => '[eshopbox_view_order]'
						),
					__( 'Change Password', 'eshopbox' ) => array(
							'option' => 'eshopbox_change_password_page_id',
							'shortcode' => '[eshopbox_change_password]'
						),
					__( 'Lost Password', 'eshopbox' ) => array(
							'option' => 'eshopbox_lost_password_page_id',
							'shortcode' => '[eshopbox_lost_password]'
						)
				);

				$alt = 1;

				foreach ( $check_pages as $page_name => $values ) {

					if ( $alt == 1 ) echo '<tr>'; else echo '<tr>';

					echo '<td>' . esc_html( $page_name ) . ':</td><td>';

					$error = false;

					$page_id = get_option( $values['option'] );

					// Page ID check
					if ( ! $page_id ) {
						echo '<mark class="error">' . __( 'Page not set', 'eshopbox' ) . '</mark>';
						$error = true;
					} else {

						// Shortcode check
						if ( $values['shortcode'] ) {
							$page = get_post( $page_id );

							if ( empty( $page ) ) {

								echo '<mark class="error">' . sprintf( __( 'Page does not exist', 'eshopbox' ) ) . '</mark>';
								$error = true;

							} else if ( ! strstr( $page->post_content, $values['shortcode'] ) ) {

								echo '<mark class="error">' . sprintf( __( 'Page does not contain the shortcode: %s', 'eshopbox' ), $values['shortcode'] ) . '</mark>';
								$error = true;

							}
						}

					}

					if ( ! $error ) echo '<mark class="yes">#' . absint( $page_id ) . ' - ' . str_replace( home_url(), '', get_permalink( $page_id ) ) . '</mark>';

					echo '</td></tr>';

					$alt = $alt * -1;
				}
			?>
		</tbody>

		<thead>
			<tr>
				<th colspan="2"><?php _e( 'WC Taxonomies', 'eshopbox' ); ?></th>
			</tr>
		</thead>

		<tbody>
            <tr>
                <td><?php _e( 'Order Statuses', 'eshopbox' ); ?>:</td>
                <td><?php
                	$display_terms = array();
                	$terms = get_terms( 'shop_order_status', array( 'hide_empty' => 0 ) );
                	foreach ( $terms as $term )
                		$display_terms[] = $term->name . ' (' . $term->slug . ')';
                	echo implode( ', ', array_map( 'esc_html', $display_terms ) );
                ?></td>
            </tr>
            <tr>
                <td><?php _e( 'Product Types', 'eshopbox' ); ?>:</td>
                <td><?php
                	$display_terms = array();
                	$terms = get_terms( 'product_type', array( 'hide_empty' => 0 ) );
                	foreach ( $terms as $term )
                		$display_terms[] = $term->name . ' (' . $term->slug . ')';
                	echo implode( ', ', array_map( 'esc_html', $display_terms ) );
                ?></td>
            </tr>
		</tbody>

        	<thead>
			<tr>
				<th colspan="2"><?php _e( 'Theme', 'eshopbox' ); ?></th>
			</tr>
		</thead>

        <?php
        $active_theme = wp_get_theme();
        if ( $active_theme->{'Author URI'} == 'http://www.woothemes.com' ) :
		
			$theme_dir = strtolower( str_replace( ' ','', $active_theme->Name ) );
        
			if ( false === ( $theme_version_data = get_transient( $theme_dir . '_version_data' ) ) ) :
        	
        		$theme_changelog = wp_remote_get( 'http://dzv365zjfbd8v.cloudfront.net/changelogs/' . $theme_dir . '/changelog.txt' );
				$cl_lines  = explode( "\n", wp_remote_retrieve_body( $theme_changelog ) );
				if ( ! empty( $cl_lines ) ) :
			
					foreach ( $cl_lines as $line_num => $cl_line ) {
						if ( preg_match( '/^[0-9]/', $cl_line ) ) :

							$theme_date    		= str_replace( '.' , '-' , trim( substr( $cl_line , 0 , strpos( $cl_line , '-' ) ) ) );
							$theme_version      = preg_replace( '~[^0-9,.]~' , '' ,stristr( $cl_line , "version" ) );
							$theme_update       = trim( str_replace( "*" , "" , $cl_lines[ $line_num + 1 ] ) );
							$theme_version_data = array( 'date' => $theme_date , 'version' => $theme_version , 'update' => $theme_update , 'changelog' => $theme_changelog );
							set_transient( $theme_dir . '_version_data', $theme_version_data , 60*60*12 );
							break;
					
						endif;
					}
				
				endif;
			
			endif;
			
		endif;
		?>
		<tbody>
            <tr>
                <td><?php _e( 'Theme Name', 'eshopbox' ); ?>:</td>
                <td><?php
					echo $active_theme->Name;
                ?></td>
            </tr>
            <tr>
                <td><?php _e( 'Theme Version', 'eshopbox' ); ?>:</td>
                <td><?php
					echo $active_theme->Version;
					
					if ( ! empty( $theme_version_data['version'] ) && version_compare( $theme_version_data['version'], $active_theme->Version, '!=' ) )
						echo ' &ndash; <strong style="color:red;">' . $theme_version_data['version'] . ' ' . __( 'is available', 'eshopbox' ) . '</strong>';
                ?></td>
            </tr>
            <tr>
                <td><?php _e( 'Author URL', 'eshopbox' ); ?>:</td>
                <td><?php
					echo $active_theme->{'Author URI'};
                ?></td>
            </tr>
		</tbody>

		<thead>
			<tr>
				<th colspan="2"><?php _e( 'Templates', 'eshopbox' ); ?></th>
			</tr>
		</thead>

		<tbody>
            <tr>
                <td><?php _e( 'Template Overrides', 'eshopbox' ); ?>:</td>
                <td><?php

					$template_path = $eshopbox->plugin_path() . '/templates/';
					$found_files   = array();
					$files         = eshopbox_scan_template_files( $template_path );

					foreach ( $files as $file ) {
						if ( file_exists( get_stylesheet_directory() . '/' . $file ) ) {
							$found_files[] = '/' . $file;
						} elseif( file_exists( get_stylesheet_directory() . '/eshopbox/' . $file ) ) {
							$found_files[] = '/eshopbox/' . $file;
						}
					}

					if ( $found_files ) {
						echo implode( ', <br/>', $found_files );
					} else {
						_e( 'No core overrides present in theme.', 'eshopbox' );
					}

                ?></td>
            </tr>
		</tbody>

	</table>
	<script type="text/javascript">

		jQuery.wc_strPad = function(i,l,s) {
			var o = i.toString();
			if (!s) { s = '0'; }
			while (o.length < l) {
				o = o + s;
			}
			return o;
		};

		jQuery('a.debug-report').click(function(){

			var report = "";

			jQuery('.wc_status_table thead, .wc_status_table tbody').each(function(){

				$this = jQuery( this );

				if ( $this.is('thead') ) {

					report = report + "\n### " + jQuery.trim( $this.text() ) + " ###\n\n";

				} else {

					jQuery('tr', $this).each(function(){

						$this = jQuery( this );

						name = jQuery.wc_strPad( jQuery.trim( $this.find('td:eq(0)').text() ), 25, ' ' );
						value = jQuery.trim( $this.find('td:eq(1)').text() );

						report = report + '' + name + value + "\n\n";
					});

				}
			} );

			var blob = new Blob( [report] );

			jQuery(this).attr( 'href', window.URL.createObjectURL( blob ) );

      		return true;
		});

	</script>
	<?php
}

/**
 * eshopbox_scan_template_files function.
 *
 * @access public
 * @param mixed $template_path
 * @return void
 */
function eshopbox_scan_template_files( $template_path ) {
	$files         = scandir( $template_path );
	$result        = array();
	if ( $files ) {
		foreach ( $files as $key => $value ) {
			if ( ! in_array( $value, array( ".",".." ) ) ) {
				if ( is_dir( $template_path . DIRECTORY_SEPARATOR . $value ) ) {
					$sub_files = eshopbox_scan_template_files( $template_path . DIRECTORY_SEPARATOR . $value );
					foreach ( $sub_files as $sub_file ) {
						$result[] = $value . DIRECTORY_SEPARATOR . $sub_file;
					}
				} else {
					$result[] = $value;
				}
			}
		}
	}
	return $result;
}

/**
 * eshopbox_status_tools function.
 *
 * @access public
 * @return void
 */
function eshopbox_status_tools() {
	global $eshopbox, $wpdb;

	$tools = apply_filters( 'eshopbox_debug_tools', array(
		'clear_transients' => array(
			'name'		=> __( 'WC Transients','eshopbox'),
			'button'	=> __('Clear transients','eshopbox'),
			'desc'		=> __( 'This tool will clear the product/shop transients cache.', 'eshopbox' ),
		),
		'clear_expired_transients' => array(
			'name'		=> __( 'Expired Transients','eshopbox'),
			'button'	=> __('Clear expired transients','eshopbox'),
			'desc'		=> __( 'This tool will clear ALL expired transients from Wordpress.', 'eshopbox' ),
		),
		'recount_terms' => array(
			'name'		=> __('Term counts','eshopbox'),
			'button'	=> __('Recount terms','eshopbox'),
			'desc'		=> __( 'This tool will recount product terms - useful when changing your settings in a way which hides products from the catalog.', 'eshopbox' ),
		),
		'reset_roles' => array(
			'name'		=> __('Capabilities','eshopbox'),
			'button'	=> __('Reset capabilities','eshopbox'),
			'desc'		=> __( 'This tool will reset the admin, customer and shop_manager roles to default. Use this if your users cannot access all of the EshopBox admin pages.', 'eshopbox' ),
		),
		'clear_sessions' => array(
			'name'		=> __('Customer Sessions','eshopbox'),
			'button'	=> __('Clear all sessions','eshopbox'),
			'desc'		=> __( '<strong class="red">Warning</strong> This tool will delete all customer session data from the database, including any current live carts.', 'eshopbox' ),
		),
	) );

	if ( ! empty( $_GET['action'] ) && ! empty( $_REQUEST['_wpnonce'] ) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'debug_action' ) ) {

		switch ( $_GET['action'] ) {
			case "clear_transients" :
				$eshopbox->clear_product_transients();

				echo '<div class="updated"><p>' . __( 'Product Transients Cleared', 'eshopbox' ) . '</p></div>';
			break;
			case "clear_expired_transients" :

				// http://w-shadow.com/blog/2012/04/17/delete-stale-transients/
				$rows = $wpdb->query( "
					DELETE
						a, b
					FROM
						{$wpdb->options} a, {$wpdb->options} b
					WHERE
						a.option_name LIKE '_transient_%' AND
						a.option_name NOT LIKE '_transient_timeout_%' AND
						b.option_name = CONCAT(
							'_transient_timeout_',
							SUBSTRING(
								a.option_name,
								CHAR_LENGTH('_transient_') + 1
							)
						)
						AND b.option_value < UNIX_TIMESTAMP()
				" );

				$rows2 = $wpdb->query( "
					DELETE
						a, b
					FROM
						{$wpdb->options} a, {$wpdb->options} b
					WHERE
						a.option_name LIKE '_site_transient_%' AND
						a.option_name NOT LIKE '_site_transient_timeout_%' AND
						b.option_name = CONCAT(
							'_site_transient_timeout_',
							SUBSTRING(
								a.option_name,
								CHAR_LENGTH('_site_transient_') + 1
							)
						)
						AND b.option_value < UNIX_TIMESTAMP()
				" );

				echo '<div class="updated"><p>' . sprintf( __( '%d Transients Rows Cleared', 'eshopbox' ), $rows + $rows2 ) . '</p></div>';

			break;
			case "reset_roles" :
				// Remove then re-add caps and roles
				eshopbox_remove_roles();
				eshopbox_init_roles();

				echo '<div class="updated"><p>' . __( 'Roles successfully reset', 'eshopbox' ) . '</p></div>';
			break;
			case "recount_terms" :

				$product_cats = get_terms( 'product_cat', array( 'hide_empty' => false, 'fields' => 'id=>parent' ) );

				_eshopbox_term_recount( $product_cats, get_taxonomy( 'product_cat' ), false, false );

				$product_tags = get_terms( 'product_tag', array( 'hide_empty' => false, 'fields' => 'id=>parent' ) );

				_eshopbox_term_recount( $product_cats, get_taxonomy( 'product_tag' ), false, false );

				echo '<div class="updated"><p>' . __( 'Terms successfully recounted', 'eshopbox' ) . '</p></div>';
			break;
			case "clear_sessions" :

				$wpdb->query( "
					DELETE FROM {$wpdb->options}
					WHERE option_name LIKE '_wc_session_%' OR option_name LIKE '_wc_session_expires_%'
				" );

				wp_cache_flush();

			break;
			default:
				$action = esc_attr( $_GET['action'] );
				if( isset( $tools[ $action ]['callback'] ) ) {
					$callback = $tools[ $action ]['callback'];
					$return = call_user_func( $callback );
					if( $return === false ) {
						if( is_array( $callback ) ) {
							echo '<div class="error"><p>' . sprintf( __( 'There was an error calling %s::%s', 'eshopbox' ), get_class( $callback[0] ), $callback[1] ) . '</p></div>';

						} else {
							echo '<div class="error"><p>' . sprintf( __( 'There was an error calling %s', 'eshopbox' ), $callback ) . '</p></div>';
						}
					}
				}
		}
	}

	?>
	<table class="wc_status_table widefat" cellspacing="0">

        <thead class="tools">
			<tr>
				<th colspan="2"><?php _e( 'Tools', 'eshopbox' ); ?></th>
			</tr>
		</thead>

		<tbody class="tools">
		<?php foreach($tools as $action => $tool) { ?>
			<tr>
                <td><?php echo esc_html( $tool['name'] ); ?></td>
                <td>
                	<p>
                    	<a href="<?php echo wp_nonce_url( admin_url('admin.php?page=eshopbox_status&tab=tools&action=' . $action ), 'debug_action' ); ?>" class="button"><?php echo esc_html( $tool['button'] ); ?></a>
                    	<span class="description"><?php echo wp_kses_post( $tool['desc'] ); ?></span>
                	</p>
                </td>
            </tr>
		<?php } ?>
		</tbody>
	</table>
	<?php
}
