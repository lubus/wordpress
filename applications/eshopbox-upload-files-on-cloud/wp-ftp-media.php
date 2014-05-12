<?php
/*
Plugin Name: Cloud Server Upload Media Library
Description: Let's you upload images to ftp-server and remove the upload on the local machine.
Author: Boxbeat Technologies Pvt Ltd
Version: 1.0
Author URI: http://theboxbeat.com/
*/

function upload_media_file_on_cloud_by_ftp( $args, $attachment_id ) {

	$upload_dir = wp_upload_dir();
	$upload_url = get_option('upload_url_path');
	$upload_yrm = get_option('uploads_use_yearmonth_folders');
	update_post_meta($attachment_id, '_wp_attachment_metadata',$args);
	$image_dir_array = array();
	if(!empty($args)){
		$image_dir_array[] = $args['file'];
		foreach($args['sizes'] as $filepath)
		{
			$image_dir_array[] = $filepath['file'];
		}
	}
	$root_dir_name = DOMAIN_API;	 
	/*
	$settings = array(
		'host'	  =>	'ftp.bitgravity.com',  			// * the ftp-server hostname
		'user'	  =>	'mayur.karwa@goo2o.biz', 				// * ftp-user
		'pass'	  =>	'awrak1ruyam',	 				// * ftp-password
		'cdn'     =>    'boxbeat.pc.cdn.bitgravity.com',			// * This have to be a pointed domain or subdomain to the root of the uploads
		'path'	  =>	'',	 					// - ftp-path, default is root (/). Change here and add the dir on the ftp-server,
		'base'	  =>     $upload_dir['basedir']  	// Basedir on local
	);*/
	$settings = array(
		'host'	  =>	FTP_HOST,  			// * the ftp-server hostname
		'user'	  =>	FTP_USERNAME, 		// * ftp-user
		'pass'	  =>	FTP_PASSWORD,	 	// * ftp-password
		'cdn'     =>    CDN_URL_PATH,		// * This have to be a pointed domain or subdomain to the root of the uploads
		'path'	  =>	FTP_DIR_PATH,	 	// - ftp-path, default is root (/). Change here and add the dir on the ftp-server,
		'base'	  =>    $upload_dir['basedir'] //- Basedir on local
	);
	/**
	 * Change the upload url to the ftp-server
	 */
	if( empty( $upload_url ) ) {
		update_option( 'upload_url_path', esc_url( $settings['cdn'] ) );
	}
	/**
	 * If uploads is stored like /uploads/year/month
	 * Remove and use only /uploads/
	 */
	if( $upload_yrm ) {
		update_option( 'uploads_use_yearmonth_folders', '' );
	}
	/**
	 * Host-connection
	 * Read about it here: http://php.net/manual/en/function.ftp-connect.php
	 */
	$connection = ftp_connect( $settings['host'] );
	/**
	 * Login to ftp
	 * Read about it here: http://php.net/manual/en/function.ftp-login.php
	 */
	$login = ftp_login( $connection, $settings['user'], $settings['pass'] );

	/**
	 * Check ftp-connection
	 */

	if ( !$connection || !$login ) {
	    die('Connection attempt failed, Check your settings');
	}

	/**
	 * Crate site directory if not created on cloud server
	 */
	if (@ftp_mkdir($connection, $root_dir_name)) {
		$settings['path'] = $settings['path'].'/'.$root_dir_name;
	} else {
		$settings['path'] = $settings['path'].'/'.$root_dir_name;
	}
	/**
	 * Get all files in uploads - local
	 * Remove hidden-files... mabye better solution
	 * http://php.net/manual/en/function.scandir.php
	 */
	$files_arr = preg_grep('/^([^.])/', scandir( $settings['base'] ) );
	// Cycle through all source files
        //foreach ( $files_arr as $file ) {
	foreach ( $image_dir_array as $file ) {
		/**
		 * If we ftp-upload successfully, mark it for deletion
		 * http://php.net/manual/en/function.ftp-put.php
		 */
		//echo $settings['path'] . "/" . $file.'---'.$settings['base'] . "/" . $file.'<br>';

		if( ftp_put( $connection, $settings['path'] . "/" . $file, $settings['base'] . "/" . $file, FTP_BINARY ) ) {
			$delete[] = $file;
		}
	}
}
add_filter( 'wp_generate_attachment_metadata', 'upload_media_file_on_cloud_by_ftp', 10, 2);