 <?php
/**
 * Email Header
 *
 * @author 		Eshopbox
 * @package 	 Eshop_email_guru/Templates/Emails
 * 
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
	background-color: " . esc_attr( $body ) . ";
	-webkit-border-radius:6px !important;
	border-radius:6px !important;
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
          font-family: "Myriad Pro", Arial;
          font-size: 12px;
        }
        a{
          color: #01b19f !important;
          text-decoration: none !important;
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
  <table width="600" border="0" bgcolor="#fff" align="center" cellpadding="0" cellspacing="0">  
      <tbody>
    <!-- Customer Message start -->
    <tr>
      <td>
        <table width="600" border="0" align="center" bgcolor="#ffffff" cellpadding="0" cellspacing="0"  style="border: 5px solid #2b2b2b;">
          <tbody>
          	<tr>
          		<td>
          			<table cellspacing="0" cellpadding="0" width="100%">
          				<tbody>
          					<tr>
          						<td align="left" style="font-family:Myriad Pro; color:#2b2b2b; font-size:11px; padding-left:20px; padding-top:20px;">
          							<img src="<?php echo CLOUD_API_PATH; ?>/mail.jpg"> &nbsp;
                        <a href="mailto:<?php echo $support_email;  ?>" target="_blank"  style="font-family:Myriad Pro; color:#2b2b2b; font-size:11px;"><?php echo $support_email; ?></a>
          						</td>
          						<td align="right" style="font-family:Myriad Pro; color:#2b2b2b; font-size:11px; padding-right:20px; padding-top:20px">
          							<img src="<?php echo CLOUD_API_PATH; ?>/phone.png"> <?php echo $support_phone;?>
          						</td>
          					</tr>
          				</tbody>
          			</table>
          		</td>
          	</tr>
          <tr>
            <td>
              <table width="100%" style="padding:20px;">
                <tr>
                  <td align="left" valign="top" bgcolor="#ffffff" style="padding:30px 20px 0px 20px; font-size:14px; border-bottom: 6px solid #2b2b2b; font-family: arial,sans-serif, Arial, Helvetica, sans-serif; color:#676767;">
                    <div style="text-align:center"><a href="<?php echo $website; ?>" title="<?php echo $site_title;?>" target="_blank"><img src="<?php echo CLOUD_API_PATH; ?>/baseoneone-logo.jpg" alt="Base One One" width="290" height="49" border="0"></a></div><br>
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
