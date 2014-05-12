<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/eshopbox/wp-load.php' );

global $wpdb;
if($_POST['action'] == 'checkZipcode'){
	$zipcode = $_POST['zipcode'];
//        $checkara =  stripslashes(get_option( 'eshopbox_cod_aramex_ena' ));
//        if($checkara=='Yes'){
//        $aramex = $wpdb->get_var("SELECT count(*) as  aramexcount FROM ".$wpdb->prefix."cod_aramex WHERE pincode = ".$zipcode);
//        }
//        $checkdtdc =  stripslashes(get_option( 'eshopbox_cod_dtdc_ena' ));
//         if($checkdtdc=='Yes'){
//        $dtdc = $wpdb->get_var("SELECT count(*) as dtdccount FROM ".$wpdb->prefix."cod_dtdc WHERE pincode = '".$zipcode."'");
//         }
//        $checkqua =  stripslashes(get_option( 'eshopbox_cod_quantium_ena' ));
//          if($checkqua=='Yes'){
//	$quantium = $wpdb->get_var("SELECT count(*) as quantiumcount FROM  ".$wpdb->prefix."cod_quantium WHERE pincode = ".$zipcode);
//          }
//                  $checkbluedart =  stripslashes(get_option( 'eshopbox_cod_bluedart_ena' ));
//          if($checkbluedart=='Yes'){
//	$bluedart = $wpdb->get_var("SELECT count(*) as bludartcount FROM  ".$wpdb->prefix."bluedart_codpins WHERE pincode = ".$zipcode);
//          }
      $delhivery = $wpdb->get_var("SELECT count(*) as delhi FROM  ".$wpdb->prefix."delhivery WHERE pin = ".$zipcode);

        if($aramex < 1 && $dtdc < 1 && $quantium < 1 && $bluedart < 1 && $delhivery < 1){ 
		echo 0;exit;
	}else{
		echo 1; exit;
	}
}


?>