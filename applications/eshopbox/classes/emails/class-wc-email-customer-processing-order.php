<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Customer Processing Order Email
 *
 * An email sent to the admin when a new order is received/paid for.
 *
 * @class 		WC_Email_Customer_Processing_Order
 * @version		2.0.0
 * @package		EshopBox/Classes/Emails
 * @author 		WooThemes
 * @extends 	WC_Email
 */
class WC_Email_Customer_Processing_Order extends WC_Email {

	/**
	 * Constructor
	 */
	function __construct() {

		$this->id 				= 'customer_processing_order';
		$this->title 			= __( 'Processing order', 'eshopbox' );
		$this->description		= __( 'This is an order notification sent to the customer after payment containing order details.', 'eshopbox' );

		$this->heading 			= __( 'Thank you for your order', 'eshopbox' );
		$this->subject      	= __( 'Your {blogname} order receipt from {order_date}', 'eshopbox' );

		$this->template_html 	= 'emails/customer-processing-order.php';
		$this->template_plain 	= 'emails/plain/customer-processing-order.php';

		// Triggers for this email
		add_action( 'eshopbox_order_status_pending_to_processing_notification', array( $this, 'trigger' ) );
		add_action( 'eshopbox_order_status_pending_to_on-hold_notification', array( $this, 'trigger' ) );

		// Call parent constructor
		parent::__construct();
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
		eshopbox_get_template( $this->template_plain, array(
			'order' 		=> $this->object,
			'email_heading' => $this->get_heading()
		) );
		return ob_get_clean();
	}
}