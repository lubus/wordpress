<?php
/*
Plugin Name: EshopBox print manifest
Plugin URI: http://www.vaibhavign.com
Description: Print manifest
Version: 0.1
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
class WC_Manifest{
    public function __construct(){
        register_activation_hook( __FILE__, array( $this, 'createTables' ));
        add_action('admin_menu', array( &$this, 'eshopbox_manifest_admin_menu' )); 
        add_action('wp_ajax_my_actions', array(&$this, 'my_actions_callback'));
        add_action('wp_ajax_my_orderaction',array(&$this,'my_orderaction_callback'));
        add_filter( 'eshopbox_reports_charts',array(&$this,'getCustomReportTab'));
    }
    
    function my_orderaction_callback(){
        global $wpdb;
        $oI = $_POST["orderId"];
       
        $aramax = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE `meta_value` =  '$oI' and meta_key='_tracking_number'"); 
       // echo '<pre>';
       // print_r($aramax);
        if(count($aramax)>0){
        $theorder = new WC_Order( $aramax[0]->post_id );    
        } else {
        $theorder = new WC_Order( $_POST['orderId'] );
        }
         echo '<tr id="'.$theorder->id.'tr">
             <td style="padding:7px 7px 8px; "><input style="margin:0 0 0 8px;" type="checkbox" name="check[]" value="'.$theorder->id.'" /></td>
             <td style="padding:7px 7px 8px; ">'.$theorder->order_custom_fields['_tracking_number'][0].'</td>
             <td style=" padding:7px 7px 8px; ">'.$theorder->id.'</td>
             <td style="padding:7px 7px 8px; ">'.$theorder->order_date.'</td>
             <td style="padding:7px 7px 8px; ">'.$theorder->shipping_first_name.'</td>
             <td style="padding:7px 7px 8px; ">'.$theorder->order_total.'</td>
             <td style="padding:7px 7px 8px; ">'.$theorder->payment_method.'</td>
             <td style="padding:7px 7px 8px; ">'.$theorder->status.'</td>
                  <td style="padding:7px 7px 8px; "><a class="rem" rel="'.$theorder->id.'">Remove</a></td>
             </tr>';
             exit;
     }

    function my_actions_callback(){
      global $eshopbox,$wpdb;
      $coutn = 0;
      $orderString = '';
      
      $terms1 = get_term_by('slug', 'processing', 'shop_order_status');
      $aramax = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."term_relationships WHERE `term_taxonomy_id` = ".$terms1->term_taxonomy_id);
      foreach($aramax as $key=>$val){  
        $meta_values = get_post_meta($val->object_id);
        if($_POST['paytype']=='all'){
        //  echo '<pre>';
        //  print_r($meta_values);
        $meta_values['_tracking_provider'][0];
        if($meta_values['_tracking_provider'][0]==$_POST['valselected']){
            $orderString .= $val->object_id.'('.$meta_values["_tracking_number"][0].'),';
            $onlyOrders  .= $val->object_id.',';
            $onlyShipments .= $meta_values["_tracking_number"][0].',';
            $coutn++;
        }
        } else{
            if($_POST['paytype']=='cod'){
                     
                       if($meta_values['_payment_method'][0]=='cod'){
        if($meta_values['_tracking_provider'][0]==$_POST['valselected']){
            $orderString .= $val->object_id.'('.$meta_values["_tracking_number"][0].'),';
            $onlyOrders  .= $val->object_id.',';
            $onlyShipments .= $meta_values["_tracking_number"][0].',';
            $coutn++;
        }
                       }
            } else {
                           
                       if($meta_values['_payment_method'][0]!='cod'){
        if($meta_values['_tracking_provider'][0]==$_POST['valselected']){
            $orderString .= $val->object_id.'('.$meta_values["_tracking_number"][0].'),';
            $onlyOrders  .= $val->object_id.',';
            $onlyShipments .= $meta_values["_tracking_number"][0].',';
            $coutn++;
        }
                       }
                
                
            }
            
            
        }
           
      }

     echo $coutn.'$'.substr($orderString,0,-1).'$'.substr($onlyOrders,0,-1).'$'.substr($onlyShipments,0,-1);
     exit;
    }
    
    /**
     * creating dropdown of shipping providers
     * @return string
     */
    
    function getShippingProvider(){
        $shippingArray = array('select'=>'select','blue-dart'=>'bluedart','aramex'=>'aramex','quantium'=>'quantium','indiapost'=>'indiapost','dtdc'=>'dtdc','delhivery'=>'delhivery','self'=>'self');
        $selection = "<select id='selectprovider' name='selectprovider' >";
        foreach($shippingArray as $key=>$val){
            $selection .= "<option value='$key'>$val</option>";
        }
        
        $selection .= '</select>';
        return $selection; 
    }
    
    function paymentType(){
         $payArray = array('all'=>'All','cod'=>'Cod','prepaid'=>'Prepaid');
        $selection = "<select id='paytype' name='paytype' >";
        foreach($payArray as $key=>$val){
            $selection .= "<option value='$key'>$val</option>";
        }
        
        $selection .= '</select>';
        return $selection; 
        
    }
    
       /**
        * 
        * Create admin menu page
        */ 
                            
       function eshopbox_manifest_admin_menu() {
           //  add_menu_page(__('Manifest','wc-checkout-cod-pincodes'), __('Manifest','wc-checkout-cod-pincodes'), 'manage_options', 'eshopbox-manifest', array( &$this, 'eshopbox_manifest_page' ) );
 add_menu_page(__('Manifest','wc-checkout-cod-pincodes'), __('Manifest','wc-checkout-cod-pincodes'), 'manage_options', 'eshopbox-manifest', array( &$this, 'eshopbox_manifest_page' ) );
         //  add_submenu_page('eshopbox', __( 'Manifest', 'wc-checkout-cod-pincodes' ), __( 'Manifest', 'eshopbox-manifest' ), 'manage_eshopbox', 'eshopbox-manifest', array( &$this, 'eshopbox_manifest_page' ) );
	}
        
        /**
         * Create admin manifest page
         * @global type $eshopbox
         */

 	function eshopbox_manifest_page() {
            global $eshopbox;
        	if ( !current_user_can( 'manage_eshopbox' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.', 'eshopbox-pip' ) );
		}
               
                wp_enqueue_media();
                
                                if($_POST['manifest']=='true'){
                    if(count($_POST['check'])>0){
                        
                      foreach($_POST['check'] as $key=>$value)
{
 $order_id=$value;
 $orderobj=new WC_Order($order_id);
 $orderobj->update_status('shipped');
 $orderStrings .= $value.',';
}
  
                }
                    
                }
            ?>
<div class="wrap">
    <div id="icon-options-general" class="icon32"><br /></div>
    <h2><?php _e( 'Create Manifest', 'wc-checkout-cod-pincodes' ); ?></h2>
   <?php if(count($_POST['check'])>0){     
    ?>
    <div id="message" class="updated fade"><p><strong><?php _e( 'Order id '.substr($orderStrings,0,-1).'  have been marked as shipped.', 'wc_shipment_tracking' ); ?></strong></p></div>
    <?php } ?>
    <p><?php // do nothing ?></p>
    
    <div id="content">
        <input type="hidden" name="cod_fields_submitted" value="submitted">
        <div id="poststuff">
            <div class="postbox">
                <h3 class="hndle"><?php _e( 'Manifest', 'eshopbox-pip' ); ?></h3>
                <div class="inside pip-preview">
                    <table class="form-table">
                         <tr>
                            <th>
                                 <label for="eshopbox_cod_aramex"><b><?php _e( 'Payment Type :', 'eshopbox-manifest' ); ?></b></label>
                             </th>
                             <td>
                                 <?php echo $this->paymentType();  ?><br />
                             </td>
                       </tr>  
                        
                        
                        
                        <tr>
                            <th>
                                 <label for="eshopbox_cod_aramex"><b><?php _e( 'Select provider :', 'eshopbox-manifest' ); ?></b></label>
                             </th>
                             <td>
                                 <?php echo $this->getShippingProvider();  ?><br />
                             </td>
                       </tr>

                       <tr>
                            <th>
                                <label for="eshopbox_cod_aramex"><b><?php _e( 'Pending Shipments :', 'eshopbox-manifest' ); ?></b></label>
                            </th>
                            <td id="noshipments">0
                            </td>
                       </tr> 
                       <tr>
                            <th>
                                 <label for="eshopbox_cod_aramex"><b><?php _e( 'Order ids :', 'eshopbox-manifest' ); ?></b></label>
                            </th>
                            <td id="noorders">Nil
                            </td>
                       </tr> 
                       <tr>
                            <th>
                                 <label for="eshopbox_cod_aramex"><b><?php _e( 'Enter order id :', 'eshopbox-manifest' ); ?></b></label>
                            </th>
                            <td id="ordertext">
                                <input type="text" name="ordert" id="ordert" />
                            </td>
                       </tr>  
                       </table>
                       </div>
                       </div>
       </div>
    </div>
</div> 
<form name="manifestform" id="manifestform" style="margin:4px 15px 0 0;" method="post" action="<?php echo $this->plugin_url() ?>/printmanifest.php" target="_blank">                               
    <div id="manifesttable">
    <input type="submit" id="submit" class="button" name="submit" style="margin-bottom: 10px; margin-right:20px" value="Create Manifest" />
   <input type="submit" id="asd" class="button markasship" name="dsd" style="margin-bottom: 10px" value="Mark Ship" /> 
        <table width="100%" cellspacing="0" cellpadding="0" class="widefat">
            <thead>
                <tr>
        <th style="padding:7px 7px 8px; "><input type="checkbox" name="checkall" id="checkall" class="select-all" value="'.$theorder->id.'" /></th>
        <th style="padding:7px 7px 8px; ">AWB No</th>
        <th style="padding:7px 7px 8px; ">Order Id</th>
        <th style=" padding:7px 7px 8px;">Date</th>
        <th style="padding:7px 7px 8px;">Name</th>
        <th style="padding:7px 7px 8px;">Amount</th>
        <th style="padding:7px 7px 8px;">Payment Method</th>

        <th style="padding:7px 7px 8px;">Status</th>
        <th style="padding:7px 7px 8px;">Action</th>
        </tr></thead>
            <tfoot>
                <tr>
        <th style="padding:7px 7px 8px; "><input type="checkbox" class="select-all" name="checkall" id="checkall" value="'.$theorder->id.'" /></th>
        <th style="padding:7px 7px 8px; ">AWB No</th>
        <th style="padding:7px 7px 8px; ">Order Id</th>
        <th style=" padding:7px 7px 8px;">Date</th>
        <th style="padding:7px 7px 8px;">Name</th>
        <th style="padding:7px 7px 8px;">Amount</th>
        <th style="padding:7px 7px 8px;">Payment Method</th>

        <th style="padding:7px 7px 8px;">Status</th>
        <th style="padding:7px 7px 8px;">Action</th>
        </tr></tfoot>
       
   

    
    <tbody id="manifdetail"></tbody>
    </table>
</div>
<input type="hidden" id="shipprovider" name="shipprovider" value="" />
<input type="hidden" id="onlyorders" name="onlyorders" value="" />
<input type="hidden" id="onlyshipments" name="onlyshipments" value="" />
<input type="hidden" name="manifest" value="true" /> 
<input type="hidden" name="paymethod" id="paymethod" value="" /> 
<input type="submit" id="submit" class="button" name="submit" style="margin-top: 10px; margin-right:20px" value="Create Manifest" />
<input type="submit" id="markasship" class="button markasship" name="markasship" style="margin-top: 10px;" value="Mark Ship" />
<input type="submit" id="uploaddelhiverymanifest" class="button" name="uploaddelhiverymanifest" style="margin-top: 10px;" value="Upload Delhivery manifest" />

</form>

<?php          
// ajax call
$eshopbox->add_inline_js("
    jQuery(document).ready(function(){
    //var xyz = escape(".$this->plugin_url().");
      //  alert(xyz);
    jQuery('#uploaddelhiverymanifest').bind('click',function(event){
      //  event.preventDefault();
        jQuery('#manifestform').attr('action','http://yoda.in/wp-content/plugins/manifest/pushdelhiverymanifest.php');
        jQuery('#manifestform').attr('target','_blank');
         $('#manifestform').submit();

    });


    jQuery('.markasship').on('click',function(event){
   // event.preventDefault();
   var checkcheck = 0;
        $(':checkbox').each(function() {
           if(this.checked == true){
               // alert('checked');
                checkcheck = 1;
            }
        });   
      if(checkcheck==1){
    jQuery('#manifestform').attr('action','');
    jQuery('#manifestform').attr('target','');
    

     $('#manifestform').submit();
} else {
alert('Please select a shipment');
return false;
}
});

jQuery('.rem').live('click',function(event){
    event.preventDefault();

    jQuery('#'+jQuery(this).attr('rel')+'tr').remove();

});
    
    
$('.select-all').on('click',function(event) {   
    if(this.checked) {
        // Iterate each checkbox
        $(':checkbox').each(function() {
            this.checked = true;                        
        });
    }
});

          jQuery('#ordert').keyup(function(event){
          var tex = jQuery(this).val();
          var checkfl = 0;
              if(event.keyCode==13){
            //  alert(jQuery('#onlyshipments').val());
              orderString = jQuery('#onlyorders').val(); 
             // alert(orderString);
                var arrayOrders = orderString.split(',');
             //   alert(arrayOrders[0]);
              jQuery.each(arrayOrders,function(i,v){
              if(arrayOrders[i]==tex){
              checkfl = 1;
              }
               
                });
                
var arrayShipments = jQuery('#onlyshipments').val().split(',');
              jQuery.each(arrayShipments,function(i,v){
              if(arrayShipments[i]==tex){
              checkfl = 1;
              }
               
                });
                
if(checkfl==0){
alert('Invalid order/shipment id');
return false;
}
                
                  var textBoxText = jQuery(this).val();
                  jQuery(this).val('');
                  var orderData = {
                  action: 'my_orderaction',
                  orderId : textBoxText
               };

               jQuery.post(ajaxurl,orderData,function(response){
                      jQuery('#manifdetail').after(response);
               });

              }
          });
    });  

    jQuery('#selectprovider').bind('change',function(){
   var payType = jQuery('#paytype').val()
    jQuery('#shipprovider').val(jQuery(this).val());
    jQuery('#paymethod').val(payType);

      jQuery('#loadimg').show();
          var data = {
          action: 'my_actions',
          whatever: 1234,
          valselected : jQuery(this).val(),
          paytype : payType
  };

jQuery.post(ajaxurl, data, function(response) {

         splitResponse = response.split('$');
         if(splitResponse[0]==0){
             alert('No pending shipments');
             jQuery('#noshipments').html('0');
             jQuery('#noorders').html('Nil');
             jQuery('#ordert').val('');

         } else {
         jQuery('#noshipments').html(splitResponse[0]);
         jQuery('#noorders').html(splitResponse[1]);
         jQuery('#onlyorders').val(splitResponse[2]);
         jQuery('#onlyshipments').val(splitResponse[3]);
         
         jQuery('#ordert').val('');
     }
  });
});

");  
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
  
    /**
     * 
     * create table for plugin
     */
    public function createTables(){
        global $wpdb;
        $table_name = $wpdb->prefix ."manifest"; 
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `orderid` varchar(255) NOT NULL,
        `dates` bigint(20) NOT NULL,
        `provider` varchar(255) NOT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=latin1;";
        require_once(ABSPATH . 'eshop-admin/upgrade-functions.php');
        dbDelta($sql);  
    }
    
    function getCustomReportTab($charts){
    
    $charts['manifest']= array(
			'title'         => __( 'Manifest', 'eshopbox' ),
			'charts'        => array(
				array(
					'title'       => __( 'Overview', 'eshopbox' ),
					'description' => '',
					'hide_title'  => true,
					'function'    => array(&$this,'eshopbox_manifest')
				),
			)
		);
    
        return $charts;
    
}





function eshopbox_manifest(){
    
global $wpdb,$eshopbox;
   echo '<form method="post" action="" style="margin-top:80px;">
<p><label for="from">From</label> <input type="text" name="start_date" id="from"  value="'.$_POST['start_date'].'" /> <label for="to">To</label> <input type="text" name="end_date" id="to"  value="'.$_POST['end_date'].'" /> <input type="submit" class="button" value="Show" /></p>
	<input type="hidden" name="manipost" value="true" /></form>';
   
            if($_POST['manipost']=='true'){
                $to =  explode('-',$_POST['start_date']);
                $toDate =  mktime(0,0,0,$to[1],$to[2],$to[0]);
                $from = explode('-',$_POST['end_date']);
                $fromDate =  mktime(0,0,0,$from[1],$from[2],$from[0]);
             //   echo "SELECT id,orderid,dates from wp_manifest where dates between $toDate and $fromDate ";
                $re = $wpdb->get_results( $wpdb->prepare( "SELECT id,orderid,dates,provider,paytype from eshop_manifest where dates between $toDate and $fromDate "));
           echo '<div style="clear:both;"><div style="float:left;width:100px;">ID</div><div style="float:left;width:400px;">Order Id</div><div style="float:left;width:100px;">Date</div><div style="float:left;width:100px;">Action</div></div>';
            foreach($re as $key=>$val){
                $ah = '';
                    $getOrderId = explode(',',$val->orderid);
                    foreach($getOrderId as $key1=>$val1){
            $ah .='<input type="hidden" class="" name="check[]"  value="'.$val1.'" />';           
                        
                        
            }
                
                echo '<div style="clear:both;"><div style="float:left;width:100px;">'.$val->id.'</div><div style="float:left;width:400px;">'.$val->orderid.'</div><div style="float:left;width:100px;">'.date('d-m-Y',$val->dates).'</div><div style="float:left;width:100px;"><form method="post" target="_blank" action="'.$this->plugin_url().'/printmanifest.php?history=true&manid='.$val->id.'&mandate='.$val->dates.'">'.$ah.'<input type="submit" value="print" /><input type="hidden" id="shipprovider" name="shipprovider" value="'.$val->provider.'" /><input type="hidden" name="paymethod" id="paymethod" value="'.$val->paytype.'" /> </form></div></div>';
            }    
                
              // echo '<pre>';
              //  print_r($re);
//$wpdb->get_var( $wpdb->prepare( "SELECT SUM( order_item_meta.meta_value" ));
            }
            echo'<script type="text/javascript">
                jQuery(document).ready(function(){
                    		var dates = jQuery( "#from, #to" ).datepicker({
		defaultDate: "",
		dateFormat: "yy-mm-dd",
		numberOfMonths: 1,
		minDate: "-12M",
		maxDate: "+0D",
		showButtonPanel: true,
		showOn: "button",
		buttonImage: "'.$eshopbox->plugin_url().'/assets/images/calendar.png",
		buttonImageOnly: true,
		onSelect: function( selectedDate ) {
			var option = this.id == "from" ? "minDate" : "maxDate",
				instance = jQuery( this ).data( "datepicker" ),
				date = jQuery.datepicker.parseDate(
					instance.settings.dateFormat ||
					jQuery.datepicker._defaults.dateFormat,
					selectedDate, instance.settings );
			dates.not( this ).datepicker( "option", option, date );
		}
	});

                });

	</script>';
            
}
    
    
    
}
new WC_Manifest();