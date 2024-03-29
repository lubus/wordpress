<?php
/*
Plugin Name: eshopbox Product CSV Import Suite_old
Plugin URI: http://woothemes.com/eshopbox/
Description: Import and export products and variations straight from boxbeat admin. Go to eshopbox > CSV Import Suite to get started. Supports post fields, product data, custom post types, taxonomies, and images.
Author: Mike Jolley
Author URI: http://mikejolley.com
Version: 1.1.9
Text Domain: wc_csv_importer

	Copyright: © 2009-2011 WooThemes.
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html

	Adapted from the boxbeat post importer by the boxbeat team
*/

/**
 * Required functions
 */
if ( ! function_exists( 'woothemes_queue_update' ) )
	require_once( 'woo-includes/woo-functions.php' );

/**
 * Plugin updates
 */
woothemes_queue_update( plugin_basename( __FILE__ ), '7ac9b00a1fe980fb61d28ab54d167d0d', '18680' );

/**
 * Check eshopbox exists
 */
//if ( is_eshopbox_active() ) {

	if ( ! is_admin() ) return;

	/**
	 * Localisation
	 **/
	load_plugin_textdomain('wc_csv_import', false, dirname( plugin_basename( __FILE__ ) ) . '/languages');

	/**
	 * WC_CSV_Import_Suite class
	 **/
	if (!class_exists('WC_Product_CSV_Import_Suite')) {

		class WC_Product_CSV_Import_Suite {

			var $post_columns;
			var $variation_columns;
			var $errors = array();

			public function __construct() {

				add_action( 'admin_init', array(&$this, 'init_fields'), 5 );
				add_action( 'admin_init', array(&$this, 'admin_init') );
				add_action( 'admin_menu', array(&$this, 'admin_menu') );
				add_action( 'admin_print_styles', array(&$this, 'admin_scripts') );
			}

			function init_fields() {

				// Post data to export
				$this->post_columns = apply_filters('eshopbox_csv_product_post_columns', array(
					'post_title'		=> 'post_title',
					'post_name'			=> 'post_name',
					'ID' 				=> 'ID',
					'post_excerpt'		=> 'post_excerpt',
					'post_content'		=> 'post_content',
					'post_status'		=> 'post_status',
					'menu_order'		=> 'menu_order',
					'post_date'			=> 'post_date',
					'post_parent'		=> 'post_parent',
					'comment_status'	=> 'comment_status',

					// Meta
					'_sku'				=> 'sku',
					'_downloadable' 	=> 'downloadable',
					'_virtual'			=> 'virtual',
					'_visibility'		=> 'visibility',
					'_stock'			=> 'stock',
					'_stock_status'		=> 'stock_status',
					'_backorders'		=> 'backorders',
					'_manage_stock'		=> 'manage_stock',
					'_regular_price'	=> 'regular_price',
					'_sale_price'		=> 'sale_price',
					'_weight'			=> 'weight',
					'_length'			=> 'length',
					'_width'			=> 'width',
					'_height'			=> 'height',
					'_tax_status'		=> 'tax_status',
					'_tax_class'		=> 'tax_class',
					'_upsell_ids'		=> 'upsell_ids',
					'_crosssell_ids'	=> 'crosssell_ids',
					'_featured'			=> 'featured',

					'_sale_price_dates_from' 	=> 'sale_price_dates_from',
					'_sale_price_dates_to' 		=> 'sale_price_dates_to',

					// Downloadable products
					'_file_path'		=> 'file_path',
					'_file_paths'		=> 'file_paths',
					'_download_limit'	=> 'download_limit',
					'_download_expiry'	=> 'download_expiry',

					// Virtual products
					'_product_url'		=> 'product_url',
					'_button_text'		=> 'button_text',

					// YOAST
					'_yoast_wpseo_focuskw'      => 'meta:_yoast_wpseo_focuskw',
					'_yoast_wpseo_title'        => 'meta:_yoast_wpseo_title',
					'_yoast_wpseo_metadesc'     => 'meta:_yoast_wpseo_metadesc',
					'_yoast_wpseo_metakeywords' => 'meta:_yoast_wpseo_metakeywords',
				));

				// Post data to export
				$this->variation_columns = apply_filters('eshopbox_csv_product_variation_post_columns', array(
					'post_parent'		=> 'post_parent',
					'ID' 				=> 'ID',
					'post_status'		=> 'post_status',

					// Core product data
					'_sku'				=> 'sku',
					'_downloadable' 	=> 'downloadable',
					'_virtual'			=> 'virtual',
					'_stock'			=> 'stock',
					'_price'			=> 'price',
					'_regular_price'	=> 'regular_price',
					'_sale_price'		=> 'sale_price',
					'_weight'			=> 'weight',
					'_length'			=> 'length',
					'_width'			=> 'width',
					'_height'			=> 'height',

					// Downloadable products
					'_file_path'		=> 'file_path',
					'_file_paths'		=> 'file_paths',
					'_download_limit'	=> 'download_limit',
				));

				// 2.0
				if ( defined('eshopbox_VERSION') && version_compare( eshopbox_VERSION, "2.0.0" ) >= 0 ) {
					unset( $this->variation_columns['_price'] );
				} else {
					unset( $this->variation_columns['_regular_price'] );
				}
			}

			/**
			 * Admin Init
			 */
			function admin_init() {
				register_importer( 'eshopbox_csv', 'eshopbox Products (CSV)', __('Import <strong>products</strong> to your store via a csv file.', 'wc_csv_import'), array( &$this, 'product_importer' ) );

				register_importer( 'eshopbox_variation_csv', 'eshopbox Product Variations (CSV)', __('Import <strong>product variations</strong> to your store via a csv file.', 'wc_csv_import'), array( &$this, 'variation_importer' ) );

				if (!empty($_GET['action']) && !empty($_GET['page']) && $_GET['page']=='eshopbox_csv_import_suite') {

					switch ($_GET['action']) {

						case "export" :

							$this->product_exporter( 'product' );

						break;
						case "export_variations" :

							$this->product_exporter( 'product_variation' );

						break;

					}

				}
			}

			/**
			 * Admin Menu
			 */
			function admin_menu() {

				$page = add_submenu_page('eshopbox', __( 'CSV Import Suite', 'wc_csv_import' ), __( 'CSV Import Suite', 'wc_csv_import' ), 'edit_posts', 'eshopbox_csv_import_suite', array(&$this, 'admin_page') );

				//add_action('admin_print_styles-' . $page, array(&$this, 'admin_scripts'));

			}

			/**
			 * Admin Page
			 */
			function admin_scripts() {
				global $eshopbox;

				wp_enqueue_script( 'eshopbox_admin' );
				wp_enqueue_script( 'chosen' );
				wp_enqueue_style( 'eshopbox_admin_styles', $eshopbox->plugin_url() . '/assets/css/admin.css' );
				wp_register_style( 'eshopbox-csv_importer', plugins_url( basename( plugin_dir_path(__FILE__) ) . '/css/style.css', basename( __FILE__ ) ), '', '1.0.0', 'screen' );
	  			wp_enqueue_style( 'eshopbox-csv_importer' );
			}

			/**
			 * Admin Page
			 */
			function admin_page() {

				global $eshopbox;

				$tab = ( !empty($_GET['tab']) && $_GET['tab'] == 'export' ) ? 'export' : 'import';
				?>

				<div class="wrap eshopbox">
					<div class="icon32" id="icon-eshopbox-importer"><br></div>
				    <h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
				        <a href="<?php echo admin_url('admin.php?page=eshopbox_csv_import_suite') ?>" class="nav-tab <?php echo ($tab == 'import') ? 'nav-tab-active' : ''; ?>"><?php _e('Import Products', 'wc_csv_import'); ?></a><a href="<?php echo admin_url('admin.php?page=eshopbox_csv_import_suite&tab=export') ?>" class="nav-tab <?php echo ($tab == 'export') ? 'nav-tab-active' : ''; ?>"><?php _e('Export Products', 'wc_csv_import'); ?></a>
				    </h2>

					<?php
						switch ($tab) {
							case "export" :
								$this->admin_export_page();
							break;
							default :
								$this->admin_import_page();
							break;
						}
					?>

				</div>

				<script type="text/javascript">

					jQuery("select.chosen_select").chosen();

				</script>
				<?php

			}

			function admin_import_page() {
				global $eshopbox;
				?>
				<div id="message" class="updated eshopbox-message wc-connect">
					<div class="squeezer">
						<h4><?php _e( '<strong>Product CSV Import Suite</strong> &#8211; Before getting started prepare your CSV files', 'eshopbox' ); ?></h4>

						<p class="submit"><a href="http://www.woothemes.com/eshopbox-docs/user-guide/extensions/product-csv-import-suite/" class="button-primary"><?php _e( 'Documentation', 'eshopbox' ); ?></a> <a class="docs button-primary" href="<?php echo plugins_url( 'sample.csv', __FILE__ ); ?>"><?php _e('Sample CSV', 'eshopbox'); ?></a></p>

						<p>
					</div>
				</div>

				<div class="tool-box">

					<h3 class="title"><?php _e('Import Product CSV', 'wc_csv_import'); ?></h3>
					<p><?php _e('Import simple, grouped, external and variable products into eshopbox using this tool.', 'wc_csv_import'); ?></p>
					<p class="description"><?php _e('Upload a CSV from your computer. Click import to import your CSV as new products (existing products will be skipped), or click merge to merge products, ninja style. Importing requires the <code>post_title</code> column, whilst merging requires <code>sku</code> or <code>id</code>.', 'wc_csv_import'); ?></p>

					<p class="submit"><a class="button" href="<?php echo admin_url('admin.php?import=eshopbox_csv'); ?>"><?php _e('Import Products', 'wc_csv_import'); ?></a> <a class="button" href="<?php echo admin_url('admin.php?import=eshopbox_csv&merge=1'); ?>"><?php _e('Merge Products', 'wc_csv_import'); ?></a></p>

				</div>

				<div class="tool-box">

					<h3 class="title"><?php _e('Import Product Variations CSV', 'wc_csv_import'); ?></h3>
					<p><?php _e('Import and add variations to your variable products using this tool.', 'wc_csv_import'); ?></p>
					<p class="description"><?php _e('Each row must be mapped to a variable product via a <code>post_parent</code> or <code>parent_sku</code> column in order to import successfully. Merging also requires a <code>sku</code> or <code>id</code> column.', 'wc_csv_import'); ?></p>
					<p class="submit"><a class="button" href="<?php echo admin_url('admin.php?import=eshopbox_variation_csv'); ?>"><?php _e('Import Variations', 'wc_csv_import'); ?></a> <a class="button" href="<?php echo admin_url('admin.php?import=eshopbox_variation_csv&merge=1'); ?>"><?php _e('Merge Variations', 'wc_csv_import'); ?></a></p>

				</div>
				<?php
			}

			function admin_export_page() {
				global $eshopbox;
				?>
				<div class="tool-box">

					<h3 class="title"><?php _e('Export Product CSV', 'wc_csv_import'); ?></h3>
					<p><?php _e('Export your products using this tool. This exported CSV will be in an importable format.', 'wc_csv_import'); ?></p>
					<p class="description"><?php _e('Click export to save your products to your computer.', 'wc_csv_import'); ?></p>

					<form action="<?php echo admin_url('admin.php?page=eshopbox_csv_import_suite&action=export'); ?>" method="post">

						<table class="form-table">
							<tr>
								<th>
									<label for="v_limit"><?php _e( 'Limit', 'wc_csv_import' ); ?></label>
								</th>
								<td>
									<input type="text" name="limit" id="v_limit" placeholder="<?php _e('Unlimited', 'wc_csv_import'); ?>" class="input-text" />
								</td>
							</tr>
							<tr>
								<th>
									<label for="v_offset"><?php _e( 'Offset', 'wc_csv_import' ); ?></label>
								</th>
								<td>
									<input type="text" name="offset" id="v_offset" placeholder="<?php _e('0', 'wc_csv_import'); ?>" class="input-text" />
								</td>
							</tr>
							<tr>
								<th>
									<label for="v_columns"><?php _e( 'Columns', 'wc_csv_import' ); ?></label>
								</th>
								<td>
									<select id="v_columns" name="columns[]" data-placeholder="<?php _e('All Columns', 'wc_csv_import'); ?>" class="chosen_select" multiple="multiple">
										<?php
											foreach ($this->post_columns as $key => $column) {
												echo '<option value="'.$key.'">'.$column.'</option>';
											}
											echo '<option value="images">'.__('Images (featured and gallery)', 'wc_csv_import').'</option>';
											echo '<option value="taxonomies">'.__('Taxonomies (cat/tags/shipping-class)', 'wc_csv_import').'</option>';
											echo '<option value="attributes">'.__('Attributes', 'wc_csv_import').'</option>';
											echo '<option value="meta">'.__('Meta (custom fields)', 'wc_csv_import').'</option>';
										?>
		       						</select>
								</td>
							</tr>
						</table>

						<p class="submit"><input type="submit" class="button" value="<?php _e('Export Products', 'wc_csv_import'); ?>" /></p>

					</form>
				</div>

				<div class="tool-box">

					<h3 class="title"><?php _e('Export Product Variations CSV', 'wc_csv_import'); ?></h3>
					<p><?php _e('Export your product variations using this tool. This exported CSV will be in an importable format.', 'wc_csv_import'); ?></p>
					<p class="description"><?php _e('Click export to save your products variations to your computer.', 'wc_csv_import'); ?></p>

					<form action="<?php echo admin_url('admin.php?page=eshopbox_csv_import_suite&action=export_variations'); ?>" method="post">

						<table class="form-table">
							<tr>
								<th>
									<label for="limit"><?php _e( 'Limit', 'wc_csv_import' ); ?></label>
								</th>
								<td>
									<input type="text" name="limit" id="limit" placeholder="<?php _e('Unlimited', 'wc_csv_import'); ?>" class="input-text" />
								</td>
							</tr>
							<tr>
								<th>
									<label for="offset"><?php _e( 'Offset', 'wc_csv_import' ); ?></label>
								</th>
								<td>
									<input type="text" name="offset" id="offset" placeholder="<?php _e('0', 'wc_csv_import'); ?>" class="input-text" />
								</td>
							</tr>
							<tr>
								<th>
									<label for="columns"><?php _e( 'Columns', 'wc_csv_import' ); ?></label>
								</th>
								<td>
									<select id="columns" name="columns[]" data-placeholder="<?php _e('All Columns', 'wc_csv_import'); ?>" class="chosen_select" multiple="multiple">
										<?php
											foreach ($this->variation_columns as $key => $column) {
												echo '<option value="'.$key.'">'.$column.'</option>';
											}
											echo '<option value="images">'.__('Images (featured and gallery)', 'wc_csv_import').'</option>';
											echo '<option value="taxonomies">'.__('Taxonomies (cat/tags/shipping-class)', 'wc_csv_import').'</option>';
											echo '<option value="meta">'.__('Meta (custom fields)', 'wc_csv_import').'</option>';
										?>
		       						</select>
								</td>
							</tr>

						</table>

						<p class="submit"><input type="submit" class="button" value="<?php _e('Export Variations', 'wc_csv_import'); ?>" /></p>

					</form>
				</div>
				<?php
			}

			/**
			 * Product Importer Tool
			 */
			function product_importer() {

				if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) return;

				// Load Importer API
				require_once ABSPATH . 'eshop-admin/includes/import.php';

				if ( ! class_exists( 'WP_Importer' ) ) {
					$class_wp_importer = ABSPATH . 'eshop-admin/includes/class-wp-importer.php';
					if ( file_exists( $class_wp_importer ) ) require $class_wp_importer;
				}

				// includes
				require dirname( __FILE__ ) . '/classes/class-wc-product-import.php';
				require dirname( __FILE__ ) . '/classes/class-wc-csv-parser.php';
				require dirname( __FILE__ ) . '/classes/class-wc-csv-log.php';

				// Dispatch
				global $WC_CSV_Product_Import;

				$WC_CSV_Product_Import = new WC_CSV_Product_Import();

				$WC_CSV_Product_Import->dispatch();

			}

			/**
			 * Variation Importer Tool
			 */
			function variation_importer() {

				if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) return;

				// Load Importer API
				require_once ABSPATH . 'eshop-admin/includes/import.php';

				if ( ! class_exists( 'WP_Importer' ) ) {
					$class_wp_importer = ABSPATH . 'eshop-admin/includes/class-wp-importer.php';
					if ( file_exists( $class_wp_importer ) ) require $class_wp_importer;
				}

				// includes
				require dirname( __FILE__ ) . '/classes/class-wc-product-import.php';
				require dirname( __FILE__ ) . '/classes/class-wc-product_variation-import.php';
				require dirname( __FILE__ ) . '/classes/class-wc-csv-parser.php';
				require dirname( __FILE__ ) . '/classes/class-wc-csv-log.php';

				// Dispatch
				global $WC_CSV_Product_Import;

				$WC_CSV_Product_Import = new WC_CSV_Product_Variation_Import();

				$WC_CSV_Product_Import->dispatch();

			}

			/**
			 * Product Exporter Tool
			 */
			function product_exporter( $post_type = 'product' ) {
				global $wpdb;

				$csv_columns = ( $post_type == 'product' ) ? $this->post_columns : $this->variation_columns;

				$product_taxonomies = get_object_taxonomies( $post_type, 'name' );

				$export_columns = ( ! empty($_POST['columns'] ) ) ? $_POST['columns'] : '';

				$product_args = array(
					'numberposts' 	=> ( ! empty($_POST['limit'] ) ) ? intval( $_POST['limit'] ) : -1,
					'post_status' 	=> array( 'publish', 'pending', 'private', 'draft' ),
					'post_type'		=> $post_type,
					'orderby' 		=> 'ID',
					'order'			=> 'ASC',
					'offset'		=> ( ! empty($_POST['offset'] ) ) ? intval( $_POST['offset'] ) : 0
				);

				if ( $post_type == 'product_variation' ) {
					$product_args['orderby'] = 'post_parent menu_order';
				}

				$products = get_posts( $product_args );

				if ( ! $products || is_wp_error( $products ) ) {
					$this->errors[] = __('Nothing found to export', 'wc_csv_import');
					return;
				}

				// Get all metadata
           		$all_meta_keys = $this->get_all_metakeys( $post_type );
           		$found_attributes = $this->get_all_product_attributes( $post_type );

				$wpdb->hide_errors();
				@set_time_limit(0);
				@ob_clean();
				header( 'Content-Type: text/csv; charset=UTF-8' );
				header( 'Content-Disposition: attachment; filename=eshopbox-product-export.csv' );
				header( 'Pragma: no-cache' );
				header( 'Expires: 0' );
           		$fp = fopen('php://output', 'w');

				// Loop products and load meta data
				$found_product_meta = array();

				// Some of the values may not be usable (e.g. arrays of arrays) but the worse
                // that can happen is we get an empty column.
				foreach ( $all_meta_keys as $meta ) {
                    if ( ! $meta ) continue;
                    if ( ! in_array( $meta, array_keys( $csv_columns ) ) && substr( $meta, 0, 1 ) == '_' ) continue;
                    $found_product_meta[] = $meta;
                }

                // Loop products
				foreach ( $products as $product ) {

					$meta_data = get_post_custom( $product->ID );

					$product->meta = new stdClass;
					$product->attributes = new stdClass;

					// Meta data
					foreach ( $meta_data as $meta => $value ) {

						if ( ! $meta ) continue;

						if ( ! in_array( $meta, array_keys( $csv_columns ) ) && substr( $meta, 0, 1 ) == '_' ) continue;

						$meta_value = maybe_unserialize( maybe_unserialize( $value[0] ) );

						if ( is_array( $meta_value ) ) {

							if ( ! isset( $meta_value[0] ) || is_array( $meta_value[0] ) )
								continue; // Can't handle multidimensional arrays

							$meta_value = implode( '|', $meta_value );
						}

						$product->meta->$meta = $this->format_export_meta( $meta_value, $meta );
					}

					// Product attributes
					if ( isset( $meta_data['_product_attributes'][0] ) ) {

						$attributes = maybe_unserialize(maybe_unserialize( $meta_data['_product_attributes'][0] ));

						if ( $attributes ) foreach ( $attributes as $key => $attribute ) {

							if ( ! $key || ! isset( $attribute['position'] ) || ! isset( $attribute['is_visible'] ) || ! isset( $attribute['is_variation'] ) ) continue;

							if ( $attribute['is_taxonomy'] == 1 ) {

								$terms = wp_get_post_terms( $product->ID, $key, array("fields" => "names") );
								if ( !is_wp_error( $terms ) ) {
									$attribute_value = implode( '|', $terms );
								} else {
									$attribute_value = '';
								}

							} else {

								$key = $attribute['name'];

								$attribute_value = $attribute['value'];

							}

							$attribute_data 	= $attribute['position'] . '|' . $attribute['is_visible'] . '|' . $attribute['is_variation'];

							$_default_attributes = isset( $meta_data['_default_attributes'][0]  ) ? maybe_unserialize( maybe_unserialize( $meta_data['_default_attributes'][0] ) ) : '';

							if ( is_array( $_default_attributes ) ) {

								$_default_attribute = isset( $_default_attributes[ $key ] ) ? $_default_attributes[ $key ] : '';

							} else {
								$_default_attribute = '';
							}

							$product->attributes->$key = array(
								'value'		=> $attribute_value,
								'data'		=> $attribute_data,
								'default'	=> $_default_attribute
							);

						}

					}

					// GPF
					if ( isset( $meta_data['_eshopbox_gpf_data'][0] ) ) {
						$product->gpf_data = $meta_data['_eshopbox_gpf_data'][0];
					}
				}

				$found_product_meta = array_diff( $found_product_meta, array_keys( $csv_columns ) );

				// Variable to hold the CSV data we're exporting
				$row = array();

				if ( $post_type == 'product_variation' ) {
					$row[] = 'Parent';
					$row[] = 'parent_sku';
				}

				// Export header rows
				foreach ( $csv_columns as $column => $value ) {
					if ( ! $export_columns || in_array( $column, $export_columns ) ) $row[] = esc_attr( $value );
				}

				// Handle special fields like taxonomies
				if ( ! $export_columns || in_array( 'images', $export_columns ) ) {
					$row[] = 'images';
				}

				if ( ! $export_columns || in_array( 'taxonomies', $export_columns ) ) {
					foreach ( $product_taxonomies as $taxonomy ) {
						if ( strstr( $taxonomy->name, 'pa_' ) ) continue; // Skip attributes

						$row[] = 'tax:' . $this->format_data( $taxonomy->name );
					}
				}

				if ( ! $export_columns || in_array( 'meta', $export_columns ) ) {
					foreach ( $found_product_meta as $product_meta ) {
						$row[] = 'meta:' . $this->format_data( $product_meta );
					}
				}

				if ( ! $export_columns || in_array( 'attributes', $export_columns ) ) {
					foreach ( $found_attributes as $attribute ) {
						$row[] = 'attribute:' . $this->format_data( $attribute );
						$row[] = 'attribute_data:' . $this->format_data( $attribute );
						$row[] = 'attribute_default:' . $this->format_data( $attribute );
					}
				}

				if ( function_exists( 'eshopbox_gpf_install' ) ) {
					$row[] = 'gpf:availability';
					$row[] = 'gpf:condition';
					$row[] = 'gpf:brand';
					$row[] = 'gpf:product_type';
					$row[] = 'gpf:google_product_category';
					$row[] = 'gpf:gtin';
					$row[] = 'gpf:mpn';
					$row[] = 'gpf:gender';
					$row[] = 'gpf:age_group';
					$row[] = 'gpf:color';
					$row[] = 'gpf:size';
				}

				//$headers[] = '"' . implode( '","', $row ) . '"';

				$row = array_map( array( &$this, 'wrap_column' ), $row );
				fwrite( $fp, implode( ',', $row ) . "\n" );
				//fputcsv( $fp, $row, ',', '"' );

				unset( $row );

				// Loop products
				foreach ( $products as $product ) {

					$row = array();

					if ( $post_type == 'product_variation' ) {

						$post_parent_title = get_the_title( $product->post_parent );

						if ( ! $post_parent_title ) continue;

						$row[] = $this->format_data( $post_parent_title );

						$parent_sku = get_post_meta( $product->post_parent, '_sku', true );

						$row[] = $parent_sku;

					}

					// Get column values
					foreach ( $csv_columns as $column => $value ) {
						if ( ! $export_columns || in_array( $column, $export_columns ) ) {

							if ( isset( $product->meta->$column ) ) {
								$row[] = $this->format_data( $product->meta->$column );
							} elseif ( isset( $product->$column ) && ! is_array( $product->$column ) ) {
								$row[] = $this->format_data( $product->$column );
							} else {
								$row[] = '';
							}
						}
					}

					// Export images/gallery
					if ( ! $export_columns || in_array( 'images', $export_columns ) ) {

						$image_file_names = array();

						// Featured image
						if ( $featured_image_id = get_post_thumbnail_id( $product->ID ) ) {
							$image_file_names[] = current( wp_get_attachment_image_src( $featured_image_id, 'full' ) );
						}

						// Images
						//$images = get_children( array('post_parent' => $product->ID, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID') );
						$image=get_post_meta($product->ID,'_product_image_gallery');
						$images=explode(',',$image[0]);
						//echo "<pre>";print_r($images);exit;
						$results = array();

						if ( $images ) {
							foreach ( $images as $image ) {
								if ( $featured_image_id==$image->ID ) continue;
								$image_file_names[] = current( wp_get_attachment_image_src( $image, 'full' ) );
							}
						}

						$row[] = implode( '|', $image_file_names );

					}

					// Export taxonomies
					if ( ! $export_columns || in_array( 'taxonomies', $export_columns ) ) {
						foreach ( $product_taxonomies as $taxonomy ) {
							if ( strstr( $taxonomy->name, 'pa_' ) ) continue; // Skip attributes

							$terms = wp_get_post_terms( $product->ID, $taxonomy->name, array("fields" => "names") );

							$row[] = $this->format_data( implode( '|', $terms ) );
						}
					}

					// Export meta data
					if ( ! $export_columns || in_array( 'meta', $export_columns ) ) {
						foreach ( $found_product_meta as $product_meta ) {
							if ( isset( $product->meta->$product_meta ) ) {
								$row[] = $this->format_data( $product->meta->$product_meta );
							} else {
								$row[] = '';
							}
						}
					}

					// Find and export attributes
					if ( ! $export_columns || in_array( 'attributes', $export_columns ) ) {
						foreach ( $found_attributes as $attribute ) {
							if ( isset( $product->attributes ) && isset( $product->attributes->$attribute ) ) {
								$values = $product->attributes->$attribute;
								$row[] = $this->format_data( $values['value'] );
								$row[] = $this->format_data( $values['data'] );
								$row[] = $this->format_data( $values['default'] );
							} else {
								$row[] = '';
								$row[] = '';
								$row[] = '';
							}
						}
					}

					// Export GPF
					if ( function_exists( 'eshopbox_gpf_install' ) ) {

						$gpf_data = empty( $product->gpf_data ) ? '' : maybe_unserialize( $product->gpf_data );

						$row[] = empty( $gpf_data['availability'] ) ? '' : $gpf_data['availability'];
						$row[] = empty( $gpf_data['condition'] ) ? '' : $gpf_data['condition'];
						$row[] = empty( $gpf_data['brand'] ) ? '' : $gpf_data['brand'];
						$row[] = empty( $gpf_data['product_type'] ) ? '' : $gpf_data['product_type'];
						$row[] = empty( $gpf_data['google_product_category'] ) ? '' : $gpf_data['google_product_category'];
						$row[] = empty( $gpf_data['gtin'] ) ? '' : $gpf_data['gtin'];
						$row[] = empty( $gpf_data['mpn'] ) ? '' : $gpf_data['mpn'];
						$row[] = empty( $gpf_data['gender'] ) ? '' : $gpf_data['gender'];
						$row[] = empty( $gpf_data['age_group'] ) ? '' : $gpf_data['age_group'];
						$row[] = empty( $gpf_data['color'] ) ? '' : $gpf_data['color'];
						$row[] = empty( $gpf_data['size'] ) ? '' : $gpf_data['size'];
					}

					// Add to csv
					//$csv[] = '"' . implode( '","', $row ) . '"';
					$row = array_map( array( &$this, 'wrap_column' ), $row );
					fwrite( $fp, implode( ',', $row ) . "\n" );
					//fputcsv( $fp, $row, ',', '"' );

					unset( $row );

				}

				fclose( $fp );

				exit;

			}

			function format_export_meta( $meta_value, $meta ) {
				if ( $meta == '_sale_price_dates_from' || $meta == '_sale_price_dates_to' ) {
					if ( $meta_value ) return date( 'Y-m-d', $meta_value );
				}

				return $meta_value;
			}

			function format_data( $data ) {
				$data = (string) $data;
				$enc = mb_detect_encoding( $data, 'UTF-8, ISO-8859-1', true );
				$data = ( $enc == 'UTF-8' ) ? $data : utf8_encode( $data );
				return $data;
			}

			function wrap_column( $data ) {
				return '"' . str_replace( '"', '""', $data ) . '"';
			}

			/**
             * Get a list of all the meta keys for a post type. This includes all public, private,
             * used, no-longer used etc. They will be sorted once fetched.
             */
            function get_all_metakeys( $post_type = 'product' ) {
                global $wpdb;

                $meta = $wpdb->get_col( $wpdb->prepare(
                    "SELECT DISTINCT pm.meta_key
                    FROM {$wpdb->postmeta} AS pm
                    LEFT JOIN {$wpdb->posts} AS p ON p.ID = pm.post_id
                    WHERE p.post_type = %s
                    AND p.post_status IN ( 'publish', 'pending', 'private', 'draft' )",
                    $post_type
                ) );

                sort( $meta );

                return $meta;
            }

            /**
             * Get a list of all the product attributes for a post type.
             * These require a bit more digging into the values.
             */
            function get_all_product_attributes( $post_type = 'product' ) {
                global $wpdb;

                $results = $wpdb->get_col( $wpdb->prepare(
                    "SELECT DISTINCT pm.meta_value
                    FROM {$wpdb->postmeta} AS pm
                    LEFT JOIN {$wpdb->posts} AS p ON p.ID = pm.post_id
                    WHERE p.post_type = %s
                    AND p.post_status IN ( 'publish', 'pending', 'private', 'draft' )
                    AND pm.meta_key = '_product_attributes'",
                    $post_type
                ) );

                // Go through each result, and look at the attribute keys within them.
                $result = array();

                if ( ! empty( $results ) ) {
                    foreach( $results as $_product_attributes ) {
                        $attributes = maybe_unserialize( maybe_unserialize( $_product_attributes ) );
                        if ( ! empty( $attributes ) && is_array( $attributes ) ) {
                        	foreach( $attributes as $key => $attribute ) {
                           		if ( ! $key || ! isset( $attribute['position'] ) || ! isset( $attribute['is_visible'] ) || ! isset( $attribute['is_variation'] ) )
                           	 		continue;

                           	 	if ( ! strstr( $key, 'pa_' ) )
                           	 		$key = $attribute['name'];

                           	 	$result[ $key ] = $key;
                           	 }
                        }
                    }
                }

                sort( $result );

                return $result;
            }

		}

	}

	$GLOBALS['wc_product_csv_import'] = new WC_Product_CSV_Import_Suite();

//}
