<?php
/*
Plugin Name: Cloud Server FTP Path Setup
Description: Set the FTP path in setting->media for upload file on cloud server
Author: Boxbeat Technologies Pvt Ltd
Version: 1.0
Author URI: http://theboxbeat.com/
*/

function cloud_ftp_path_activation() {
	if (get_option('upload_path') == '' || get_option('upload_url_path') == '') {
	//-- CLOUD_API_PATH defined in exhopbox-config.php
		update_option('upload_url_path',CLOUD_API_PATH,true); 
		update_option('upload_path','eshop-content/uploads',true);
		wp_redirect(admin_url('options-media.php'));
		exit();
	}
}
register_activation_hook( __FILE__, 'cloud_ftp_path_activation');