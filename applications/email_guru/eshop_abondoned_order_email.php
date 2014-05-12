<?php
require_once($_SERVER['DOCUMENT_ROOT'] .'/eshopbox/wp-load.php');
global $wpdb,$eshopbox;

//credentials for sendimg sms(these are for testing replace it with your site exotel credentials after testing)
$exotel_sid = "boxbeattechnologies"; // Your Exotel SID
$exotel_token = "8a1b4402d530d035fd298aeac118f52571f5f8dd"; // Your exotel token
$url = "https://".$exotel_sid .":". $exotel_token."@twilix.exotel.in/v1/Accounts/".$exotel_sid."/Sms/send";
$args = array(	
	'post_type'       => 'shop_order',
        'meta_key'        => '_payment_method',
	'meta_value'      => 'payu_in',
	 );
$temppost=get_posts( $args );

foreach($temppost as $postob=>$value)
{
    $email_status='';
    $mailsent='false';
    $order_id=$value->ID;
    $order=new Wc_Order($value->ID);  
    $orderstatus=$order->status;
    //$order_date=$order->order_date;
    $orderdetail=$order-> order_custom_fields;
    $bilphnno=$orderdetail['_billing_phone'];
    $ordertotal=$orderdetail['_order_total'];
    $ordertotal=$ordertotal[0];
    $bilphnno= $bilphnno[0];
    $bilphnno="+91".$bilphnno;
    $frstname=$order->shipping_first_name;
    $lstname=$order->shipping_last_name;
    $fullname=$frstname." ".$lstname;
    $orderid=str_replace('#','',$order_id);
    $order_time= strtotime($order_date);
    $diff=(time()-$order_time)/60 ;
    $diff=intval($diff);
    $post_data = array
                (
                    'From' => '09266673161',
                    'To' => $bilphnno,
                    'Body' =>"Dear ".$fullname.", we noticed that your purchase at Sabhyata wasn't completed.Please check your email and try again. In case the amount has been debited, get in touch with us to confirm the order.",
                );
    if($diff >30)
    {
        if($orderstatus =='pending')
        {
           $email_status=get_post_meta($order_id,'payment_fail_email_status',true);
            if($email_status == '')
            {
                $ch = curl_init();
                curl_setopt($ch , CURLOPT_VERBOSE, 1);
                curl_setopt($ch , CURLOPT_URL, $url);
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
                $_SESSION['abondoned_order']='true';
                $_SESSION['orderid']=$order_id;
                $email=new WC_Emails();
                $mailsent ='true';
            }
            if ($mailsent == 'true')
            {
            add_post_meta($order_id,'payment_fail_email_status','true');
            }
        }
    }
}
?>