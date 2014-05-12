<?php
/**
 * Email Footer
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Load colours
$base = get_option( 'eshopbox_email_base_color' );

$base_lighter_40 = eshopbox_hex_lighter( $base, 40 );

// For gmail compatibility, including CSS styles in head/body are stripped out therefore styles need to be inline. These variables contain rules which are added to the template inline.
$template_footer = "
	border-top:0;
	-webkit-border-radius:6px;
";

$credit = "
	border:0;
	color: $base_lighter_40;
	font-family: Arial;
	font-size:12px;
	line-height:125%;
	text-align:center;
";
 $store_fb = get_option( 'store_fb', '' );
$store_twitter = get_option( 'store_twitter', '' );
$store_pinterest = get_option( 'store_pinterest', '' );
$website=get_bloginfo('siteurl');
?>
															</div>
														</td>
                                                    </tr>
                                                </table>
                                                <!-- End Content -->
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- End Body -->
                                </td>
                            </tr>
                        	<tr>
                            	<td align="center" valign="top">
                                    <!-- Footer -->
                                	<table border="0" cellpadding="10" cellspacing="0" width="600" id="template_footer" style="font-size:14px; padding-top:0px; font-family: arial,sans-serif, Arial, Helvetica, sans-serif; color:#676767;">
                                    	<tr>
                                        	<td valign="top">
                                                <table border="0" cellpadding="10" cellspacing="0" width="100%" style="border-top: 6px solid #2b2b2b;">
                                                    <tr>
                                                        <td>
                                                            <table cellspacing="0" cellpadding="0" width="100%">
                                                                <tbody>
                                                                    <tr>
                                                                        <td align="center" style="font-family: Myriad Pro; font-size:12px; color:#2b2b2b;">
                                                                            FOLLOW US ON<br />
                                                                            <a href="<?php  echo $store_fb;?>" title="Facebook" tittle="Facebook"><img src="<?php echo CLOUD_API_PATH; ?>/facebook_1.png" width="20" height="20" alt="Facebook"></a>
                                                                            <a href="<?php  echo $store_twitter;?>" title="Twitter" tittle="Twitter"><img src="<?php echo CLOUD_API_PATH; ?>/twitter_1.png" width="20" height="20" alt="Twitter"></a>
                                                                            <a href="<?php  echo $store_pinterest;?>" title="Twitter" tittle="Twitter"><img src="<?php echo CLOUD_API_PATH; ?>/pin_1.png" width="22" height="22" alt="pin"></a>
                                                                            
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <table cellspacing="0" cellpadding="0" width="100%" style="padding-top:26px;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td align="left" style="font-family: Myriad Pro; font-size:12px; color:#2b2b2b; width:218px; text-align:cnter;">
                                                                          <a href="<?php echo $website ?>/shipping-delivery/" target="_blank" style="cursor:pointer;"> SHIPPING OPTION</a>
                                                                        </td>
                                                                        <td align="center" style="font-family: Myriad Pro; font-size:12px; color:#2b2b2b; text-align:cnter;">
                                                                           <a href="<?php echo $website ?>/contact/" target="_blank" style="cursor:pointer;">CUSTOMER CARE</a>
                                                                        </td>
                                                                        <td align="right" style="font-family: Myriad Pro; font-size:12px; color:#2b2b2b; text-align:cnter;">
                                                                           <a href="<?php echo $website ?>/returns-exchange/" target="_blank" style="cursor:pointer;">RETURN POLICY</a>
                                                                        </td>
                                                                    </tr>
<!--                                                                    <tr>
                                                                        <td align="left">
                                                                           <a href="<?php //echo $website ?>/shipping-and-handling/" target="_blank" style="cursor:pointer;" title="SHIPPING OPTION"><img src="<?php//       echo CLOUD_API_PATH; ?>/shipping.jpg" width="179" height="83" /></a>
                                                                        </td>
                                                                        <td align="center">
                                                                           <a href="<?php //echo $website ?>/contact-us/" target="_blank" style="cursor:pointer;" title="CUSTOMER CARE"><img src="<?php //echo CLOUD_API_PATH; ?>/care.jpg" width="80" height="80" /></a>
                                                                        </td>
                                                                        <td align="right">
                                                                           <a href="<?php //echo $website ?>/returns-and-cancellations/" target="_blank" style="cursor:pointer;" title="RETURN POLICY"><img src="<?php //echo CLOUD_API_PATH; ?>/return.jpg" width="100" height="109" /></a>
                                                                        </td>
                                                                    </tr>-->
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center">
                                                                <a href="<?php echo $website ?>/privacy-policy/" title="Click Here" style="font-size:11px; font-family:Myriad Pro; color: #747474;" target="_blank">   To view Privacy Policy click here</a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- End Footer -->
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>
