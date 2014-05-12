<?php
/**
 * Init/register importers for EshopBox.
 *
 * @author 		WooThemes
 * @category 	Admin
 * @package 	EshopBox/Admin/Importers
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

register_importer( 'eshopbox_tax_rate_csv', __( 'EshopBox Tax Rates (CSV)', 'eshopbox' ), __( 'Import <strong>tax rates</strong> to your store via a csv file.', 'eshopbox'), 'eshopbox_tax_rates_importer' );

/**
 * eshopbox_tax_rates_importer function.
 *
 * @access public
 * @return void
 */
function eshopbox_tax_rates_importer() {

	// Load Importer API
	require_once ABSPATH . 'eshop-admin/includes/import.php';

	if ( ! class_exists( 'WP_Importer' ) ) {
		$class_wp_importer = ABSPATH . 'eshop-admin/includes/class-wp-importer.php';
		if ( file_exists( $class_wp_importer ) )
			require $class_wp_importer;
	}

	// includes
	require dirname( __FILE__ ) . '/tax-rates-importer.php';

	// Dispatch
	$WC_CSV_Tax_Rates_Import = new WC_CSV_Tax_Rates_Import();

	$WC_CSV_Tax_Rates_Import->dispatch();
}