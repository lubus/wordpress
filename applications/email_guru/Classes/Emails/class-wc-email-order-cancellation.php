<?php
/**
 * Order Cancellation Email
 *
 * An email sent to the admin when an order is cancelled.
 *
 * @class 		WC_Email_Order_Cancellation
 * @version		1.2 
 * @package		Eshop_email_guru/Classes/Emails
 * @author 		Shalu
 * @extends 	WC_Email
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class WC_Email_Order_Cancellation extends WC_Email {

	/**
	 * Constructor
	 */
	function __construct() {           
		$this->id 				= 'order_cancellation';
		$this->title 			= __( 'Order cancellation', 'eshopbox' );
		$this->description		= __( 'This is an order notification sent to the customer when order get cancelled due to some reason.', 'eshopbox' );

		$this->heading 			= __( 'Your Live Bruno order has been cancelled', 'eshopbox' );
		$this->subject      	= __( 'Your Live Bruno order has been cancelled', 'eshopbox' );
		$this->template_html 	= 'emails/order-cancellation.php';
		$this->template_plain 	= 'emails/plain/order-cancellation.php';
              
                // Call parent constructor
                parent::__construct();

		// Triggers for this email
               if($_SESSION['cancelled']=='true')
               {
                    $order_id=$_SESSION['orderid'];
                    $this->trigger($order_id);
                    unset($_SESSION['cancelled']);
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
                        $customer_name=$this->object->billing_first_name.' '.$this->object->billing_last_name;
			$this->find[] = '{order_date}';
			$this->replace[] = date_i18n( eshopbox_date_format(), strtotime( $this->object->order_date ) );

			$this->find[] = '{order_number}';
			$this->replace[] = $this->object->get_order_number();
                        $this->find[] = '{customer_name}';
			$this->replace[] = $customer_name;
                        $this->find[] = '{shipping_amount}';
			$this->replace[] = $this->object->order_shipping;

		}
		//echo $this->get_content(); exit;
               
		if ( ! $this->is_enabled() || ! $this->get_recipient() )
			return;
                
		$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
	
        }

	/**
	 * get_subject function.
	 *
	 * @access public
	 * @return string
	 */
	function get_subject() {
		if ( ! empty( $this->object ) && $this->object->has_downloadable_item() )
			return apply_filters( 'eshopbox_email_subject_customer_completed_order', $this->format_string( $this->subject_downloadable ), $this->object );
		else
			return apply_filters( 'eshopbox_email_subject_customer_completed_order', $this->format_string( $this->subject ), $this->object );
	}

	/**
	 * get_heading function.
	 *
	 * @access public
	 * @return string
	 */
	function get_heading() {
		if ( ! empty( $this->object ) && $this->object->has_downloadable_item() )
			return apply_filters( 'eshopbox_email_heading_customer_completed_order', $this->format_string( $this->heading_downloadable ), $this->object );
		else
			return apply_filters( 'eshopbox_email_heading_customer_completed_order', $this->format_string( $this->heading ), $this->object );
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
			'subject' => array(
				'title' 		=> __( 'Subject', 'eshopbox' ),
				'type' 			=> 'text',
				'description' 	=> sprintf( __( 'Defaults to <code>%s</code>', 'eshopbox' ), $this->subject ),
				'placeholder' 	=> '',
				'default' 		=> ''
			),
			'heading' => array(
				'title' 		=> __( 'Email Heading', 'eshopbox' ),
				'type' 			=> 'text',
				'description' 	=> sprintf( __( 'Defaults to <code>%s</code>', 'eshopbox' ), $this->heading ),
				'placeholder' 	=> '',
				'default' 		=> ''
			),
			'subject_downloadable' => array(
				'title' 		=> __( 'Subject (downloadable)', 'eshopbox' ),
				'type' 			=> 'text',
				'description' 	=> sprintf( __( 'Defaults to <code>%s</code>', 'eshopbox' ), $this->subject_downloadable ),
				'placeholder' 	=> '',
				'default' 		=> ''
			),
			'heading_downloadable' => array(
				'title' 		=> __( 'Email Heading (downloadable)', 'eshopbox' ),
				'type' 			=> 'text',
				'description' 	=> sprintf( __( 'Defaults to <code>%s</code>', 'eshopbox' ), $this->heading_downloadable ),
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
					'plain'	 	=> __( 'Plain text', 'eshopbox' ),
					'html' 			=> __( 'HTML', 'eshopbox' ),
					'multipart' 	=> __( 'Multipart', 'eshopbox' ),
				)
			)
		);
    }
}