<?php
/**
 *
 *
 * This class contains all functions for sending sms on all order status
 *
 * @class 		Smsclass
 * @version		1.4
 * @package		Eshop_sms_guru
 */
class Smsclass
{
    public function __construct()
        {
            //$this-> exotel_sid = "blisshoes"; // Your Exotel SID
            //$this->exotel_token = "a6d7b8beadd6c06b41bfdf46f2aa9c51747dbab1"; // Your exotel token
            //$this->url = "https://".$this-> exotel_sid .":". $this->exotel_token."@twilix.exotel.in/v1/Accounts/".$this-> exotel_sid."/Sms/send";
            //$this->eshopexotel_sid = "boxbeattechnologies"; // eshop Exotel SID
            //$this->eshopexotel_token = "8a1b4402d530d035fd298aeac118f52571f5f8dd"; // eshop exotel token
            //$this->eshopurl = "https://".$this->eshopexotel_sid .":". $this->eshopexotel_token."@twilix.exotel.in/v1/Accounts/".$this->eshopexotel_sid ."/Sms/send";
            //$this->from='09212599988'; //companys phone no
            
        }

    //function to send sms on holding an order means on just placing an order
    public function mysite_hold($order_id)
        {   
            $order = new WC_Order( $order_id );
            //echo "hello";echo"<pre>";print_r($order);exit;
            $orderdetail=$order-> order_custom_fields;
            $frstname=ucfirst($order->shipping_first_name);
            $lstname=ucfirst($order->shipping_last_name);
            $fullname=$frstname." ".$lstname;
            $bilphnno=$orderdetail['_billing_phone'];
            $ordertotal=$orderdetail['_order_total'];
            $ordertotal=$ordertotal[0];
            $ordertotal=intval($ordertotal);
            $bilphnno= $bilphnno[0];
           // $bilphnno="+91".$bilphnno;
            $orderid=str_replace('#','',$order_id);
            $randomno=rand(100000,999999);
            $meta_key='_order_confirmation_code';
          
            $payment_type=get_post_meta($order->id ,'_payment_method',true);
             
            //$path='http://boxetplace.com/wishlist/';
            if($payment_type == 'cod')
                { 
                    add_post_meta($order_id, $meta_key, $randomno);		    
             $this->MSG=urlencode("Hi ".$fullname.", your order confirmation code is ".$randomno." . If not initiated by you then please ignore this message.");
             $this->url = 'http://103.247.98.91/API/SendMsg.aspx?uname=20140123&pass=BNCL7V.&send=PRCARE&dest='.$bilphnno.'&msg='.$this->MSG;
             $this->from='01126941196';
                    $post_cod_data = array
                        (
                            'From' => $this->from,
                            'To' => $bilphnno,
                            'Body' => "Hi ".$fullname.", your order confirmation code is ".$randomno." . If not initiated by you then please ignore this message.",
                        );
                    $ch1 = curl_init();
                    curl_setopt($ch1 , CURLOPT_VERBOSE, 1);
                    curl_setopt($ch1, CURLOPT_URL, $this->url);
                    curl_setopt($ch1 , CURLOPT_POST, 1);
                    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch1 , CURLOPT_FAILONERROR, 0);
                    curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, FALSE);
                    curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, FALSE);
                    curl_setopt($ch1, CURLOPT_POSTFIELDS,http_build_query($post_cod_data));
                    $http_result = curl_exec($ch1);
                    $error = curl_error($ch1);
                    //echo "error is ".$error."error finish";
                    $http_code = curl_getinfo($ch1 ,CURLINFO_HTTP_CODE);

                    curl_close($ch1);
                    add_post_meta($order_id,'cod_confirm',1);
                    // echo "<pre>";print "Response = ";print_r($http_result);exit;
                }
        }

    //function to send sms on pending status  of an order
    public function mysite_pending($order_id)
        {
//if needed
        }

    //function to send sms on failing of an order
    function mysite_failed($order_id)
        {
            $order = new WC_Order( $order_id );
            $orderdetail=$order-> order_custom_fields;
            $frstname=ucfirst($order->shipping_first_name);
            $lstname=ucfirst($order->shipping_last_name);
            $fullname=$frstname." ".$lstname;
            $bilphnno=$orderdetail[_billing_phone];
            $ordertotal=$orderdetail[_order_total];
            $ordertotal=$ordertotal[0];
            $ordertotal=intval($ordertotal);
            $bilphnno= $bilphnno[0];
            //$bilphnno="+91".$bilphnno;
            $orderid=str_replace('#','',$order_id);
            $payment_type=get_post_meta($order->id ,'_payment_method',true);
	    $this->MSG=urlencode("Dear ".$fullname.", we noticed that your purchase at phiverivers.com was not completed. Please check your email and try again. In case the amount has been debited, we will refund it.");
            $this->url = 'http://103.247.98.91/API/SendMsg.aspx?uname=20140123&pass=BNCL7V.&send=PRCARE&dest='.$bilphnno.'&msg='.$this->MSG;
            $this->from='01126941196';
            $post_data = array
                (
                    'From' => $this->from,
                    'To' => $bilphnno,
                    'Body' => "Dear ".$fullname.", we noticed that your purchase at phiverivers.com was not completed. Please check your email and try again. In case the amount has been debited, we will refund it.",
                );
            $ch = curl_init();
            curl_setopt($ch , CURLOPT_VERBOSE, 1);
            curl_setopt($ch , CURLOPT_URL, $this->url);
            curl_setopt($ch , CURLOPT_POST, 1);
            curl_setopt($ch , CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch , CURLOPT_FAILONERROR, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($post_data));
            $http_result = curl_exec($ch);
            $error = curl_error($ch);
            $http_code = curl_getinfo($ch ,CURLINFO_HTTP_CODE);
            curl_close($ch);
        }



    //function to send sms when  an order is in processing status
    function mysite_processing($order_id)
        {
            $order = new WC_Order( $order_id );
            $orderdetail=$order-> order_custom_fields;
            $frstname=ucfirst($order->shipping_first_name);
            $lstname=ucfirst($order->shipping_last_name);
            $fullname=$frstname." ".$lstname;
            $bilphnno=$orderdetail[_billing_phone];
            $ordertotal=$orderdetail[_order_total];
            $ordertotal=$ordertotal[0];
            $ordertotal=intval($ordertotal);
            $bilphnno= $bilphnno[0];
            //$bilphnno="+91".$bilphnno;
            $orderid=str_replace('#','',$order_id);
            $payment_type=get_post_meta($order->id ,'_payment_method',true);
		
            if($payment_type == 'cod')
                {
		$this->MSG=urlencode("Hi ".$fullname.", your order with order ID ".$orderid." is confirmed. It will be shipped from our warehouse soon. Check your email for more details. Thank you for shopping at phiverivers.com.");
                $this->url = 'http://103.247.98.91/API/SendMsg.aspx?uname=20140123&pass=BNCL7V.&send=PRCARE&dest='.$bilphnno.'&msg='.$this->MSG;
                $this->from='01126941196';
                //to buyer
                    $post_data = array
                        (
                            'From' => $this->from,
                            'To' => $bilphnno,
                            'Body' => "Hi ".$fullname.", your order with order ID ".$orderid." is confirmed. It will be shipped from our warehouse soon. Check your email for more details. Thank you for shopping at phiverivers.com.",

                        );
                    $ch = curl_init();
                    curl_setopt($ch , CURLOPT_VERBOSE, 1);
                    curl_setopt($ch , CURLOPT_URL, $this->url);
                    curl_setopt($ch , CURLOPT_POST, 1);
                    curl_setopt($ch , CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch , CURLOPT_FAILONERROR, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
                    curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($post_data));
                    $http_result = curl_exec($ch);
                    $error = curl_error($ch);
//echo "<pre>";print_r($post_data);exit;
                    $http_code = curl_getinfo($ch ,CURLINFO_HTTP_CODE);
                    curl_close($ch);
                    update_post_meta($order_id,'cod_confirm',4);
                    $_SESSION['cod_order_confirm']='true';
                    $_SESSION['orderid']=$order_id;
                    $email=new WC_Emails();


                }
        }

    //function to send sms when an order  gets completed
    function mysite_completed($order_id)
        {
            $order = new WC_Order( $order_id );
            $orderdetail=$order-> order_custom_fields;
           // echo "<pre>";print_r($orderdetail);exit;
            $delievery_date=$orderdetail['_date_shipped'][0] ;
            $delievery_date=date('d/m/Y',$delievery_date);          
            $frstname=ucfirst($order->shipping_first_name);
            $lstname=ucfirst($order->shipping_last_name);
            $fullname=$frstname." ".$lstname;
            $bilphnno=$orderdetail[_billing_phone];
            $ordertotal=$orderdetail[_order_total];
            $ordertotal=$ordertotal[0];
            $ordertotal=intval($ordertotal);
            $bilphnno= $bilphnno[0];
            //$bilphnno="+91".$bilphnno;
            $orderid=str_replace('#','',$order_id);
            $tracking_provider = get_post_meta( $orderid, '_tracking_provider', true );
            $tracking_number   = get_post_meta( $orderid, '_tracking_number', true );
		$this->MSG=urlencode("Hi ".$fullname.", our shipping provider has confirmed the delivery of your order. Please get in touch with us if you have any issue. Hope you enjoyed shopping at phiverivers.com."); 
             $this->url = 'http://103.247.98.91/API/SendMsg.aspx?uname=20140123&pass=BNCL7V.&send=PRCARE&dest='.$bilphnno.'&msg='.$this->MSG;
             $this->from='01126941196';
            $post_data = array
                (
                    'From' => $this->from,
                    'To' => $bilphnno,
                    'Body' => "Hi ".$fullname.", our shipping provider has confirmed the delivery of your order. Please get in touch with us if you have any issue. Hope you enjoyed shopping at phiverivers.com.",

                );
            $ch = curl_init();
            curl_setopt($ch , CURLOPT_VERBOSE, 1);
            curl_setopt($ch , CURLOPT_URL, $this->url);
            curl_setopt($ch , CURLOPT_POST, 1);
            curl_setopt($ch , CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch , CURLOPT_FAILONERROR, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($post_data));
            $http_result = curl_exec($ch);
            $error = curl_error($ch);
            $http_code = curl_getinfo($ch ,CURLINFO_HTTP_CODE);
            curl_close($ch);
        }

    //function to send sms when  an order get refunded
    function mysite_refunded($order_id)
        {
            $order = new WC_Order( $order_id );
            $orderdetail=$order-> order_custom_fields;
             $frstname=ucfirst($order->shipping_first_name);
            $lstname=ucfirst($order->shipping_last_name);
            $fullname=$frstname." ".$lstname;
            $bilphnno=$orderdetail[_billing_phone];
            $ordertotal=$orderdetail[_order_total];
            $ordertotal=$ordertotal[0];
            $ordertotal=intval($ordertotal);
            $bilphnno= $bilphnno[0];
            //$bilphnno="+91".$bilphnno;
            $orderid=str_replace('#','',$order_id);
	    $this->MSG=urlencode("Hi ".$fullname.", the refund request for the order ".$orderid." has been approved. A coupon worth your order amount Rs.".$ordertotal." has been sent to your email. Please check your email for more details.");
             $this->url = 'http://103.247.98.91/API/SendMsg.aspx?uname=20140123&pass=BNCL7V.&send=PRCARE&dest='.$bilphnno.'&msg='.$this->MSG;
             $this->from='01126941196';
            $post_data = array
                (
                    'From' => $this->from,
                    'To' => $bilphnno,
                    'Body' =>"Hi ".$fullname.", the refund for the payment of order with order ID ".$orderid." is in process. Please contact your bank for the same.",
                );
            $ch = curl_init();
            curl_setopt($ch , CURLOPT_VERBOSE, 1);
            curl_setopt($ch , CURLOPT_URL, $this->url);
            curl_setopt($ch , CURLOPT_POST, 1);
            curl_setopt($ch , CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch , CURLOPT_FAILONERROR, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($post_data));
            $http_result = curl_exec($ch);
            $error = curl_error($ch);
            $http_code = curl_getinfo($ch ,CURLINFO_HTTP_CODE);
            curl_close($ch);
        }

    //function to send sms when  an order gets cancelled
    function mysite_cancelled($order_id)
        {   echo $order_id;
            $order = new WC_Order( $order_id );
            $orderdetail=$order-> order_custom_fields;
            $frstname=ucfirst($order->shipping_first_name);
            $lstname=ucfirst($order->shipping_last_name);
            $fullname=$frstname." ".$lstname;
            $bilphnno=$orderdetail[_billing_phone];
            $ordertotal=$orderdetail[_order_total];
            $ordertotal=$ordertotal[0];
            $orderid=str_replace('#','',$order_id);
            $ordertotal=intval($ordertotal);
            $bilphnno= $bilphnno[0];
            //$bilphnno="+91".$bilphnno;
           //echo "after". $orderid=str_replace('#','',$order_id);exit;
		$this->MSG=urlencode("Hi ".$fullname.", your order with order ID ".$orderid." has been cancelled. Please check your email for more details.");
             $this->url = 'http://103.247.98.91/API/SendMsg.aspx?uname=20140123&pass=BNCL7V.&send=PRCARE&dest='.$bilphnno.'&msg='.$this->MSG;
             $this->from='01126941196';
            $post_data = array
                (
                    'From' => $this->from,
                    'To' => $bilphnno,
                    'Body' =>"Hi ".$fullname.", your order with order ID ".$orderid." has been cancelled. Please check your email for more details.",
                );
            $ch = curl_init();
            curl_setopt($ch , CURLOPT_VERBOSE, 1);
            curl_setopt($ch , CURLOPT_URL, $this->url);
            curl_setopt($ch , CURLOPT_POST, 1);
            curl_setopt($ch , CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch , CURLOPT_FAILONERROR, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($post_data));
            $http_result = curl_exec($ch);
            $error = curl_error($ch);
            $http_code = curl_getinfo($ch ,CURLINFO_HTTP_CODE);
            curl_close($ch);
        }

    //function to send sms when payment of an order has been made
    function mysite_payment_complete($order_id)
        {  // echo $order_id;exit;
            $order = new WC_Order( $order_id );
            $orderdetail=$order-> order_custom_fields;
            $frstname=ucfirst($order->shipping_first_name);
            $lstname=ucfirst($order->shipping_last_name);
            $fullname=$frstname." ".$lstname;
            $bilphnno=$orderdetail[_billing_phone];
            $ordertotal=$orderdetail[_order_total];
            $ordertotal=$ordertotal[0];
            $ordertotal=intval($ordertotal);
            $bilphnno= $bilphnno[0];
            //$bilphnno="+91".$bilphnno;
            $orderid=str_replace('#','',$order_id);
            $payment_type=get_post_meta($order->id ,'_payment_method',true);
		$this->MSG=urlencode("Thank you for placing order ".$orderid." at phiverivers.com. You will be informed once we ship the product. View your email for more details.");
             $this->url = 'http://103.247.98.91/API/SendMsg.aspx?uname=20140123&pass=BNCL7V.&send=PRCARE&dest='.$bilphnno.'&msg='.$this->MSG;
             $this->from='01126941196';
                      //to buyer

                    $post_data = array
                        (
                            'From' => $this->from,
                            'To' => $bilphnno,
                            'Body' =>"Thank you for placing order ".$orderid." at phiverivers.com. You will be informed once we ship the product. View your email for more details.",
                        );
                    $ch = curl_init();
                    curl_setopt($ch , CURLOPT_VERBOSE, 1);
                    curl_setopt($ch , CURLOPT_URL, $this->url);
                    curl_setopt($ch , CURLOPT_POST, 1);
                    curl_setopt($ch , CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch , CURLOPT_FAILONERROR, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                    curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($post_data));
                    $http_result = curl_exec($ch);
                    $error = curl_error($ch);
                    $http_code = curl_getinfo($ch ,CURLINFO_HTTP_CODE);
                    curl_close($ch);

        }
//function to send the confirmation code
    function resend_cod_code($order_id)
        {   
            $order = new WC_Order($order_id);
            $orderdetail=$order-> order_custom_fields;
            $frstname=ucfirst($order->shipping_first_name);
            $lstname=ucfirst($order->shipping_last_name);
            $fullname=$frstname." ".$lstname;
            $bilphnno=$orderdetail[_billing_phone];
            $ordertotal=$orderdetail[_order_total];
            $ordertotal=$ordertotal[0];
            $ordertotal=intval($ordertotal);
            $bilphnno= $bilphnno[0];
            //$bilphnno="+91".$bilphnno;
            $orderid=str_replace('#','',$order_id);
            $meta_key='_order_confirmation_code';
            $temp=get_post_meta($order->id ,'_order_confirmation_code',true);
	    $this->MSG=urlencode("Hi ".$fullname.", your order confirmation code is ".$temp." . If not initiated by you then please ignore this message.");
             $this->url = 'http://103.247.98.91/API/SendMsg.aspx?uname=20140123&pass=BNCL7V.&send=PRCARE&dest='.$bilphnno.'&msg='.$this->MSG;
             $this->from='01126941196';
            $post_cod_data = array
                (
                    'From' => $this->from,
                    'To' => $bilphnno,
                    'Body' => "Hi ".$fullname.", your order confirmation code is ".$temp." . If not initiated by you then please ignore this message.",
                );
            $ch1 = curl_init();
            curl_setopt($ch1 , CURLOPT_VERBOSE, 1);
            curl_setopt($ch1, CURLOPT_URL, $this->url);
            curl_setopt($ch1 , CURLOPT_POST, 1);
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch1 , CURLOPT_FAILONERROR, 0);
            curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch1, CURLOPT_POSTFIELDS,http_build_query($post_cod_data));
            $http_result = curl_exec($ch1);
            $error = curl_error($ch1);
            $http_code = curl_getinfo($ch1 ,CURLINFO_HTTP_CODE);
            curl_close($ch1);
        }
//function for sending sms for shipment of order
function mysite_shipment_sms($post_id)
{
if($_POST['wc_order_action']== 'send_email_order_shipped')
{
    if ( isset( $_POST['tracking_number'] ) )
    {
        if($_POST['tracking_provider' != ' ']){
            $tracking_provider =$_POST['tracking_provider'];
	}
        else{
            $tracking_provider =$_POST['custom_tracking_provider'];
	}
        $tracking_number=$_POST['tracking_number'];
        $firstname=ucfirst($_POST['_billing_first_name']);
        $lastname=ucfirst($_POST['_billing_last_name']);
        $fullname=$firstname." ".$lastname;
        $order_id=$_POST['post_ID'];
        $order = new WC_Order($order_id);
        $orderdetail=$order-> order_custom_fields;
        $ordertotal=$orderdetail['_order_total'];
        $ordertotal=$ordertotal[0];
        $ordertotal=intval($ordertotal);
        $bilphnno=$orderdetail['_billing_phone'];
        $bilphnno= $bilphnno[0];
        //$bilphnno="+91".$bilphnno;
        $orderid=str_replace('#','',$order_id);
        
        $payment_type=get_post_meta($order->id ,'_payment_method',true);
     
        if($payment_type == 'cod')
        {
        $this->MSG=urlencode("Hi, your order ".$orderid." has been shipped via ".$tracking_provider.". Your tracking number is ".$tracking_number.". Please have Rs. ".$ordertotal." ready to pay for the order. View your email for more details.");
        $this->url = 'http://103.247.98.91/API/SendMsg.aspx?uname=20140123&pass=BNCL7V.&send=PRCARE&dest='.$bilphnno.'&msg='.$this->MSG;
        $this->from='09266673161';
        $post_cod_data = array
        (
        'From' => $this->from,
        'To' => $bilphnno,
        'Body' =>"Hi ".$fullname.", your order ".$orderid." has been shipped via ".$tracking_provider.". Your tracking number is ".$tracking_number.". Please have Rs. ".$ordertotal." ready to pay for the order. View your email for more details.",
        );
        }
        else
        {
		$this->MSG=urlencode("Hi ".$fullname.", your order ".$orderid." has been shipped via ".$tracking_provider.". Your tracking number is ".$tracking_number.". View email for more details.");
        $this->url = 'http://103.247.98.91/API/SendMsg.aspx?uname=20140123&pass=BNCL7V.&send=PRCARE&dest='.$bilphnno.'&msg='.$this->MSG;
        $this->from='09266673161'; 
             $post_cod_data = array
            (
            'From' => $this->from,
            'To' => $bilphnno,
            'Body' =>"Hi ".$fullname.", your order ".$orderid." has been shipped via ".$tracking_provider.". Your tracking number is ".$tracking_number.". View your email for more details.",
                 );

        }
        $ch1 = curl_init();
        curl_setopt($ch1 , CURLOPT_VERBOSE, 1);
        curl_setopt($ch1, CURLOPT_URL, $this->url);
        curl_setopt($ch1 , CURLOPT_POST, 1);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch1 , CURLOPT_FAILONERROR, 0);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch1, CURLOPT_POSTFIELDS,http_build_query($post_cod_data));
        $http_result = curl_exec($ch1);
        $error = curl_error($ch1);
        $http_code = curl_getinfo($ch1 ,CURLINFO_HTTP_CODE);
        curl_close($ch1);
    }
}
}


}
$sms=new Smsclass;
//handle order cofirmation method call
if($_POST['cod_confirm'] == 'comfirmed')
    {
        if(!class_exists('WC_Order'))
            {
                require($_SERVER['DOCUMENT_ROOT'].'/eshopbox/wp-load.php');
            }
        $order_id=$_POST['order_id'];
        $order = new WC_Order($order_id);
        $order-> update_status('processing');
    }
    
//handle cod confirmation code resending
if($_POST['cod_resend'] == 'resent')
    { 
        if(!class_exists('WC_Order'))
            {
                require($_SERVER['DOCUMENT_ROOT'].'/eshopbox/wp-load.php');
            }
        $order_id=$_POST['order_id']; 
        $sms->resend_cod_code($order_id);
    }
    
?>
