<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * New Order Email
 *
 * An email sent to the admin when a new order is received/paid for.
 *
 * @class 		WC_Email_New_Order
 * @version		2.0.0
 * @package		EshopBox/Classes/Emails
 * @author 		WooThemes
 * @extends 	WC_Email
 */
class WC_Email_New_Order extends WC_Email {

	/**
	 * Constructor
	 */
	function __construct() {

		$this->id 				= 'new_order';
		$this->title 			= __( 'New order', 'eshopbox' );
		$this->description		= __( 'New order emails are sent when an order is received.', 'eshopbox' );

		$this->heading 			= __( 'New customer order', 'eshopbox' );
		$this->subject      	= __( '[{blogname}] New customer order ({order_number}) - {order_date}', 'eshopbox' );

		$this->template_html 	= 'emails/admin-new-order.php';
		$this->template_plain 	= 'emails/plain/admin-new-order.php';

		// Triggers for this email
		add_action( 'eshopbox_order_status_pending_to_processing_notification', array( $this, 'trigger' ) );
		add_action( 'eshopbox_order_status_pending_to_completed_notification', array( $this, 'trigger' ) );
		add_action( 'eshopbox_order_status_pending_to_on-hold_notification', array( $this, 'trigger' ) );
		add_action( 'eshopbox_order_status_failed_to_processing_notification', array( $this, 'trigger' ) );
		add_action( 'eshopbox_order_status_failed_to_completed_notification', array( $this, 'trigger' ) );
		add_action( 'eshopbox_order_status_failed_to_on-hold_notification', array( $this, 'trigger' ) );

		// Call parent constructor
		parent::__construct();

		// Other settings
		$this->recipient = $this->get_option( 'recipient' );

		if ( ! $this->recipient )
			$this->recipient = get_option( 'admin_email' );
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

    /**
     * Initialise Settings Form Fields
     *
     * @access public
     * @return void
     */
    function init_form_fields() {
    	$this->form_fields = array(
			'enabled' => array(
				'title' 		=> __( 'Enable/Disable', 'eshopbox' ),
				'type' 			=> 'checkbox',
				'label' 		=> __( 'Enable this email notification', 'eshopbox' ),
				'default' 		=> 'yes'
			),
			'recipient' => array(
				'title' 		=> __( 'Recipient(s)', 'eshopbox' ),
				'type' 			=> 'text',
				'description' 	=> sprintf( __( 'Enter recipients (comma separated) for this email. Defaults to <code>%s</code>.', 'eshopbox' ), esc_attr( get_option('admin_email') ) ),
				'placeholder' 	=> '',
				'default' 		=> ''
			),
			'subject' => array(
				'title' 		=> __( 'Subject', 'eshopbox' ),
				'type' 			=> 'text',
				'description' 	=> sprintf( __( 'This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', 'eshopbox' ), $this->subject ),
				'placeholder' 	=> '',
				'default' 		=> ''
			),
			'heading' => array(
				'title' 		=> __( 'Email Heading', 'eshopbox' ),
				'type' 			=> 'text',
				'description' 	=> sprintf( __( 'This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.', 'eshopbox' ), $this->heading ),
				'placeholder' 	=> '',
				'default' 		=> ''
			),
			'email_type' => array(
				'title' 		=> __( 'Email type', 'eshopbox' ),
				'type' 			=> 'select',
				'description' 	=> __( 'Choose which format of email to send.', 'eshopbox' ),
				'default' 		=> 'html',
				'class'			=> 'email_type',
				'options'		=> array(
					'plain'		 	=> __( 'Plain text', 'eshopbox' ),
					'html' 			=> __( 'HTML', 'eshopbox' ),
					'multipart' 	=> __( 'Multipart', 'eshopbox' ),
				)
			)
		);
    }
}
