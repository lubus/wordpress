<?php
    /*
    Plugin Name:Eshop_Sms_Guru
    Plugin URI:
    Description:Declares a plugin that will be visible in the WordPress admin interface and help for sms integeration on your website.
    Version:1.4
    Author:Shalu
    Author URI: Eshopbox.com
    License:GPLv2
    */
global $eshopbox;
if ( !defined('TWILIOSMS_URL') )
define( 'TWILIOSMS_URL', plugin_dir_url( __FILE__ ) );
if ( !defined('TWILIOSMS_PATH') )
define( 'TWILIOSMS_PATH', plugin_dir_path( __FILE__ ) );
if ( !defined('TWILIOSMS_BASENAME') )
define( 'TWILIOSMS_BASENAME', plugin_basename( __FILE__ ) );
if ( !defined('TWILIOSMS_themeNAME') )
define( 'TWILIOSMS_THEMENAME', get_theme_root());
define( 'TWILIOSMS_FILE', __FILE__ );
if(!class_exists('Smsclass'))
{
    require TWILIOSMS_PATH.'smsclass.php' ;
}

//action hooks for order status
$allstatus=array('failed','processing','completed','refunded','cancelled');

//action hooks for  status change of an order
foreach($allstatus as $key)
    {
        add_action( 'eshopbox_order_status_'.$key, array($sms,'mysite_'.$key));
    }
add_action( 'eshopbox_order_status_on-hold',array($sms,'mysite_hold') );
add_action( 'eshopbox_payment_complete', array($sms,'mysite_payment_complete'));

//function to create ui on thank you page in case of cod payment mode
function cod_confirmation_block($order_id)
    {
		//echo $order_id;exit;
		$order = new WC_Order($order_id );

		$orderdetail=$order-> order_custom_fields;
		$bilphnno=$orderdetail['_billing_phone'];
		$bilphnno= $bilphnno[0];               
                $smsstatus=get_post_meta($order->id ,'cod_confirm',true);
                $bilphnno="0".$bilphnno;
		//echo "phn no".$bilphnno;exit;
                
                if($order->status != 'failed' )
                {
                    if($order->payment_method == 'cod'){
                        $order_status=get_post_meta($order->id,'cod_confirm',true);
                        if($order_status!=4)
                        {
                            $thanksblock='<div class="tickicon_orange">Congratulations, your order has been placed.</div>';
                            $thanksblock.='<div class="tickicon_green" style="display: none">Congratulations, your order has been placed.</div>';
                            $thanksblock.=' <div class="sucessfully_order">Your order has been successfully placed. Please Verify your mobile number to help us process your order immediately.</div>';
                            $thanksblock.='<div class="sucessfully_order_cod_confirm" style="display:none">Your order has been placed sucessfully and right now is in processing.</div>';
                        }else{
                            $thanksblock="<div class='tickicon_green'>Congratulations, your order has been placed.</div>";
                                //$thanksblock.='<div class="congrat">Congratulation! Your order has been confirmed successfully</div>';
                            //$thanksblock.='<div class="sucessfully_order">Your order id is #'.$order_id.'. A confirmation mail regarding this order has been send to <strong>'.$order->billing_email.'</strong></div>';
							$thanksblock.='<div class="sucessfully_order green_success">Your order has been successfully placed and being processed. A confirmation mail regarding this order has been send to <strong>'.$order->billing_email.'</strong></div>';
                            //$thanksblock.='<div class="cls_cn">Your order will be processed as per the delivery details.</div>
                              
                        }
                    }else{
                        $thanksblock.="<div class='tickicon_green'>Congratulations, your order has been placed.</div>";
                    }
                    $thanksblock.='<div class="confirmation_order" style="display:none" >You can track your order from your account page. A confirmation mail regarding this order has been send to <strong>'.$order->billing_email.'</strong></div>';
					
                }
        $cod_form=$thanksblock;
        $cod_form.=  '<div>';
        $cod_form.='<form id="cod_form" method="post" action="">
		
		<div class="mobileno_block">
		<div class="cover_block1">
		<div class="mobileno_headingblock">Mobile No: <strong>'.$bilphnno.'</strong></div>
		<div class="mobile_text">
			<span class="mobile_title">Verify Code:</span>
			<input type="hidden" id="cod_code_gen" value="'.get_post_meta($order_id ,'_order_confirmation_code',true).'"/>
			<input type="hidden" id="path" value="'.plugins_url().'"/>
			<input type="hidden" id="order_id" value="'.$order_id.'"/>
			<input type="hidden" id="order_status"  value="'.$smsstatus.'"/>
			<input type="text" id="cod_code"/>
			<input type="button" id="cod_submit" value="Verify Now"/>
			
			<span id="cod_resent_icon" style="display: none">Resent</span>
		</div>
		</div>
		
		<div class="verify_textblock">Verification code sent to <strong>'.$bilphnno.'</strong>.If you have not recieved it, Resend it by clicking the link 
        <span><input type="button"  value="Resend Verification Code"/></span>
		</div>
		</div>
        <div class="clear"></div>
		
		
		<span id="cod_confirm_msg" style="display: none">Your Order has been confirmed successfully</span>
        <span id="cod_error_msg" style="display: none">Please enter the valid confirmation code.</span>
        <span id="cod_resend_msg" style="display: none">The confirmation code has been resent to your number.</span>
		</form>
        </div>';
    if($smsstatus == 4)
        {
          $cod_form = $thanksblock.' <input type="hidden" id="order_status"  value="'.$smsstatus.'"/>';
        }
        echo $cod_form;
    }

//action hook of thank you page
add_action('eshopbox_thankyou_cod', 'cod_confirmation_block', 30);
add_action( 'eshopbox_process_shop_order_meta',array($sms,'mysite_shipment_sms'));
//function to js file
function load_sms_js()
    {
    global $wp_query;
    $post=$wp_query->post;
    $post_title=$post->post_title;
    if($post_title=='Order Received'){
        wp_enqueue_script('mysmsscript',TWILIOSMS_URL. 'sms_handling.js');
         wp_enqueue_style( 'my_style',  TWILIOSMS_URL .'sms.css');
    }
    }
add_action('wp_enqueue_scripts','load_sms_js');// load js in non-admin pages
//add_options_page('sms_connect','sms_connect', 'manage_options', $menu_slug, $function);

function eshop_cod_cofirm_email($classobj)
{
    //include_once(TWILIOSMS_PATH.'class-wc-email-cod-cofirm-order.php');
//$classobj['WC_Email_Cod_Confirm_Order'] = new WC_Email_Cod_Confirm_Order();
//return $classobj;
}
function payu_confirmation_block($order_id){
   //echo $order_id;exit;
		$order = new WC_Order($order_id );

		$orderdetail=$order-> order_custom_fields;
		$bilphnno=$orderdetail['_billing_phone'];
		$bilphnno= $bilphnno[0];
                $smsstatus=get_post_meta($order->id ,'cod_confirm',true);
                $bilphnno="0".$bilphnno;
		//echo "phn no".$bilphnno;exit;
                $thanksblock="<div class='tickicon_green'>Congratulations, your order has been placed..</div>";
                $thanksblock.='<div class="confirmation_order">You can track your order from your account page. A confirmation mail regarding this order has been send to <b>'.$order->billing_email.'</b></div>';
            echo     $thanksblock;
}
//add_filter('eshopbox_email_classes','eshop_cod_cofirm_email',1,3);
add_action('eshopbox_thankyou_payu_in', 'payu_confirmation_block', 30);
?>