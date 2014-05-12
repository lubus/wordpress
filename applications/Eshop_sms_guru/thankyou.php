<?php

/**

 * Thankyou page

 *

 * @author 		WooThemes

 * @package 	WooCommerce/Templates

 * @version     2.0.0

 */



if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



global $woocommerce;



if ( $order ) : ?>



	<?php if ( in_array( $order->status, array( 'failed' ) ) ) : ?>



		<div class="orderrecieved_message"><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction.', 'woocommerce' ); ?></div>



		<div class="orderrecieved_message">
			<?php

			if ( is_user_logged_in() )

				_e( 'Please attempt your purchase again or go to your account page.', 'woocommerce' );

			else

				_e('Please attempt your purchase again.', 'woocommerce' );

		?>
        </div>



		<div class="paynow_btnblock">

			<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay Now', 'woocommerce' ) ?></a>

			<?php if ( is_user_logged_in() ) : ?>

			<a href="<?php echo esc_url( get_permalink( woocommerce_get_page_id( 'myaccount' ) ) ); ?>" class="button pay" style="display:none;"><?php _e( 'My Account', 'woocommerce' ); ?></a>

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
        <?php do_action( 'woocommerce_thankyou_' . $order->payment_method, $order->id ); ?>          
               
        <div class="thanksamountblock">
            <?php if( $order->payment_method_title == 'Cash on Delivery' ){?>
           	<div class="pull-left">AMOUNT DUE: <span style="color:#ff0000;"><?php echo $order->get_formatted_order_total()?></span> AT TIME OF DELIVERY</div>
            <?php }else{?>
                <div class="pull-left">AMOUNT PAID: <?php echo $order->get_formatted_order_total(); ?></div>
            <?php } ?>
                <div class="pull-right">PAYMENT MODE:  <span><?php echo $order->payment_method_title;?></span></div>
           </div>
			<?php endif; ?>
            <?php do_action( 'woocommerce_thankyou', $order->id ); ?>
            <?php else : ?>
            <?php _e( 'Thank you. Your order has been received.', 'woocommerce' ); ?>
            <?php endif; ?>
           
    	</div>
      <div class="thanks_rightblock">

        <div class="thanks_orderblock">

        	<h3>Order Summary</h3>

			<ul class="order_details">

			<li class="order">

				<?php _e( 'Order:', 'woocommerce' ); ?>

				<strong><?php echo $order->get_order_number(); ?></strong>

			</li>

			<li class="date">

				<?php _e( 'Date:', 'woocommerce' ); ?>

				<strong><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></strong>

			</li>
                        <li class="noofitems">

				<?php _e( 'No of Items : ', 'woocommerce' );  ?>

				<strong><?php echo $order->get_item_count(); ?></strong>

			</li>

			<li class="total">

				<?php _e( 'Total:', 'woocommerce' ); ?>

				<strong><?php echo $order->get_formatted_order_total(); ?></strong>

			</li>

			<?php if ( $order->payment_method_title ) : ?>

			<li class="method">

				<?php _e( 'Payment method:', 'woocommerce' ); ?>

				<strong><?php echo $order->payment_method_title; ?></strong>

			</li>

			<?php endif; ?>

		</ul>

        </div>
		<div class="thanksneedhelpblock">
        	<div class="needhelp_title">NEED HELP ?</div>
        	<div class="needhelp_content">If you have any questions or need further assistance regarding your order, Please Contact us</div>
            <div class="thanks_viaphone">Via Phone : <span>+91-9212577799</span></div>
            <div class="thanks_viaphone">Or by Email :<span>support@gipsyonline.com</span></div>
        </div>
        <div class="thanksnewsletter">
        	<div class="thankscontainer">
        		<?php mailchimpSF_signup_form(); ?>
             </div>
        </div>
        </div>
    </div>
	
<!--                    <div class="thanku_const_msg">
                        With your purchase you have acknowledged the efforts of thousands of weavers, spinners and couture artists who have, over the past several years, kept the art of handmade fabric alive and flourishing.
                    </div>-->

	