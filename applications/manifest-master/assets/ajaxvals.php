<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );

global $wpdb;
if($_POST['action'] == 'checkZipcode'){
	$zipcode = $_POST['zipcode'];
        $checkara =  stripslashes(get_option( 'woocommerce_cod_aramex_ena' ));
        if($checkara=='Yes'){
        $aramex = $wpdb->get_var("SELECT count(*) as  aramexcount FROM ".$wpdb->prefix."cod_aramex WHERE pincode = ".$zipcode);
        }
        $checkdtdc =  stripslashes(get_option( 'woocommerce_cod_dtdc_ena' ));
         if($checkdtdc=='Yes'){
        $dtdc = $wpdb->get_var("SELECT count(*) as dtdccount FROM ".$wpdb->prefix."cod_dtdc WHERE pincode = '".$zipcode."'");
         }
        $checkqua =  stripslashes(get_option( 'woocommerce_cod_quantium_ena' ));
          if($checkqua=='Yes'){
	$quantium = $wpdb->get_var("SELECT count(*) as quantiumcount FROM  ".$wpdb->prefix."cod_quantium WHERE pincode = ".$zipcode);
          }
        if($aramex < 1 && $dtdc < 1 && $quantium < 1){
		echo 0;exit;
	}else{
		echo 1; exit;
	}
}


?>