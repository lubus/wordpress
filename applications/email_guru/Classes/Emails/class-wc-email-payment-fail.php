<?php

/**
 * Customer Order failed  Email(when payment gets failed)
 *
 * An email sent to the user  when payment gets fail for an order due to some reason.
 *
 * @class 		WC_Email_Order_Cancellation
 * @version		1.2
 * @package		Eshop_email_guru/Classes/Emails
 * @author 		Shalu
 * @extends 	WC_Email
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WC_Email_Order_Fail extends WC_Email {

	/**
	 * Constructor
	 */
	function __construct() {

		$this->id 				= 'payment_fail';
		$this->title 			= __( 'Payment fail', 'eshopbox' );
		$this->description		= __( 'This is an order notification sent to the customer when order get cancelled due to some reason.', 'eshopbox' );

		$this->heading 			= __( 'Your order has been cancelled', 'eshopbox' );
		$this->subject      	= __( 'Your order has been cancelled', 'eshopbox' );              

		$this->template_html 	= 'emails/order-fail.php';
		$this->template_plain 	= 'emails/plain/order-fail.php';

                // Call parent constructor
		parent::__construct();

		// Triggers for this email		
                if($_SESSION['fail']=='true')
                { 
                    $order_id=$_SESSION['orderid'];
                    $this->trigger($order_id);
                    unset($_SESSION['fail']);
                    unset($_SESSION['orderid']);
                }
	}

	/**
	 * trigger function.
	 *
	 * @access public
	 * @return void
	 */
	function trigger( $order_id ) {
		global $eshopbox;

		if ( $order_id ) {//echo"helllo";
			$this->object 		= new WC_Order( $order_id );
			$this->recipient	= $this->object->billing_email;
                        $customer_name=$this->object->billing_first_name.''.$this->object->billing_last_name;
                        
			$this->find[] = '{order_date}';
			$this->replace[] = date_i18n(eshopbox_date_format(), strtotime( $this->object->order_date ) );

			$this->find[] = '{order_number}';
			$this->replace[] = $this->object->get_order_number();
                        $this->find[] = '{customer_name}';
			$this->replace[] = $customer_name;
                        $this->find[] = '{shipping_amount}';
			$this->replace[] = $this->object->order_shipping;

		}
//echo $this->get_content();exit;
                //if ( ! $this->get_recipient() )
		if ( ! $this->is_enabled() || ! $this->get_recipient() )                
			return;

		$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
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
		eshopboxe_get_template( $this->template_plain, array(
			'order' 		=> $this->object,
			'email_heading' => $this->get_heading()
		) );
		return ob_get_clean();
	}
}