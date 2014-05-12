<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );
global $eshopbox;
global $wpdb;
$pluginPath =  untrailingslashit( plugins_url( '/', __FILE__ ) );

if(count($_POST['check'])>0){
     // insert into manifest table
$wpdb->insert( 
    $wpdb->prefix.'manifest', 
    array( 
            'orderid' => implode(',',$_POST['check']),
            'dates' => time(),
            'provider' => $_POST['shipprovider']

    )
 );
 $manifestId = $wpdb->insert_id;     
 }
 
 $i = 1;
$k=1;

//$dispatchDetails




$xml = '<?xml version="1.0" encoding="UTF-8"?>
<data>
<dispatch_date>2013-11-21T10:53:46.789382+00:00</dispatch_date>
<dispatch_id>'.$manifestId.'</dispatch_id>
<pickup_location>
<add>test test test</add>
<city>New Delhi</city>
<country>India</country>
<name>Test</name>
<phone>011-23456245</phone>
<pin>110074</pin>
<state>Delhi</state>
</pickup_location>
<shipments>';
foreach($_POST['check'] as $key=>$value)
{
    $productWeight = '';
                    $product_id="";
                 $product_name="";
                 $productWeight="";
                 $quant = "";
                 $concatProduct="";
 $order_id=$value;
 $orderobj=new WC_Order($order_id);
 $shippingadd = $orderobj->get_formatted_shipping_address();
 $items = $orderobj->get_items();
// echo"<pre>";print_r($orderobj);
 $shipdetail=get_post_meta($order_id);
 if($orderobj->payment_method=='cod'){
     $paymentMethod = "COD";
     $amountPayable =  $orderobj->order_total;
 }
  else{
     $paymentMethod = "PREPAID";
     $amountPayable =  $orderobj->order_total;
 }
 foreach ( $items as $item ) {
    $product_name = $item['name'];
    $product_id .= $item['product_id'].',';
    $product_variation_id = $item['variation_id'];
    $productWeight += 0.2;
    $p =  get_post_meta($item['product_id']);
                     

                     
                  $product_id .= $p['_sku'][0].',';
    $_product = $orderobj->get_product_from_item( $item );
    $product_name .= $item['name'].',';
   // $product_id .= $item['product_id'].',';
    $product_variation_id = $item['variation_id'];
    
    $concatProduct .= $p['_sku'][0].'-'.$item['name'].',';
}
//echo '<pre>';
//print_r($orderobj);

$oDate = explode(" ",$orderobj->order_date);
$abc1 .='<element>
<add>'.$orderobj->shipping_address_1.''.$orderobj->shipping_address_2.'</add>
<billable_weight>'.$productWeight.'</billable_weight>
<city>'.$orderobj->shipping_city.'</city>
<client>XYZ Online</client>
<cod_amount>'.$amountPayable.'</cod_amount>
<country>India</country>
<dimensions>10.00CM x 10.00CM x 10.00CM</dimensions>
<name>'.$orderobj->shipping_first_name.''.$orderobj->shipping_last_name.'</name>
<order>'.$orderobj->id.'</order>
<order_date>'.$oDate[0].'T'.$oDate[1].'+00:00</order_date>
<payment_mode>'.$paymentMethod.'</payment_mode>
<phone>'.$orderobj->billing_phone.'</phone>
<pin>'.$orderobj->shipping_postcode.'</pin>
<products_desc>'.substr($concatProduct,0,-1).'</products_desc>
<return_add />
<return_city />
<return_country />
<return_name />
<return_phone />
<return_pin />
<return_state />
<state>'.$orderobj->shipping_state.'</state>
<supplier>XYZ online</supplier>
<total_amount>'.$orderobj->order_total.'</total_amount>
<volumetric>0.0</volumetric>
<waybill>10112542610</waybill>
<weight>'.$productWeight.' gm</weight>
</element>';

 $i++;
$k++;
} 


$abc2 ='</shipments>
</data>
';



$finalXmlPush =  $xml.$abc1.$abc2;
/*
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://test.delhivery.com/cmu/push/xml/?token=88fea63243e2d9d767fb817f9d3642efec1ca1f5'); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, '3');
        $trackingNum = curl_exec($ch);
        curl_close($ch);
        echo $trackingNum;
        exit;
 * 
 */
        $URL = 'http://test.delhivery.com/cmu/push/xml/?token=88fea63243e2d9d767fb817f9d3642efec1ca1f5';
      $ch = curl_init($URL);
      curl_setopt($ch, CURLOPT_MUTE, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
      curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      echo $output = curl_exec($ch);
      curl_close($ch);
exit;
?>
