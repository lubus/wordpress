<?php
    /*
    Plugin Name:Eshop_Email_Guru
    Plugin URI:
    Description:Declares a plugin that will be visible in the WordPress admin inside woocommerce setting tab inside email interface and help for email  integeration on your website.
    Version:1.3
    Author: Shalu
    Author URI: Eshopbox.com
    License:GPLv2
    */
if ( !defined('Eshop_Email_Guru_PATH') )
define( 'Eshop_Email_Guru_PATH', plugin_dir_path( __FILE__ ) );
if ( !defined('Eshop_Email_Guru_URL') )
define( 'Eshop_Email_Guru_URL', plugin_dir_url( __FILE__ ) );
if ( !defined('Eshop_Email_Guru_BASENAME') )
define( 'Eshop_Email_Guru_BASENAME', plugins_url() );
//echo MYPLUGIN_BASENAME;exit;

//function for adding custom emails to woocommerce
function eshop_custom_emails_ui($classobj)
    {
//        include_once( Eshop_Email_Guru_PATH.'/Classes/Emails/class-wc-email-abondoned-cart.php' );
        include_once( Eshop_Email_Guru_PATH.'/Classes/Emails/class-wc-email-abondoned-order.php' );
        include_once( Eshop_Email_Guru_PATH.'/Classes/Emails/class-wc-email-order-cancellation.php' );
        include_once( Eshop_Email_Guru_PATH.'/Classes/Emails/class-wc-email-order-shipment.php' );
        include_once( Eshop_Email_Guru_PATH.'/Classes/Emails/class-wc-email-order-refunded.php' );
        include_once( Eshop_Email_Guru_PATH.'/Classes/Emails/class-wc-email-neworder-cod.php' );
        include_once( Eshop_Email_Guru_PATH.'/Classes/Emails/class-wc-email-neworder-payu.php' );
         include_once( Eshop_Email_Guru_PATH.'/Classes/Emails/class-wc-email-payment-fail.php' );

        ///$emailobj=new WC_Emails();
  //      $classobj['WC_Email_Abondoned_Cart'] = new WC_Email_Abondoned_Cart();
        $classobj['WC_Email_Abondoned_Order'] = new WC_Email_Abondoned_Order();
        $classobj['WC_Email_Order_Cancellation'] = new WC_Email_Order_Cancellation();
        $classobj['WC_Email_Order_Fail'] = new WC_Email_Order_Fail();
        $classobj['WC_Email_Order_Shipment'] = new WC_Email_Order_Shipment();
        $classobj['WC_Email_Order_Refunded'] = new WC_Email_Order_Refunded();
        $classobj['WC_Email_Customer_New_Order_Cod'] = new WC_Email_Customer_New_Order_Cod();
        $classobj['WC_Email_Customer_New_Order_Payu'] = new WC_Email_Customer_New_Order_Payu();
        return $classobj;
    }


//function called to send email when payment gets failed
function payment_fail_email($order_id)
 { 
    $_SESSION['fail']='true';
    $_SESSION['orderid']=$order_id;
    $email=new WC_Emails();
 }

//function called for sending email on shipment of order
function eshop_function_shipment_mail($post_id)
{       
    if($_POST['wc_order_action']== 'send_email_order_shipped')
    {
        if ( isset( $_POST['tracking_number'] ) )
        {
            $tracking_provider =$_POST['tracking_provider'];
            $tracking_number=$_POST['tracking_number'];
            $order_id=$_POST['post_ID'];
            $_SESSION['shipped']='true';
            $_SESSION['orderid']=$order_id;
            $_SESSION['tracking_provider']=$tracking_provider;
            $_SESSION['tracking_number']=$tracking_number;
            $email=new WC_Emails();
           
       }
    }
}

//function called for sending email on cancellation of order
 function eshop_function_cancellation_mail($order_id)
{
    $_SESSION['cancelled']='true';
    $_SESSION['orderid']=$order_id;
    $email=new WC_Emails();
}

//function called for sending email on refund of order
function eshop_function_refunded_mail($order_id)
{
    $_SESSION['refunded']='true';
    $_SESSION['orderid']=$order_id;
    $email=new WC_Emails();
}


add_action( 'eshopbox_order_status_failed','payment_fail_email');
add_filter('eshopbox_email_classes','eshop_custom_emails_ui',1,3);
add_action( 'eshopbox_process_shop_order_meta','eshop_function_shipment_mail' );
//add_action( 'eshopbox_process_shop_order_meta','eshop_function_shipment_sms' );
add_action( 'eshopbox_order_status_cancelled','eshop_function_cancellation_mail');
add_action( 'eshopbox_order_status_refunded','eshop_function_refunded_mail');

?>
