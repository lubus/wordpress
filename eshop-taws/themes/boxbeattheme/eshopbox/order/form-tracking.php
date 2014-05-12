<?php
/**
 * Order tracking form
 *
 * @author 		WooThemes
 * @package 	eshopbox/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $eshopbox, $post;
?>
<div class="login_lost1">
<div class="login_lost">
<div class="track_orderleft">
<h2 class="track_head"><?php _e( 'Check Order Status', 'eshopbox' ); ?></h2>
<p class="text_class"><?php _e( 'Enter your order id and email address to check the current status of your order.', 'eshopbox' ); ?></p>
<div class="form_track1">
<form action="<?php echo esc_url( get_permalink($post->ID) ); ?>" method="post" class="track_order">

<p class="form-row"><label for="orderid"><?php _e( 'Order ID*', 'eshopbox' ); ?></label> <input class="input-text input_lostpo" type="text" name="orderid" id="orderid" placeholder="<?php _e( 'Found in your order confirmation email.', 'eshopbox' ); ?>" /></p>
	<p class="form-row"><label for="order_email"><?php _e( 'Email Address*', 'eshopbox' ); ?></label> <input class="input-text input_lostpo" type="text" name="order_email" id="order_email" placeholder="<?php _e( 'Email you used during checkout.', 'eshopbox' ); ?>" /></p>
	<div class="clear"></div>

	<div class="buttons_border buttons_border1 buttons_border2"><p class="form-row"><input type="submit" class="button lost_pass_submit" name="track" value="<?php _e( 'TRACK NOW', 'eshopbox' ); ?>" /></p></div>
	<?php $eshopbox->nonce_field('order_tracking') ?>

</form>
</div>
<div class="track_bto">
<div id="cartfaqblock">		
    <section class="faq-section">
     <h1 class="faq-section-title"><span class="collapse-toggle"><b></b>  What does order status means?</span></h1>
      <div class="faq-section-entry collapse" style="display: none;"> The order statuses let you know how far along your order is. The following statuses are used for an order to Process.
  <ul>
  <li> <span>Pending / On - Hold -</span> Order placed Successfully but the Payment is awaiting & in case of Cash on Delivery order 
        has not been confirmed Yet.</li>
  <li><span>Processing -</span> Payment / Confirmation for your order is received & order is accepted by the merchant, But the 
        order is awaiting fulfillment.</li>
  <li><span>Ready-Ship -</span>  Your order item(s) packed by the merchant but not yet dispatched. </li>
  <li>   <span>Shipped -</span> Your order has been dispatched/shipped that contains the Tracking number & name of the logistics 
        provider.</li>
  
  </ul>    
      
      
      </div>
    </section>
  <section class="faq-section">
     <h1 class="faq-section-title"><span class="collapse-toggle"><b></b>  How do I track my Order after it shipped?</span></h1>
      <div class="faq-section-entry collapse" style="display: none;">  An Email and sms are sent to you after the Order is Shipped that contains the tracking number and name of the 
  logistics provider.<br/>
    Visit the website of the logistics provider and enter the tracking number for your shipment details.
   </div>
    </section>
      <section class="faq-section">
     <h1 class="faq-section-title"><span class="collapse-toggle"><b></b>    How do I use my tracking number to check the details of my shipment?</span></h1>
     <div class="faq-section-entry collapse" style="display: block;">Courier companies use certain standard abbreviations and terminologies. Some of the most commonly used terms are explained 
  below. If it didn't answer your question please call us.
  <table cellpadding="0" cellspacing="0">
      <tbody><tr>
          <td class="option">In transit</td>
            <td>Your order is on its way to your city</td>
        </tr>
        <tr>
          <td class="option">out for delivery</td>
            <td>Your order has reached your city and we will attempt to deliver it today</td>
        </tr>
        <tr>
          <td class="option">Hold at hub</td>
            <td>We tried delivering your order at least once and we will try again in 48 hours</td>
        </tr>
        <tr>
          <td class="option">RTO in transit</td>
            <td>Your order was undelivered and is on its way back to our warehouse.</td>
        </tr>
        
    </tbody></table>
  
  </div>
    </section>
    
    </div>
    </div>

</div><!--track_orderleft close-->

<div class="track_orderright">
<div class="contact_right">
<h5>NEED HELP ?</h5>
<P>If you have any questions or need further 
assistance before placing this order, Please 
Contact us</P>

<h6>Or by Email : </h6>
<p  class="mg_bot"><strong>support@phive rivers.com</strong></p>

</div>



</div>

<div class="clear"></div>

</div>
</div>