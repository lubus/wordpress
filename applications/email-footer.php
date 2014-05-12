<?php
/**
 * Email Footer
 *
 * @author 		WooThemes
 * @package 	eshopbox/Templates/Emails
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
//echo '<pre>';print_r($_REQUEST);
//$_REQUEST['_payment_method']=='cod'
if($_REQUEST['wc_order_action']=='send_email_order_shipped'){
 $whatnext=  'Just wait for your order, you will receive it soon.' ;
}
if($_REQUEST['wc_order_action']=='send_email_customer_completed_order' || $_REQUEST['order_status']=='completed'){
   $whatnext=' Explore our collection and shop another one for one.';
}
if($_REQUEST['order_status']=='processing'){
    $whatnext='You will receive an email with your courier Tracking ID & a link to track your order.';

} 
        if($_REQUEST['order_status']=='on-hold'){
    $whatnext='You will receive a call from our customer care executive to confirm your order.';

}
if($_REQUEST['order_status']=='cancelled'){
    $whatnext='Visit factoryrush.com and try placing another order.';
}
if($_REQUEST['order_status']=='refunded'){
    $whatnext='Wait to receive the store credits.';
} 
        if($_REQUEST['order_status']=='failed'){
    $whatnext='Go to factoryrush.com and complete your payment.';
}
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
                                	<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_footer" style="border-top:1px solid #e0e0e0">
                                    	<tr>
                                        	<td valign="top">
                                                <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td>
                                                            <table cellspacing="0" cellpadding="0" width="100%">
                                                                <tbody>
                                                                    <tr>
                                                                    	<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#666666; width:38%;  text-align:center;">What Next? <br/><span style="text-align:left; font-size:12px; color:#777; display:inline-block; width:100%; margin:12px 0 0 0;"><?php echo $whatnext;?></span></td>
                                                                        <td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#666666; width:38%; text-align:center; vertical-align:top;">Need Help<br/><span style="text-align:center; font-size:12px; color:#777; display:inline-block; width:100%; margin:12px 0 0 0;">Mail us at support@factoryrush.com</span></td>
                                                                        <td align="center" style=" vertical-align:top; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#666666; text-align:center; ">
                                                                            Follow Us<br />
                                                                            <span style="text-align:center; font-size:12px; color:#777; display:inline-block; width:100%; margin:12px 0 0 0;"><a href="https://www.facebook.com/privatelivesnightwear" title="Facebook" tittle="Facebook"><img src="http://speed.eshopbox.com/pri64f956630ae93c1f3293234dc378d/facebook_1.png" width="8" height="15" alt="Facebook"></a>
                                                                            <a href="https://twitter.com/Private__Lives"  style="margin:0 15px;" title="Twitter" tittle="Twitter"><img src="http://speed.eshopbox.com/pri64f956630ae93c1f3293234dc378d/twitter_1.png" width="20" height="15" alt="Twitter"></a>
                                                                            <a href="http://www.pinterest.com/privatelives1/" title="Pinterest" tittle="Pinterest"><img src="http://speed.eshopbox.com/pri64f956630ae93c1f3293234dc378d/pin_1.png" width="18" height="18" alt="Pinterest"></a></span>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-family:Arial, Helvetica, sans-serif; padding-top:30px; font-size:14px; color:#666666; text-align:center; border-bottom:1px solid #e0e0e0;">
                                                            factoryrush.com
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#777; text-align:center;">
                                                               Buy One Get One Free. From factory floor to your door.
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
