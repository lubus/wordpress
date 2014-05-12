<?php
/**
 * EshopBox Updates
 *
 * Plugin updates script which updates the database.
 *
 * @author 		WooThemes
 * @category 	Admin
 * @package 	EshopBox/Admin/Updates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Runs the installer.
 *
 * @access public
 * @return void
 */
function do_update_eshopbox() {
	global $eshopbox;

	// Include installer so we have page creation functions
	include_once( 'eshopbox-admin-install.php' );

	// Do updates
	$current_db_version = get_option( 'eshopbox_db_version' );

	if ( version_compare( $current_db_version, '1.4', '<' ) ) {
		include( 'includes/updates/eshopbox-update-1.4.php' );
		update_option( 'eshopbox_db_version', '1.4' );
	}

	if ( version_compare( $current_db_version, '1.5', '<' ) ) {
		include( 'includes/updates/eshopbox-update-1.5.php' );
		update_option( 'eshopbox_db_version', '1.5' );
	}

	if ( version_compare( $current_db_version, '2.0', '<' ) ) {
		include( 'includes/updates/eshopbox-update-2.0.php' );
		update_option( 'eshopbox_db_version', '2.0' );
	}

	if ( version_compare( $current_db_version, '2.0.9', '<' ) ) {
		include( 'includes/updates/eshopbox-update-2.0.9.php' );
		update_option( 'eshopbox_db_version', '2.0.9' );
	}

	if ( version_compare( $current_db_version, '2.0.15', '<' ) ) {
		include( 'includes/updates/eshopbox-update-2.0.15.php' );
		update_option( 'eshopbox_db_version', '2.0.15' );
	}

	update_option( 'eshopbox_db_version', $eshopbox->version );
}