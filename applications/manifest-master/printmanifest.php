<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/eshopbox/wp-load.php' );
global $eshopbox;
global $wpdb;
$pluginPath =  untrailingslashit( plugins_url( '/', __FILE__ ) );
/*
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
 * 
 */
if(!isset($_GET['history'])){
if(count($_POST['check'])>0){

    
     // insert into manifest table
$wpdb->insert( 
    $wpdb->prefix.'manifest', 
    array( 
            'orderid' => implode(',',$_POST['check']),
            'dates' => time(),
            'provider' => $_POST['shipprovider'],
            'paytype'  => $_POST['paymethod']

    )
 );
 $manifestId = $wpdb->insert_id;     
 $manifestDate = date('d-m-Y');
 }
} else {
     $manifestId = $_GET['manid'];     
     $manifestDate = date('d-m-Y',$_GET['mandate']);
  // do nothing  
}
$displayCustAreaCode = false;
if($_POST['shipprovider']=='blue-dart'){
    if($_POST['paymethod']!=''){
    $displayCustAreaCode = true;
        if($_POST['paymethod']=='cod'){
           if(stripslashes(get_option( 'cod_areacustomer' ))!=''){
               $areaCustomerCode = stripslashes(get_option( 'cod_areacustomer' ));
           }     
        } else {
           if(stripslashes(get_option( 'prepaid_areacustomer' ))!=''){
               $areaCustomerCode = stripslashes(get_option( 'prepaid_areacustomer' ));
           }   
        }
    
    }
    
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Manifest</title>
    <style>
    body{
            font-family: Arial;
            color: #000;
    }
    td{
            vertical-align: left;
            padding: 0px 0px;

    }
    th{
    text-align:left;
    font-weight: normal;
    padding: 3px 0px;
    }
    </style>
</head>
    <script type="text/javascript" src="<?php echo $pluginPath;  ?>/assets/jquery-1.3.2.min.js" ></script>  
    <script type="text/javascript" src="<?php echo $pluginPath;  ?>/assets/jquery-barcode.js" ></script>  
    <script type="text/javascript">
  //  window.opener.location='/eshopbox/wp-admin/edit.php?post_type=shop_order';
      function generateBarcode(a,b,c){
        var value = $("#"+a).val();
        var btype = $("input[name=btype]:checked").val();
        var renderer = $("input[name=renderer]:checked").val();
        
		var quietZone = false;
        if ($("#quietzone").is(':checked') || $("#quietzone").attr('checked')){
          quietZone = true;
        }
		
        var settings = {
          output:renderer,
          bgColor: $("#bgColor").val(),
          color: $("#color").val(),
          barWidth: $("#barWidth").val(),
          barHeight: $("#barHeight").val(),
          moduleSize: $("#moduleSize").val(),
          posX: $("#posX").val(),
          posY: $("#posY").val(),
          addQuietZone: $("#quietZoneSize").val()
        };
        if ($("#rectangular").is(':checked') || $("#rectangular").attr('checked')){
          value = {code:value, rect: true};
        }
        if (renderer == 'canvas'){
          clearCanvas();
          $("#"+b).hide();
          $("#"+c).show().barcode(value, btype, settings);
        } else {
          $("#"+c).hide();
          $("#"+b).html("").show().barcode(value, btype, settings);
        }
      }
          
      function showConfig1D(){
        $('.config .barcode1D').show();
        $('.config .barcode2D').hide();
      }
      
      function showConfig2D(){
        $('.config .barcode1D').hide();
        $('.config .barcode2D').show();
      }
      
      function clearCanvas(){
        var canvas = $('#canvasTarget').get(0);
        var ctx = canvas.getContext('2d');
        ctx.lineWidth = 1;
        ctx.lineCap = 'butt';
        ctx.fillStyle = '#FFFFFF';
        ctx.strokeStyle  = '#000000';
        ctx.clearRect (0, 0, canvas.width, canvas.height);
        ctx.strokeRect (0, 0, canvas.width, canvas.height);
      }
      

  
    </script>
<body bgcolor="#ccc">
	<table align="center" width="940" bgcolor="#fff" cellspacing="0" cellpadding="0" style="padding: 20px 30px">
		<tbody>
                    <tr>
                        <td>
                            <table width="100%" cellspacing="0" cellpadding="0" style="padding-bottom:20px; margin-bottom:10px; border-bottom:3px solid #000;">
                                <tbody>
                                    <tr>
								<td width="25%" align="left">
									<!-- table for header logo and left bar -->
									<table width="100%" cellspacing="0" cellpadding="0">
										<tbody>
                                                                                    <tr>
												<td colspan="2" style="font-size:26px; color:#000; text-transform: uppercase;">
													<?php echo bloginfo('name'); ?>
												</td>
											</tr>
											<tr>
												<td colspan="2" style="font-size:15px;">
									<?php $siteURL =  explode('/eshopbox',site_url()); 
                                                                                                            echo $siteURL[0];
                                                                                                        
                                                                                                        ?>
												</td>
											
											</tr>
											
										</tbody>
									</table>
									<!-- table for header logo and left bar -->
								</td>
								<td width="50%" align="center">&nbsp;</td>
								<td width="25%" align="right">
									<!-- table right bar -->
									<table width="100%" cellspacing="0" cellpadding="0">
										<tbody>
                                                                                    <tr>
                                                                                       <td colspan="2" style="font-family:Arial; font-size:24px; color:#000; text-align: right; padding-top: 6px; padding-bottom: 4px;">Manifest
                                                                                           </td>
                                                                                     </tr>
											<tr>
												<td style="font-family:Arial; font-size:11px; color:#000; text-align: right;">
													<b>Manifest Id</b>
												</td style="font-family:Arial; font-size:11px; color:#000; text-align: right;">
												<td  style="font-family:Arial; font-size:11px; color:#000; text-align: right;"><?php echo $manifestId;  ?></td>
											</tr>
											
											<tr>
												<td style="font-family:Arial; font-size:11px; color:#000; text-align: right;"><b>Manifest Date</b>
												</td>
												<td style="font-family:Arial; font-size:11px; color:#000; text-align: right;"><?php echo $manifestDate;  ?></td>
											</tr>
                                                                                    <!--
											<tr>
												<td><b>Total Weight</b>
												</td>
												<td>1</td>
											</tr>
											<tr>
												<td><b>Total pieces</b>
												</td>
												<td>2</td>
											</tr>
                                                                                    -->
										</tbody>
									</table>
									<!-- table right bar -->
								</td>
							</tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
			
			<tr>
				<td>
					<!-- header block -->
					<table width="100%" align="center" cellspacing="0" cellpadding="0" style="padding-top:20px">
						<tbody>
							
                                                        <tr>
								<td width="25%" align="left">
									<!-- table for header logo and left bar -->
									<table width="100%" cellspacing="0" cellpadding="0">
										<tbody>
                                                                                    <tr><td><b> Courier Service</b>
                                                                                            </td>
												<td style="text-transform:capitalize;">
													<?php echo $_POST['shipprovider'];  ?>
												</td>
											</tr>
                                                                                   <?php if($displayCustAreaCode){ ?>
                                                                                    <tr><td><b>Customer Code</b>
                                                                                            </td>
												<td style="text-transform:capitalize;">
													<?php echo $areaCustomerCode;  ?>
												</td>
											</tr>
                                                                                   <?php }  ?>	
										</tbody>
									</table>
									<!-- table for header logo and left bar -->
								</td>
								<td width="50%" align="center">&nbsp;</td>
								<td width="25%" align="right">
									<!-- table right bar -->
									<table width="100%" cellspacing="0" cellpadding="0">
										<tbody>
                                                                                    
											<tr>
												<td><b>Total Shipments</b>
												</td>
												<td><?php echo count($_POST['check']);  ?>
												</td>
											</tr>
                                                                                    <!--
											<tr>
												<td><b>Total Weight</b>
												</td>
												<td>1</td>
											</tr>
											<tr>
												<td><b>Total pieces</b>
												</td>
												<td>2</td>
											</tr>
                                                                                    -->
										</tbody>
									</table>
									<!-- table right bar -->
								</td>
							</tr>
						</tbody>
					</table>
					<!-- header block -->
				</td>
			</tr>
					<tr>
								<td width="100%">
									<!-- product detail table -->
									<table width="100%" cellspacing="0" cellpadding="0" style="padding: 0px 0; border-top:1px solid #000; margin:35px 0;">
										<thead>
											<tr>
												<th style="padding-left: 4px; border-bottom: 1px solid #000;font-size: 12px;text-transform: uppercase;font-family: Arial;color: #000;font-weight: bold;">No.</th>						
												<th style="padding-left: 4px;border-bottom: 1px solid #000;font-size: 12px;text-transform: uppercase;font-family: Arial;color: #000;font-weight: bold;">Order Id
												</th>
												<th style="padding-left: 4px;border-bottom: 1px solid #000;font-size: 12px;text-transform: uppercase;font-family: Arial;color: #000;font-weight: bold;">SKU Code
												</th>
												<th style="padding-left: 4px;border-bottom: 1px solid #000;font-size: 12px;text-transform: uppercase;font-family: Arial;color: #000;font-weight: bold;">Receipient Details
												</th>
												<th style="padding-left: 4px;border-bottom: 1px solid #000;font-size: 12px;text-transform: uppercase;font-family: Arial;color: #000;font-weight: bold;">Wgt
												</th>
												<th style="padding-left: 4px;border-bottom: 1px solid #000;font-size: 12px;text-transform: uppercase;font-family: Arial;color: #000;font-weight: bold;">Mode
												</th>
												<th width="20%" style=" padding-left: 4px;border-bottom: 1px solid #000;font-size: 12px;text-transform: uppercase;font-family: Arial;color: #000;font-weight: bold;">AWB Number
												</th>
											</tr>
		<tbody>									</thead>
<?php
$i = 1;
$k=1;

foreach($_POST['check'] as $key=>$value)
{
$product_id='';
 $order_id=$value;
 $orderobj=new WC_Order($order_id);
 $shippingadd = $orderobj->get_formatted_shipping_address();
 $items = $orderobj->get_items();
// echo"<pre>";print_r($orderobj);
 $shipdetail=get_post_meta($order_id);
 foreach ( $items as $item ) {
     $_product = $orderobj->get_product_from_item( $item );
    // echo '<pre>'; 
    // print_r($item);
    $product_name = $item['name'];
    $product_id .= $item['product_id'].',';
    $product_variation_id = $item['variation_id']; 
    $sku = $wpdb->get_var( $wpdb->prepare( "SELECT meta_value  FROM $wpdb->postmeta WHERE meta_key='_sku' AND post_id='%d' LIMIT 1", $product_id ) ); 
    $we =  $_product->get_weight();
     $we = 0.2;
    if($we==''){
        $we = 'n/a';
    } else {
        $we = $we.' kg';
    }
}
if($orderobj->payment_method=='payu_in'){
    $pMethod = "Noncod";
} else {
     $pMethod = $orderobj->payment_method;
}
?>
        <script>
       //   $(function(){
     //   generateBarcode('barcodeValue<?php echo $i; ?>','barcodeTarget<?php echo $i; ?>','canvasTarget<?php echo $i; ?>');
    //  });

      $(function(){
        $('input[name=btype]').click(function(){
          if ($(this).attr('id') == 'datamatrix') showConfig2D(); else showConfig1D();
        });
        $('input[name=renderer]').click(function(){
          if ($(this).attr('id') == 'canvas') $('#miscCanvas').show(); else $('#miscCanvas').hide();
        });
       generateBarcode('barcodeValue<?php echo $i; ?>','barcodeTarget<?php echo $i; ?>','canvasTarget<?php echo $i; ?>');
      });
  
          </script>
          
          
          									
											<tr>
												<td style="border-bottom:1px dashed #000; padding: 6px; 0px; font-weight:normal; font-size:12px; color:#000; font-family:Arial; Helvetica, sans-serif"><?php echo $k;  ?>
												</td>
												<td style="border-bottom:1px dashed #000; padding: 6px; 0px; font-weight:normal; font-size:12px; color:#000; font-family:Arial; Helvetica, sans-serif"><b><?php echo $order_id; ?></b><br/>
												</td>
												<td style="border-bottom:1px dashed #000; padding: 6px; 0px; font-weight:normal; font-size:12px; color:#000; font-family:Arial; Helvetica, sans-serif"><?php echo $sku; ?>
												</td>
												<td style="border-bottom:1px dashed #000; padding: 6px; 0px; font-weight:normal; font-size:12px; color:#000; font-family:Arial; Helvetica, sans-serif"><?php echo $shippingadd;  ?><br/><?php echo $orderobj->billing_phone; ?>
												</td>
												<td style="border-bottom:1px dashed #000; padding: 6px; 0px; font-weight:normal; font-size:12px; color:#000; font-family:Arial; Helvetica, sans-serif"><?php echo $we; ?>
												</td>
												<td style="border-bottom:1px dashed #000; padding: 6px; 0px; font-weight:normal; font-size:12px; color:#000; font-family:Arial; Helvetica, sans-serif"><?php echo $pMethod; ?>
												</td>
												<td width="20%" style="border-bottom:1px dashed #000; padding: 6px; 0px; font-weight:normal; font-size:12px; color:#000; font-family:Arial; Helvetica, sans-serif">    <input type="hidden" id="barcodeValue<?php echo $i; ?>" value="<?php echo get_post_meta( $order_id, '_tracking_number', true ); ?>">
     
     <input style="display:none;" type="radio" name="btype" id="code128" value="code 128" checked="checked">     
    <input type="hidden" id="barHeight" value="50" size="3">
    <input type="hidden" id="barWidth" value="2" size="3">
    <input type="hidden" id="css" name="renderer" value="css" />
<div id="barcodeTarget<?php echo $i; ?>" class="barcodeTarget"></div>
    <canvas id="canvasTarget<?php echo $i; ?>" width="150" height="150"></canvas>
   
 
												</td>
											</tr>
										

    
    
    

<?php
$i++;
$k++;
} 

?>
    </tbody>
        </table>
</table>

    <input type="hidden" id="barcodeValue" value="123456879">
     
     <input style="display:none;" type="radio" name="btype" id="code128" value="code128" checked="checked">     
    <input type="hidden" id="barHeight" value="50" size="3">
    <input type="hidden" id="barWidth" value="2" size="3">
    <input type="hidden" id="css" name="renderer" value="css" />
  
   
    <td> <div id="barcodeTarget" class="barcodeTarget"></div>
    <canvas id="canvasTarget" width="150" height="150"></canvas> </td>
        <script>

            window.print();
        </script>
    
    </body>
</html> 
