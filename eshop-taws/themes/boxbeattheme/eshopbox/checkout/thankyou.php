<?php

/**

 * Thankyou page

 *

 * @author 		WooThemes

 * @package 	eshopbox/Templates

 * @version     2.0.0

 */



if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



global $eshopbox;



if ( $order ) : ?>



	<?php if ( in_array( $order->status, array( 'failed' ) ) ) : ?>



		<div class="orderrecieved_message"><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction.', 'eshopbox' ); ?></div>



		<div class="orderrecieved_message">
			<?php

			if ( is_user_logged_in() )

				_e( 'Please attempt your purchase again or go to your account page.', 'eshopbox' );

			else

				_e( 'Please attempt your purchase again.', 'eshopbox' );

		?>
        </div>



		<div class="paynow_btnblock">

			<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay Now', 'eshopbox' ) ?></a>

			<?php if ( is_user_logged_in() ) : ?>

			<a href="<?php echo esc_url( get_permalink( eshopbox_get_page_id( 'myaccount' ) ) ); ?>" class="button pay" style="display:none;"><?php _e( 'My Account', 'eshopbox' ); ?></a>

			<?php endif; ?>

		</div>



	<?php else :

            $orderdetail=$order-> order_custom_fields;
        //$ordertotal=$orderdetail[_order_total][0];
        //$orderpaymenttitle=$orderdetail[_payment_method_title][0];

         $billemail=$orderdetail['_billing_email'][0];
         //echo '<pre>';print_r($orderdetail);

            ?>
            
	<div id="thanksmain">
    	<div class="thanks_leftblock">
        <?php do_action( 'eshopbox_thankyou_' . $order->payment_method, $order->id ); 
        //echo '<pre>';print_r($order);
//        if($order->payment_method =='cod'){
//            echo " <div class='tickicon_green'>Thank you.Your order has been placed successfully.</div>
//                <div class='sucessfully_order green_success'>Your order has been placed successfully and right now is in processing. A confirmation mail regarding this order has been send to <b>".$order->billing_email.".</strong></div>
//";
//        }
//        else{
//            echo " <div class='tickicon_green'>Thank you.Your order has been placed successfully.</div>
//                <div class='sucessfully_order green_success'>Your order has been placed successfully and right now is in processing. A confirmation mail regarding this order has been send to <b>".$order->billing_email.".</strong></div>
//";
//        }
        ?>
        
			<?php endif; ?>
            <?php do_action( 'eshopbox_thankyou', $order->id ); ?>
            <?php else : ?>
            <?php _e( 'Thank you. Your order has been received.', 'eshopbox' ); ?>
            <?php endif; ?>
    	</div>
      <div class="thanks_rightblock">
<div class="track_orderblock">

       <h3>Order Summary</h3>
      <ul class="order_details">
         <li class="order">
		<span>Order Id: </span>
				<strong><?php echo $order->get_order_number(); ?></strong>

			</li>

			<li class="date">

				<span>Order Date : </span> 
				<strong><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></strong>

			</li>

			<li class="total">

				<span>Order amount: </span> 
				<strong><span class="amount"><?php echo $order->get_formatted_order_total(); ?></span></strong>

			</li>
            	<li class="item_no">

				<span>No. of item(s) :</span> 
				<strong><span class="amount"><?php echo $order->get_item_count(); ?></span></strong>

			</li>

			
			<li class="method">

				<span>Payment method :</span>
				<strong><?php echo $order->payment_method_title; ?></strong>

			</li>

			
		</ul>

        </div>
        
		
        
        </div>
    </div>
<script type="text/javascript">
var fb_param = {};
fb_param.pixel_id = '6012132747534';
fb_param.value = '0.00';
fb_param.currency = 'INR';
(function(){
  var fpw = document.createElement('script');
  fpw.async = true;
  fpw.src = '//connect.facebook.net/en_US/fp.js';
  var ref = document.getElementsByTagName('script')[0];
  ref.parentNode.insertBefore(fpw, ref);
})();
</script>
<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/offsite_event.php?id=6012132747534&amp;value=0&amp;currency=INR" /></noscript>