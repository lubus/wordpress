<?php
/**
 * Order details
 *
 * @author 		WooThemes
 * @package 	eshopbox/Templates
 * @version     2.0.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $eshopbox;

$order = new WC_Order( $order_id );

?>


<!--<table class="shop_table order_details weldone">
	<thead>
		<tr>
        	<th class="product-info"><?php _e( 'Order Info', 'eshopbox' ); ?></th>
	        <th class="product-image"><?php _e( 'Description', 'eshopbox' ); ?></th>
        	<th class="product-name"></th>
                <th class="product-price"><?php _e( 'Price', 'eshopbox' ); ?></th>
                <th class="product-qty"><?php _e( 'Quantity', 'eshopbox' ); ?></th>
		<th class="product-total"><?php _e( 'Total', 'eshopbox' ); ?></th>
		</tr>
	</thead>

	<tbody>
		<?php
		if (sizeof($order->get_items())>0)
		{
			$datadispstr = '';
			$stiched_total = '';
			foreach($order->get_items() as $item) {
			$item_meta = $item['item_meta'];
			$_product = get_product( $item['variation_id'] ? $item['variation_id'] : $item['product_id'] );
			$src = wp_get_attachment_image_src( get_post_thumbnail_id($item['product_id']),'shop_thumbnail');
			$size=$_product->variation_data['attribute_pa_size'];
			if($size){ $sizeelement= '</br>Size : '.$size; }

			$datadispstr.= '<tr class = "' . esc_attr( apply_filters( 'eshopbox_order_table_item_class', 'order_table_item', $item, $order ) ) . '">
			<td class="product-info">';
			$datadispstr.= '<span class="sku_st">'.$order->get_order_number().'</span><br/>On '.date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) );
			//--- YY---
			$item_uploaded_file_url = $item_meta[item_uploaded_file_url][0];
			$cart_contents_stiched  = $item_meta[cart_contents_stiched][0];

			if($cart_contents_stiched!=''){
				$stiched_total = $stiched_total+$cart_contents_stiched;
			}

			if($item_uploaded_file_url!='')
			{
				$datadispstr.= '<br><div class="pr_name pr_mes"><a href="'.FILE_UPLOAD_DIR_URL.basename($item_uploaded_file_url).'">Your Measurement</a></div>';
			}
			$datadispstr.= '</td>';

			if (@getimagesize($src[0])) {
				$datadispstr.='<td class="product_image"><a href="'.get_permalink( $item['product_id'] ).'"><img width="94" height="94" class="attachment-shop_thumbnail wp-post-image" src="'.$src[0].'"></a></td>';
			}else{
				$datadispstr.='<td class="product_image">&nbsp;</td>';
			}
			$datadispstr.='<td class="product-name">'.apply_filters( 'eshopbox_order_table_product_title', '<a class="productnametd" href="' . get_permalink( $item['product_id'] ) . '">' . $item['name'] . '</a>', $item ) . ' ' .'</br><div class="style_mat">Style Code: '.$_product->get_sku().$sizeelement.'</div></td>';

            $datadispstr.= '<td class="product-total">' . $order->get_formatted_line_subtotal( $item ) . '</td><td class="product-qty">' . $item['qty'] .'</td><td class="product-total">' . $order->get_formatted_line_subtotal( $item ) . '</td></tr>';
				// Show any purchase notes
				if ($order->status=='completed' || $order->status=='processing') {
					if ($purchase_note = get_post_meta( $_product->id, '_purchase_note', true))
						$datadispstr.='<tr class="product-purchase-note"><td colspan="3">' . apply_filters('the_content', $purchase_note) . '</td></tr>';
				}

			}
			echo $datadispstr;
		}

		do_action( 'eshopbox_order_items_table', $order );
		?>
	</tbody>

</table>-->

<!--<table class="shop_table order_details weldone price_content">
	<tfoot>
    <tr>
	<?php

	//echo '<pre>';  print_r($order->get_items()); echo '</pre>';
	if ( $totals = $order->get_order_item_totals() ) foreach ( $totals as $total ) : ?>
		<th class="order_th"><?php echo $total['label']; ?></th>
				<td class="val_cls"><?php echo $total['value']; ?></td>
			</tr>
			<?php
		endforeach;
	?>
	</tfoot>
</table>-->


<div class="track_orderdetail">
<div class="track_orderdetailleft">

<?php  if (is_page(59)) {
	?>
   

<div class="trackstatusdiv">
<?php
global $eshopbox;

$total_payable_amt = $order->order_custom_fields['_order_total'][0];
	$status = get_term_by('slug', $order->status, 'shop_order_status');
echo '<div class="status_order">';
	$order_status_text = sprintf( __( 'Status of your order: <span>%s </span>which was made %s is <span> &ldquo;%s&rdquo;</span>', 'eshopbox' ), $order->get_order_number(), human_time_diff(strtotime($order->order_date), current_time('timestamp')) . ' ' . __( 'ago', 'eshopbox' ), __($status->name, 'eshopbox') );

	if ($order->status == 'completed') $order_status_text .= ' ' . __( 'and was completed', 'eshopbox' ) . ' ' . human_time_diff(strtotime($order->completed_date), current_time('timestamp')).__( ' ago', 'eshopbox' );

	$order_status_text .= '.';
	echo $order_status_text;
	//echo wpautop( esc_attr( apply_filters( 'eshopbox_order_tracking_status', $order_status_text, $order ) ) );
	echo '</div>';
?>
<p class="confi_cls">A confirmation mail regarding this order has been sent to <span><?php
	if ($order->billing_email) echo ''.__( '', 'eshopbox' ).''.$order->billing_email.'';
?></span> </p>
<!--<div class="block_hide">
<?php //if($order->status =='shipped'){    
//if($order->order_custom_fields['_tracking_number'][0] != ''){ ?>

<h3>SHIPMENT DETAILS</h3>
<?php //if($order->order_custom_fields['_tracking_provider'][0] != ''){$trackingprovider=$order->order_custom_fields['_tracking_provider'][0];}
       // else{$trackingprovider=$order->order_custom_fields['_custom_tracking_provider'][0];}
  
?>
<ul>
<li>TRACKING NO. : <span><?php //echo $order->order_custom_fields['_tracking_number'][0]; ?></span></li>
<li>COURIER NAME : <span><?php //echo $trackingprovider; ?></span></li>
</ul>
 <?php // } ?>
</div>-->
 
</div>
    <div class="shippingdetailblock">
          <?php if( $order->status =='shipped'){?> 
    	<div class="title">SHIPMENT DETAILS</div>
        <?php if($order->order_custom_fields['_tracking_provider'][0] != ''){$trackingprovider=$order->order_custom_fields['_tracking_provider'][0];}
        else{$trackingprovider=$order->order_custom_fields['_custom_tracking_provider'][0];}
        ?>
        <div class="cover_pull">
        <div class="pull-left">TRACKING NO. : <span><?php echo $order->order_custom_fields['_tracking_number'][0]; ?></span></div>
        <div class="pull-left pull-left1">COURIER NAME : <span><?php echo $trackingprovider; ?></span></div>
        </div>
        <?php }?>
    </div>	

 <?php //}?>
    
<?php } ?>
<div class="amountdue_div">
<div class="amountdue_divleft">
    <?php //echo "<pre>"; print_r($order);?>
   <?php if($order->payment_method =='payu_in'){?>
<h2>AMOUNT Paid: <span class="amt_duprice" display-style="color:green"> <?php 
echo $order->order_custom_fields['_order_total'][0]; ?></span></h2>
   <?php }else{?>
<h2>AMOUNT DUE : <span class="amt_duprice" display-style="color:red"> <?php echo $order->order_custom_fields['_order_total'][0]; ?></span> <span>at time of delivery</span></h2>
   <?php }?>
</div>
<div class="amountdue_divright"><h2>PAYMENT MODE: <span><?php echo $order->payment_method_title; ?></span></h2></div>
<div class="clear"></div>
</div>

<div class="trackorder_item">

<h2>Your ordered item(s)</h2>

<ul>
<?php
$i = 1;
foreach($order->get_items() as $item) {

    $item_meta = $item['item_meta'];
    $_product = get_product( $item['variation_id'] ? $item['variation_id'] : $item['product_id'] );
    $src = wp_get_attachment_image_src( get_post_thumbnail_id($item['product_id']),'shop_thumbnail');
    $size=$_product->variation_data['attribute_pa_size'];
    $totalquantity+=$item['qty'];
			//echo "<pre>";print_r($size);echo "</pre>";
?>
        <li><span class="count_nm"><?php echo $i; ?>.</span><?php echo $item['name']; ?><span>/ Size-</span><?php echo $size; ?><span>/ Qty-</span><?php echo $item['qty']; ?></li>
<?php
$i++;
} 
?>
</ul>
</div>
<div class="what_hapnext">
<div class="trackadress_div">

<h2>DELIVERY Address</h2>
        <p>
        <?php
        if (!$order->get_formatted_shipping_address()) _e( 'N/A', 'eshopbox' ); else echo $order->get_formatted_shipping_address();
        ?>
        </p>
        
                <dl class="customer_details">
<?php
	if ($order->billing_email) echo '<dt>'.__( 'Email:', 'eshopbox' ).'</dt><dd>'.$order->billing_email.'</dd>';
?>
</dl>
        <dl class="customer_details">
<?php
	if ($order->billing_phone) echo '<dt>'.__( 'Telephone:', 'eshopbox' ).'</dt><dd>'.$order->billing_phone.'</dd>';
?>
</dl>
</div>

<div class="block_nexth">
<h2>What Happens Next?</h2>
<p>
A confirmation mail regarding this order has been sent to 
<strong><?php echo $order->order_custom_fields['_billing_email'][0];?></strong> After shipping you will be receiveing 
an email stating the Shipmnet tracking information and the 
expected delivery date for your order.
</p>
<p>You can track the current status of your order by clicking this
link <a href="<?php echo get_permalink( 59 ); ?>" target="_blank">Track my order</a></p>
</div>
</div>

</div>
<?php if(is_page( 59 )) { ?>
<div class="track_orderdetailright">
<div class="track_orderblock">
<?php

$valueshipped=get_post_meta( $order->id, '_shipped_date' );
$valuereadyshipped=get_post_meta( $order->id, '_readyshipped_date' );
$valueprocessing=get_post_meta( $order->id, '_processing_date' );
//echo "test".$valueprocessing[0];
//echo "<pre>";print_r($valueprocessing);echo "</pre>";
?>
       <h3>Order flow</h3>
      <ul class="order_details">
         <li class="order">
		Order Placed. :
				<strong><?php echo date('M d,Y', strtotime($order->order_date));?></strong>

			</li>
<?php if(!empty($valueprocessing[0])) {?>
			<li class="date">

				
Order Processed :
				<strong><?php echo date('M d,Y', $valueprocessing[0]);?></strong>

			</li>
<?php } if(!empty($valuereadyshipped[0])){?>
			<li class="total">

				Order.Item Packed :
				<strong><span class="amount"> <?php echo date('M d,Y', $valuereadyshipped[0]); ?></span></strong>

			</li>
<?php } if(!empty($valueshipped[0])){?>
			
			<li class="method">

				Order Dispatched :
				<strong><?php echo date('M d,Y',$valueshipped[0]);?></strong>

			</li>
<?php } ?>
			
		</ul>

        </div>

<div class="track_orderblock">

       <h3>Order Summary</h3>
      <ul class="order_details">
         <li class="order">
		Order Id:
				<strong><?php echo $order->get_order_number(); ?></strong>

			</li>

			<li class="date">

				Order Date : 
				<strong><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></strong>

			</li>

			<li class="total">

				Order amount: 
				<strong><span class="amount"><?php echo $order->get_formatted_order_total(); ?></span></strong>

			</li>
            	<li class="item_no">

				No. of item(s) : 
				<strong><span class="amount"><?php echo $totalquantity; ?></span></strong>

			</li>

			
			<li class="method">

				Payment method :
				<strong><?php echo $order->payment_method_title; ?></strong>

			</li>

			
		</ul>

        </div>


</div>

<?php } ?>

<div class="clear"></div>
</div>



<?php if ( get_option('eshopbox_allow_customers_to_reorder') == 'yes' && $order->status=='completed' ) : ?>
	<p class="order-again">
		<a href="<?php echo esc_url( $eshopbox->nonce_url( 'order_again', add_query_arg( 'order_again', $order->id, add_query_arg( 'order', $order->id, get_permalink( eshopbox_get_page_id( 'view_order' ) ) ) ) ) ); ?>" class="button"><?php _e( 'Order Again', 'eshopbox' ); ?></a>
	</p>
<?php endif; ?>

<?php do_action( 'eshopbox_order_details_after_order_table', $order ); ?>





<?php if (get_option('eshopbox_ship_to_billing_address_only')=='no') : ?>



<!-- /.col2-set -->

<?php endif;  ?>

<div class="clear"></div>
