<?php

/**
 * Customer Order Shipment Email
 *
 * An email sent to the admin when a new order is received/paid for.
 *
 * @class 		WC_Email_Order_Shipment
  * @version		1.2
 * @package		Eshop_email_guru/Classes/Emails
 * @author 		Shalu
 * @extends 	WC_Email
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class WC_Email_Order_Shipment extends WC_Email {

	/**
	 * Constructor
	 */
	function __construct() {

		$this->id                       = 'order_shipment';
		$this->title 			= __( 'Order Shipment', 'eshopbox' );
		$this->description		= __( 'This is an order notification sent to the customer after payment containing order details.', 'eshopbox' );

		$this->heading 			= __( 'Thank you for your order', 'eshopbox' );
		$this->subject      	= __( 'Your {blogname} order receipt from {order_date} for shipment', 'eshopbox' );
		$this->template_html 	= 'emails/order-shipment.php';
		$this->template_plain 	= 'emails/plain/order-shipment.php';

                // Call parent constructor
		parent::__construct();


		// Triggers for this email		
                if($_SESSION['shipped']=='true')
                {
                    $order_id=$_SESSION['orderid'];
                    $tracking_provider=$_SESSION['tracking_provider'];
                    $tracking_number=$_SESSION['tracking_number'];
                    $this->trigger($order_id,$tracking_provider,$tracking_number);
                    unset($_SESSION['shipped']);
                    unset($_SESSION['orderid']);
                    unset($_SESSION['tracking_provider']);
                    unset($_SESSION['tracking_number']);
                }
		
	}

	/**
	 * trigger function.
	 *
	 * @access public
	 * @return void
	 */
	function trigger( $order_id ,$tracking_provider,$tracking_number) {
		global $eshopbox;

		if ( $order_id ) {
			$this->object 		= new WC_Order( $order_id );
			$this->recipient	= $this->object->billing_email;
                        $customer_name=$this->object->billing_first_name.''.$this->object->billing_last_name;
			$this->find[] = '{order_date}';
			$this->replace[] = date_i18n( eshopbox_date_format(), strtotime( $this->object->order_date ) );

			$this->find[] = '{order_number}';
			$this->replace[] = $this->object->get_order_number();
                        $this->find[] = '{customer_name}';
			$this->replace[] = $customer_name;
                        $this->find[] = '{shipping_amount}';
			$this->replace[] = $this->object->order_shipping;
                        $this->find[] = '{courier_name}';
			$this->replace[] = $tracking_provider;
                        $this->find[] = '{shipping_id}';
			$this->replace[] = $tracking_number;
		}
           //echo $this->get_content();exit;
                 //if (! $this->get_recipient() )
		if ( ! $this->is_enabled() || ! $this->get_recipient() )                       
			return;

		$this->send( $this->get_recipient(), $this->get_subject(),$this->get_content(), $this->get_headers(), $this->get_attachments() );
	}

	/**
	 * get_content_html function.
	 *
	 * @access public
	 * @return string
	 */
	function get_content_html() {
		ob_start();
		eshopbox_get_template( $this->template_html, array(
			'order' 		=> $this->object,
			'email_heading' => $this->get_heading()
		) );
		return ob_get_clean();
	}

	/**
	 * get_content_plain function.
	 *
	 * @access public
	 * @return string
	 */
	function get_content_plain() {
		ob_start();
		eshopbox_get_template( $this->template_plain, array(
			'order' 		=> $this->object,
			'email_heading' => $this->get_heading()
		) );
		return ob_get_clean();
	}


}