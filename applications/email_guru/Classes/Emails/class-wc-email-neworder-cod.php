<?php
/**
 * Customer New Order Cod Email
 *
 * An email sent to the USER when a new order is received/paid for in case of cod.
 *
 * @class 		WC_Email_Customer_New_Order_Cod
 * @version		1.2
 * @package		Eshop_email_guru/Classes/Emails
 * @author 		Shalu
 * @extends 	WC_Email
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class WC_Email_Customer_New_Order_Cod extends WC_Email {

	/**
	 * Constructor
	 */
	function __construct() {

		$this->id 				= 'new_order_cod';
		$this->title 			= __( 'New Order Cod', 'eshopbox' );
		$this->description		= __( 'This is an order notification sent to the customer after order is placed via COD mode', 'eshopbox' );

		$this->heading 			= __( 'Thank you for your order', 'eshopbox' );
		$this->subject      	= __( 'Your {blogname} order receipt from {order_date}', 'eshopbox' );

		$this->template_html 	= 'emails/new-order-cod.php';
		$this->template_plain 	= 'emails/plain/new-order-cod.php';

		// Call parent constructor
		parent::__construct();

		// Triggers for this email
		//add_action( 'eshopbox_order_status_pending_to_processing_notification', array( $this, 'trigger' ) );
		add_action( 'eshopbox_order_status_pending_to_on-hold_notification', array( $this, 'trigger' ) );

		
	}

	/**
	 * trigger function.
	 *
	 * @access public
	 * @return void
	 */
	function trigger( $order_id ) {
		global $eshopbox;
 
		if ( $order_id ) {
                   
			$this->object 		= new WC_Order( $order_id );
			$this->recipient	= $this->object->billing_email;

			$this->find[] = '{order_date}';
			$this->replace[] = date_i18n( eshopbox_date_format(), strtotime( $this->object->order_date ) );

			$this->find[] = '{order_number}';
			$this->replace[] = $this->object->get_order_number();
		}

        //echo $this->get_content(); exit;       
                    if ( ! $this->get_recipient() )
			return;
		$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
                $this->send( 'orders@eshopbox.com', $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );

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
