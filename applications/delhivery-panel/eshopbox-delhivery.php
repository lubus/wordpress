<?php
/*
Plugin Name: Delhivery
Plugin URI: http://www.vaibhavign.com
Description: Delhivery integration panel
Version: 2.0
Author: Vaibhav Sharma
Author Email: http://www.vaibhavign.com
*/

/**
 * Copyright (c) `date "+%Y"` Vaibhav Sharma. All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * **********************************************************************
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WC_Delhivery{
    public function __construct(){
        register_activation_hook( __FILE__, array( $this, 'createTables' ));
        add_action('admin_menu', array( &$this, 'eshopbox_delhivery_admin_menu' )); 
       
    }
                            
function eshopbox_delhivery_admin_menu() {
    
    add_menu_page(__('Delhivery','wp-delhivery'), __('Delhivery','wc-delhivery'), 'edit_posts', 'eshopbox-delhivery', array( &$this, 'eshopbox_delhivery_page' ) );
    add_submenu_page( 'eshopbox-delhivery', 'Config', 'Config', 'edit_posts', 'delhivery_config', array( &$this, 'delhivery_config_page' ) );
     add_submenu_page( 'eshopbox-delhivery', 'Upload AWB', 'Upload AWB', 'edit_posts', 'delhivery_uploadawb', array( &$this, 'delhivery_upload_awb' ) );
}
 
// config page for the delhivery panel
function delhivery_config_page(){
  		if ( !current_user_can( 'manage_eshopbox' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.', 'wp-delhivery' ) );
		}
		// Load needed WP resources for media uploader
		wp_enqueue_media();

		// Save the field values
		if ( isset( $_POST['delhivery_fields_submitted'] ) && $_POST['delhivery_fields_submitted'] == 'submitted' ) {
			foreach ( $_POST as $key => $value ) {
			
				  if ( get_option( $key ) != $value ) {
					  update_option( $key, $value );
				  }
				  else {
					  add_option( $key, $value, '', 'no' );
				  }
				}
			
		}
                ?>
   <div class="wrap">
			<div id="icon-options-general" class="icon32">
				<br />
			</div> 
			<h2><?php _e( 'Eshopbox Delhivery panel', 'wp-delhivery' ); ?></h2>
			<?php if ( isset( $_POST['pip_fields_submitted'] ) && $_POST['pip_fields_submitted'] == 'submitted' ) { ?>
			<div id="message" class="updated fade"><p><strong><?php _e( 'Your settings have been saved.', 'wp-delhivery' ); ?></strong></p></div>
			<?php } ?>
			<p><?php _e( 'Change settings for delhivery panel.', 'wp-delhivery' ); ?></p>
			<div id="content">
			  <form method="post" action="" id="pip_settings">
				  <input type="hidden" name="delhivery_fields_submitted" value="submitted">
				  <div id="poststuff">
						<div class="postbox">
							<h3 class="hndle"><?php _e( 'Configuration', 'wp-delhivery' ); ?></h3>
							<div class="inside pip-preview">
							  <table class="form-table">
							    <tr>
    								<th>
    									<label for="eshopbox_delhivery_store_name"><b><?php _e( 'Store name:', 'wp-delhivery' ); ?></b></label>
    								</th>
    								<td>
    									<input type="text" name="delhivery_store_name" class="regular-text" value="<?php echo stripslashes(get_option( 'delhivery_store_name' )); ?>" /><br />
    									<span class="description"><?php
    										echo __( 'Your store name.', 'wp-delhivery' );
    					
    									?></span>
    								</td>
    							</tr>
                    
								</table>
							</div>
						</div>
					</div>
			  <p class="submit">
				<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'wp-delhivery' ); ?>" />
			  </p>
		    </form>
		  </div>
		</div> 
    
    <?php
}

 function eshopbox_delhivery_page(){
                 global $wpdb;
    //  echo "<input type='button' name='batchno' value='Enter Batch number' id='batchno' />"; 
    //  echo "<input type='button' name='awbnum' value='Upload Files of AWB numbers' id='awbnum' />"; 
      ?>
                 
             <div class="wrap">
			<div id="icon-options-general" class="icon32">
				<br />
			</div> 
			<h2><?php _e( 'Eshopbox Delhivery panel', 'wp-delhivery' ); ?></h2>
			<?php if ( isset( $_POST['pip_fields_submitted'] ) && $_POST['pip_fields_submitted'] == 'submitted' ) { ?>
			<div id="message" class="updated fade"><p><strong><?php _e( 'Your settings have been saved.', 'wp-delhivery' ); ?></strong></p></div>
			<?php } ?>
			<p><?php _e( 'Change settings for Delhivery panel.', 'wp-delhivery' ); ?></p>
			<div id="content">
			  <form method="post" name="batchform" id="batchform" action="" >
				  <input type="hidden" name="delhivery_fields_submitted" value="submitted">
				  <div id="poststuff">
						<div class="postbox">
							<h3 class="hndle"><?php _e( 'Download softdata with manifest id', 'wp-delhivery' ); ?></h3>
							<div class="inside pip-preview">
							  <table class="form-table">
							    <tr>
    								<th>
    									<label for="eshopbox_bluedart_store_name"><b><?php _e( 'Manifest id:', 'wp-delhivery' ); ?></b></label>
    								</th>
    								<td>
    									
    									          <input type="text" name="batch" id="batch" />
            <input type="radio" name="rad" value="cod" /> COD
            <input type="radio" name="rad" value="payu_in" /> Prepaid
            <input type="radio" name="rad" value="both" /> both
    								</td>
    							</tr>
    							
    				<tr>
    								
    								<td>
    						 <p class="submit">
				 <input type="submit" name="subbatch" value="submit" />
                                  <input type="hidden" name="post1" value="post" />
			  </p>			
    									         
    								</td>
    							</tr>		
                                                                                 
                        
								</table>
							</div>
						</div>
					</div>
			 
		    </form>
		  </div>
		</div> 
        <!--
               <div class="wrap">

		
			<div id="content">
			  <form method="post" name="csvform" id="csvform" action="" enctype="multipart/form-data" >
				  <input type="hidden" name="delhivery_fields_submitted" value="submitted">
				  <div id="poststuff">
						<div class="postbox">
							<h3 class="hndle"><?php _e( 'Upload AWB number .xls/.xlsx/.txt file', 'wp-delhivery' ); ?></h3>
							<div class="inside pip-preview">
							  <table class="form-table">
							    <tr>
    								<th>
    									<label for="eshopbox_bluedart_store_name"><b><?php _e( 'Upload file:', 'wp-delhivery' ); ?></b></label> 
    								</th>
    								<td>
    									
    									          <input type="file" name="csvtext" />
    								</td>
    							</tr>
    							
    				<tr>
    								
    								<td>
    						 <p class="submit">
		        <input type="submit" name="subbatch" value="submit" />
            <input type="hidden" name="postcsv" value="post" />
			  </p>			
    									         
    								</td>
    							</tr>		
                                                                                 
                        
								</table>
							</div>
						</div>
					</div>
			 
		    </form>
		  </div>
		</div>  
        -->
<!--
 <div class="wrap">

		
			<div id="content">
			  <form method="post" name="ordercsvform" id="ordercsvform" action="" enctype="multipart/form-data" >
				  <input type="hidden" name="bluedart_ordercsvform_submitted" value="submitted">
				  <div id="poststuff">
						<div class="postbox">
							<h3 class="hndle"><?php _e( 'Upload Order id .xls/.xlsx/.txt file', 'wp-delhivery' ); ?></h3>
							<div class="inside pip-preview">
							  <table class="form-table">
							    <tr>
    								<th>
    									<label for="eshopbox_bluedart_store_name"><b><?php _e( 'Upload file:', 'wp-delhivery' ); ?></b></label> 
    								</th>
    								<td>
    									
    									          <input type="file" name="ordercsvtext" />
    								</td>
    							</tr>
    							
    				<tr>
    								
    								<td>
    						 <p class="submit">
		        <input type="submit" name="subbatch" value="submit" />
            <input type="hidden" name="orderpostcsv" value="post" />
			  </p>			
    									         
    								</td>
    							</tr>		
                                                                                 
                        
								</table>
							</div>
						</div>
					</div>
			 
		    </form>
		  </div>
		</div> 
-->
                 
        <?php         
     /*
        echo '<form name="batchform" id="batchform" method="post">
          <input type="text" name="batch" id="batch" />
            <input type="radio" name="rad" value="cod" /> COD
            <input type="radio" name="rad" value="payu_in" /> Prepaid
            <input type="radio" name="rad" value="both" /> both
            <input type="submit" name="subbatch" value="submit" />
            <input type="hidden" name="post1" value="post" />
        </form>';   
      
            echo '<form name="csvform" id="csvform" method="post" enctype="multipart/form-data">
                <input type="file" name="csvtext" />
            <input type="submit" name="subbatch" value="submit" />
            <input type="hidden" name="postcsv" value="post" />
        </form>'; 
      * */
     

     if($_POST['postcsv']=='post'){
        // echo '<pre>'; print_r($_FILES);
        // print_r($_POST);
	// for text files
	if($_FILES['csvtext']['type']=='text/plain'){ 
		$myFile = $_FILES['csvtext']['tmp_name'];
		$handle = @fopen($myFile, "r");
if ($handle) {
    while (($buffer = fgets($handle, 4096)) !== false) {
    $buffer = trim($buffer);
    if($buffer!=''){
$querystr = "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_tracking_number' and meta_value='$buffer'";
$postid = $wpdb->get_var($querystr);
if($postid !=''){
$orderIds[] = $postid;
}
    }
    }
    $this->readArrayExportxls($orderIds);
}


	} else { // for excel file
//	if($_FILES['csvtext']['type']=='application/vnd.ms-excel'){
$myFile = $_FILES['csvtext']['tmp_name'];
//set_include_path(get_include_path() . PATH_SEPARATOR . 'class/');
include 'class/PHPExcel/IOFactory.php';

//$myFile = $myFile;
//echo get_include_path() . PATH_SEPARATOR . 'class/';
	try {

	$objPHPExcel = PHPExcel_IOFactory::load($myFile);
//echo 'test123';
} catch(Exception $e) {

	die('Error loading file "'.pathinfo($myFile,PATHINFO_BASENAME).'": '.$e->getMessage());

}
//echo 'test99';
//echo '<pre>';
//print_r($objPHPExcel);


$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);



foreach($sheetData as $sheetdata){

	$awbnumber = trim($sheetdata['A']);
$querystr = "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_tracking_number' and meta_value='$awbnumber'";
$postid = $wpdb->get_var($querystr);
if($postid !=''){
$orderIds[] = $postid;
}
//print_r($sheetdata);

}
$this->readArrayExportxls($orderIds);
//print_r($xyz);

//	}
	//echo '<pre>';
	//print_r($_FILES);
         
     } }
     
     
     // for order id
     
         if($_POST['orderpostcsv']=='post'){
        // echo '<pre>'; print_r($_FILES);
        // print_r($_POST);
	// for text files
	if($_FILES['ordercsvtext']['type']=='text/plain'){ 
		$myFile = $_FILES['ordercsvtext']['tmp_name'];
		$handle = @fopen($myFile, "r");
if ($handle) {
    while (($buffer = fgets($handle, 4096)) !== false) {
    $buffer = trim($buffer);
    if($buffer!=''){

$orderIds[] = $buffer;
    }
    }
    $this->readArrayExportxls($orderIds);
}


	} else { // for excel file
//	if($_FILES['csvtext']['type']=='application/vnd.ms-excel'){
$myFile = $_FILES['ordercsvtext']['tmp_name'];
//set_include_path(get_include_path() . PATH_SEPARATOR . 'class/');
include 'class/PHPExcel/IOFactory.php';

//$myFile = $myFile;
//echo get_include_path() . PATH_SEPARATOR . 'class/';
	try {

	$objPHPExcel = PHPExcel_IOFactory::load($myFile);
//echo 'test123';
} catch(Exception $e) {

	die('Error loading file "'.pathinfo($myFile,PATHINFO_BASENAME).'": '.$e->getMessage());

}
//echo 'test99';
//echo '<pre>';
//print_r($objPHPExcel);


$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);



foreach($sheetData as $sheetdata){
	$orderno = trim($sheetdata['A']);
        $orderIds[] = $orderno;
//print_r($sheetdata);
}
$this->readArrayExportxls($orderIds);
//print_r($xyz);

//	}
	//echo '<pre>';
	//print_r($_FILES);
         
     } }
     
     
     
     
      if($_POST['post1']=='post'){
        //  print_r($_POST);
          $manifestId = $_POST['batch'];
         // echo "SELECT * FROM ".$wpdb->prefix."manifest WHERE `id` IN  ($manifestId)";
        $manifestDetails = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."manifest WHERE `id` IN  ($manifestId)"); 
        foreach($manifestDetails as $key=>$val){
            $o[] = $val->orderid;
            
        }
        //echo '<pre>';
        //print_r($manifestDetails);
             $getOrderId = implode(',',$o);
           // $getOrderId = $manifestDetails[0]->orderid;
            $individualOrder = explode(',',$getOrderId);
            $finalarray[]=array("Airwaybill","Type","Reference Number","Sender / Store name","attention","address1","address2","address3","pincode","tel number","mobile number","Prod/SKU code","contents"
                ,"weight","Declared Value","Collectable Value","Vendor Code","Shipper Name","Return Address1","Return Address2","Return Address3","Return Pin","Length ( Cms )","Bredth ( Cms )","Height ( Cms )","Pieces","Area_customer_code","Handover Date","Handover Time"
                );
            foreach($individualOrder as $key=>$val){
                $orderIds[] = $val;
                
            }
            $mdates = $manifestDetails[0]->dates;
            $this->readArrayExportxls($orderIds,$mdates);
            exit;
        }
  
 }
 
 
 function readArrayExportxls($orderIds,$manidetail=""){
      global $wpdb,$eshopbox;
                              $finalarray[]=array("waybill","order no","Consignee Name","city","state","country","Address","pincode","phone","mobile"
                ,"Weight","payment mode","package amount","cod amount","product to be shipped","shipping client","Length ( Cms )","Bredth ( Cms )","Height ( Cms )"
                );
                
            foreach($orderIds as $key=>$val){
                 $theorder = new WC_Order($val);
                  if($theorder->id>0){
              //   echo '<pre>';
              //   print_r($theorder);
                 $items = $theorder->get_items();
                 $product_id="";
                 $product_name="";
                 $productWeight="";
                 $quant = "";
                  foreach ( $items as $item ) {
                     // echo '<pre>';
                     // print_r($item);
                                           $p =  get_post_meta($item['product_id']);
                     

                     
                  $product_id .= $p['_sku'][0].',';
    $_product = $theorder->get_product_from_item( $item );
    $product_name .= $item['name'].',';
   // $product_id .= $item['product_id'].',';
    $product_variation_id = $item['variation_id'];
  //  $productWeight += $_product->get_weight()/1000;
     $productWeight += 0.6;
   // $quant +=$item['qty'];
         $quant =1;

}
               //  echo '<pre>';
              //   print_r($theorder);
$shipperName= get_option('shipper_name');
$shipperPin = get_option('return_pincode');
                 $vendorCode = get_option('vendor_code');
                 if($theorder->payment_method=='cod'){
                     $totalCollectible = $theorder->order_total;
                     $custCode = get_option('cod_areacustomer');
                     $payType = "COD";
                 } else {
                     $totalCollectible = 0;
                     $custCode = get_option('prepaid_areacustomer');
                     $payType = "PREPAID";
                 }
                 
                 if($theorder->shipping_address_2==''){
                     $shipAddress2 = '-';
                 } else {
                     $shipAddress2 = $theorder->shipping_address_2;
                 }
                 
                 if(get_option('return_address1')==''){
                     $shipperAddress1 = "-";
                 } else {
                     $shipperAddress1 = get_option('return_address1');
                 }
                 
                 if(get_option('return_address2')==''){
                     $shipperAddress2 = "-";
                 } else {
                     $shipperAddress2 = get_option('return_address2');
                 }
                 
                  if(get_option('return_address3')==''){
                     $shipperAddress3 = "-";
                 } else {
                     $shipperAddress3 = get_option('return_address3');
                 }
                 
   //  $dateTime = explode(' ',date('d-m-Y h:m:s',$manifestDetails[0]->dates));
                 
                if($manidetail==""){
                     $newDate =  date('d-m-Y h:m:s',$theorder->order_custom_fields['_date_shipped'][0]);
                      $dateTime = explode(' ',$newDate);
                 } else {
                 $dateTime = explode(' ',date('d-m-Y h:m:s',$manidetail));
                 }
                 
               if($_POST['rad']=='' || $_POST['rad']=='both'){
                 $finalarray[] = array($theorder->order_custom_fields['_tracking_number'][0],'Phiverevers-'.$theorder->id,$theorder->shipping_first_name.' '.$theorder->shipping_last_name,$theorder->order_custom_fields['_shipping_city'][0],$theorder->order_custom_fields['_shipping_state'][0],'India',$theorder->shipping_address_1,
  $theorder->shipping_postcode,'-',$theorder->billing_phone,$productWeight,$payType, $theorder->order_total,$totalCollectible,
                   substr($product_id,0,-1).'-'.substr($product_name,0,-1),  $shipperName,"20","20","5");
                } else if($theorder->payment_method==$_POST['rad']){
                          $finalarray[] = array($theorder->order_custom_fields['_tracking_number'][0],'Phiverevers-'.$theorder->id,$theorder->shipping_first_name.' '.$theorder->shipping_last_name,$theorder->order_custom_fields['_shipping_city'][0],$theorder->order_custom_fields['_shipping_state'][0],'India',$theorder->shipping_address_1,
  $theorder->shipping_postcode,'-',$theorder->billing_phone,$productWeight,$payType, $theorder->order_total,$totalCollectible,
                   substr($product_id,0,-1).'-'.substr($product_name,0,-1), $shipperName, "20","20","5"); 
                }
                
                
                
                  }   
                     
            }
    
//echo '<pre>';
//print_r($finalarray);
      $outputBuffer = fopen("/tmp/somefileephive.csv", "w");      
        //  $outputBuffer = fopen("php://output", 'w');
	foreach($finalarray as $val) {
	    fputcsv($outputBuffer, $val);
	}
	fclose($outputBuffer);    
    // echo 'testa'; exit;   
            include_once('class/PHPExcel/IOFactory.php');

//$myFile = $myFile;
//echo get_include_path() . PATH_SEPARATOR . 'class/';
$objReader = PHPExcel_IOFactory::createReader('CSV'); 
$objPHPExcel = $objReader->load('/tmp/somefileephive.csv'); 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('/tmp/MyExcelFilessssphive.xls');
ob_end_clean(); // Added by me
flush();
header('Content-Description: File Transfer');
//header("Content-type: application/octet-stream");
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename=softdata_delhivery_'.date('d-m-Y').'.xls');
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize('/tmp/MyExcelFilessssphive.xls'));
ob_end_clean(); 
//ob_clean();
flush();
//ob_end_clean(); // Added by me
//ob_start(); // Added by me 
readfile('/tmp/MyExcelFilessssphive.xls');

unlink('/tmp/MyExcelFilessssphive.xls');
exit; 
//   header("Content-type: application/octet-stream");
 //   header("Content-Disposition: filename='/tmp/MyExcelFilesss.xls'");

 /*
            ob_clean();     
header('Content-Type: application/vnd.ms-excel;');                 // This should work for IE & Opera
header("Content-type: application/x-msexcel");     
header("Content-Disposition: attachment; filename=MyExcelFile.xls");
header("Pragma: no-cache");
header("Expires: 0");
            
    $outputBuffer = fopen("php://output", 'w');
	foreach($finalarray as $val) {
	    fputcsv($outputBuffer, $val);
	}
	fclose($outputBuffer);        
      exit; 
  * 
  */ 
 }
 
 function delhivery_upload_awb(){
     global $wpdb;
       		if ( !current_user_can( 'manage_eshopbox' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.', 'wp-delhivery' ) );
		}
    ?>
               <div class="wrap">

		
			<div id="content">
			  <form method="post" name="uploadawb" id="csvform" action="" enctype="multipart/form-data" >
				 
				  <div id="poststuff">
						<div class="postbox">
							<h3 class="hndle"><?php _e( 'Upload AWB number .xls/.xlsxfile', 'wp-delhivery' ); ?></h3>
							<div class="inside pip-preview">
							  <table class="form-table">
							    <tr>
    								<th>
    									<label for="eshopbox_bluedart_store_name"><b><?php _e( 'Upload file:', 'wp-delhivery' ); ?></b></label> 
    								</th>
    								<td>
    									
    									          <input type="file" name="awbnum" />
                                                                                    <input type="radio" name="rad" value="cod" /> COD
            <input type="radio" name="rad" value="prepaid" /> Prepaid
    								</td>
    							</tr>
    							
    				<tr>
    								
    								<td>
    						 <p class="submit">
		        <input type="submit" name="subbatchs" value="submit" />
            <input type="hidden" name="postawbnum" value="post" />
			  </p>			
    									         
    								</td>
    							</tr>		
                                                                                 
                        
								</table>
							</div>
						</div>
					</div>
			 
		    </form>
		  </div>
		</div>  
<?php

if($_POST['postawbnum']=='post'){
if(!isset($_POST['rad'])){
    die('Select cod/prepaid type of AWB number');
}
    
    $myFile = $_FILES['awbnum']['tmp_name'];
//set_include_path(get_include_path() . PATH_SEPARATOR . 'class/');
include 'class/PHPExcel/IOFactory.php';

//$myFile = $myFile;
//echo get_include_path() . PATH_SEPARATOR . 'class/';
	try {

	$objPHPExcel = PHPExcel_IOFactory::load($myFile);
//echo 'test123';
} catch(Exception $e) {

	die('Error loading file "'.pathinfo($myFile,PATHINFO_BASENAME).'": '.$e->getMessage());

}
//echo 'test99';
//echo '<pre>';
//print_r($objPHPExcel);


$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

if($_POST['rad']=='cod'){
    $awbTablename = $wpdb->prefix ."delivery_codawb"; 
} else {
    $awbTablename = $wpdb->prefix ."delivery_prepaidawb" ;
}

$ik=0;
foreach($sheetData as $sheetdata){
    if($ik>0){
    $wpdb->insert(
	$awbTablename,
	array(
		'awbnumber' => trim($sheetdata['A']),
		'assigned' => '0',
                'orderid' => ''
	)
    );
    }
    $ik++;
    
	//$orderno = trim($sheetdata['A']);
      //  $ordernox = trim($sheetdata['B']);
      //  $orderIds[] = $orderno;
        
        
//print_r($sheetdata);
}

//echo '<pre>';
//print_r($orderIds);
//print_r($ordernox);

}


   
 }
 
    public function createTables(){
        global $wpdb;
        $table_name = $wpdb->prefix ."delivery_codawb"; 
        
       $sql = "CREATE TABLE IF NOT EXISTS $table_name (
  `awbnumber` varchar(255) NOT NULL,
  `assigned` int(11) NOT NULL,
  `orderid` varchar(200) NOT NULL,
  `locks` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

        require_once(ABSPATH . 'eshop-admin/upgrade-functions.php');
        dbDelta($sql);  
            $table_name = $wpdb->prefix . "delivery_prepaidawb"; 
            $sql = "CREATE TABLE IF NOT EXISTS $table_name (
  `awbnumber` varchar(255) NOT NULL,
  `assigned` int(11) NOT NULL,
  `orderid` varchar(200) NOT NULL,
  `locks` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
            
        require_once(ABSPATH . 'eshop-admin/upgrade-functions.php');
        dbDelta($sql);      
        
        
    }
       


/**
     * Get the plugin url.
     *
     * @access public
     * @return string
     */
    public function plugin_url() {
        if ( $this->plugin_url ) return $this->plugin_url;
        return $this->plugin_url = untrailingslashit( plugins_url( '/', __FILE__ ) );
    }

    /**
     * Get the plugin path.
     *
     * @access public
     * @return string
     */
    public function plugin_path() {
        if ( $this->plugin_path ) return $this->plugin_path;
        return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
    }
  

}
new WC_Delhivery();
