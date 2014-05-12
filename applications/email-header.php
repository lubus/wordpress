 <?php
/**
 * Email Header
 *
 * @author 		WooThemes
 * @package 	eshopbox/Templates/Emails
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$support_email = get_option( 'support_email', '' );
$support_phone = get_option( 'support_phone', '' );
$website=get_bloginfo('siteurl');
$site_title = get_option( 'site_title', '' );
// Load colours
$bg 		= get_option( 'eshopbox_email_background_color' );
$body		= get_option( 'eshopbox_email_body_background_color' );
$base 		= get_option( 'eshopbox_email_base_color' );
$base_text 	= eshopbox_light_or_dark( $base, '#202020', '#ffffff' );
$text 		= get_option( 'eshopbox_email_text_color' );

$bg_darker_10 = eshopbox_hex_darker( $bg, 10 );
$base_lighter_20 = eshopbox_hex_lighter( $base, 20 );
$text_lighter_20 = eshopbox_hex_lighter( $text, 20 );

// For gmail compatibility, including CSS styles in head/body are stripped out therefore styles need to be inline. These variables contain rules which are added to the template inline. !important; is a gmail hack to prevent styles being stripped if it doesn't like something.
$wrapper = "
	background-color: " . esc_attr( $bg ) . ";
	width:100%;
	-webkit-text-size-adjust:none !important;
	margin:0;
	padding: 70px 0 70px 0;
";
$template_container = "
	-webkit-box-shadow:0 0 0 3px rgba(0,0,0,0.025) !important;
	box-shadow:0 0 0 3px rgba(0,0,0,0.025) !important;
	-webkit-border-radius:6px !important;
	border-radius:6px !important;
	background-color: " . esc_attr( $body ) . ";
	border: 1px solid $bg_darker_10;
	-webkit-border-radius:6px !important;
	border-radius:6px !important;
";
$template_header = "
	background-color: " . esc_attr( $base ) .";
	color: $base_text;
	-webkit-border-top-left-radius:6px !important;
	-webkit-border-top-right-radius:6px !important;
	border-top-left-radius:6px !important;
	border-top-right-radius:6px !important;
	border-bottom: 0;
	font-family:Arial;
	font-weight:bold;
	line-height:100%;
	vertical-align:middle;
";
$body_content = "
	
	-webkit-border-radius:0px !important;
	border-radius:0px !important;
";
$body_content_inner = "
	color: $text_lighter_20;
	font-family:Arial;
	font-size:14px;
	line-height:150%;
	text-align:left;
";
$header_content_h1 = "
	color: " . esc_attr( $base_text ) . ";
	margin:0;
	padding: 28px 24px;
	text-shadow: 0 1px 0 $base_lighter_20;
	display:block;
	font-family:Arial;
	font-size:30px;
	font-weight:bold;
	text-align:left;
	line-height: 150%;
";
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?php echo get_bloginfo('name'); ?></title>
        <style>
        body{
          font-family:Arial, Helvetica, sans-serif;
          font-size: 13px;
		  color:#777;
        }
        a{
          color: #46629E!important;
          text-decoration: underline !important;
        }
		 a:hover{
          color: #F58B0E!important;
          text-decoration:none!important;
        }
        p{
          color: #2b2b2b;
          font-size: 12px;
        }
        h1{
          color: #2b2b2b;
          font-size : 18px;
        }
        h2{
          font-size: 16px;
          color: #2b2b2b;
        }
        h3{
          font-size: 14px !important;
          color: #2b2b2b;
        }
		
        </style>
	</head>
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="padding:0px; margin:0px;">
  <tbody><tr>
    <td>
  <!--Start Banner -->
  <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color:#f1f1f1;-moz-box-shadow: 0px 0px 10px 1px #dfdfdf; -webkit-box-shadow: 0px 0px 10px 1px #dfdfdf;
box-shadow: 0px 0px 10px 1px #dfdfdf; border:1px solid #dfdfdf; ">  
      <tbody>
    <!-- Customer Message start -->
    <tr>
      <td>
        <table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
          <tbody>
          <tr>
            <td>
              <table width="100%" style="padding:20px;">
                <tr>
                  <td align="left" valign="top" style="padding:20px 0 0; font-size:14px; border-bottom: 0px solid #2b2b2b; font-family: arial,sans-serif, Arial, Helvetica, sans-serif; color:#777777;">
                    <div style="text-align:center"><a href="<?php echo $website; ?>" title="<?php echo $site_title;?>" target="_blank"><img src="<?php echo CLOUD_API_PATH; ?>/log2.png" alt="Factory Rush" width="181" height="29" border="0"></a></div>
                  </td>
                </tr>
              </table>
              </td>
            </tr>
            <tr>
                            	<td align="center" valign="top">
                                    <!-- Body -->
                                	<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body">
                                    	<tr>
                                            <td valign="top" style="<?php echo $body_content; ?>">
                                                <!-- Content -->
                                                <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td valign="top">
                                                            <div style="<?php echo $body_content_inner; ?>">
