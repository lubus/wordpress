<?php
/**
 * Abondoned Order Email
 *
 * An email sent to the user when a abondoned order is created.
 *
 * @class 		WC_Email_Abondoned_Order
 * @version		1.2
 * @package		Eshop_email_guru/Classes/Emails
 * @author 		Shalu
 * @extends 	WC_Email
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class WC_Email_Abondoned_Order extends WC_Email
{

    /**
    * Constructor
    */
    function __construct()
    {


        $this->id 				= 'abondoned_order';
        $this->title 			= __( 'Abondoned Order', 'woocommerce' );
        $this->description		= __( 'Abondoned cart emails are sent to customer when a abondoned cart is created..', 'woocommerce' );
        $this->heading 			= __( 'Abondoned cart', 'woocommerce' );
        $this->subject      	= __( '[{blogname}] Abondoned Cart ({order_number}) - {order_date}', 'woocommerce' );
        $this->template_html 	= 'emails/admin-abondoned-order.php';
        $this->template_plain 	= 'emails/plain/admin-abondoned-order.php';

        // Call parent constructor
        parent::__construct();

        // Triggers for this email
        if($_SESSION['abondoned_order'] =='true')
        {
            $order_id=$_SESSION['orderid'];
            $this->trigger($order_id);
            unset($_SESSION['abondoned_order']);
            unset($_SESSION['orderid']);
        }

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
			$this->recipient	= $this->object->billing_email;
                        $customer_name=$this->object->billing_first_name.''.$this->object->billing_last_name;
                       
			$this->find[] = '{order_date}';
			$this->replace[] = date_i18n( woocommerce_date_format(), strtotime( $this->object->order_date ) );

			$this->find[] = '{order_number}';
			$this->replace[] = $this->object->get_order_number();
                        $this->find[] = '{customer_name}';
			$this->replace[] = $customer_name;
                        $this->find[] = '{shipping_amount}';
			$this->replace[] = $this->object->order_shipping;

		}
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
		woocommerce_get_template( $this->template_plain, array(
			'order' 		=> $this->object,
			'email_heading' => $this->get_heading()
		) );
		return ob_get_clean();
	}
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
				'title' 		=> __( 'Enable/Disable', 'woocommerce' ),
				'type' 			=> 'checkbox',
				'label' 		=> __( 'Enable this email notification', 'woocommerce' ),
				'default' 		=> 'yes'
			),
			'recipient' => array(
				'title' 		=> __( 'Recipient(s)', 'woocommerce' ),
				'type' 			=> 'text',
				'description' 	=> sprintf( __( 'Enter recipients (comma separated) for this email. Defaults to <code>%s</code>.', 'woocommerce' ), esc_attr( get_option('admin_email') ) ),
				'placeholder' 	=> '',
				'default' 		=> ''
			),
			'subject' => array(
				'title' 		=> __( 'Subject', 'woocommerce' ),
				'type' 			=> 'text',
				'description' 	=> sprintf( __( 'This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', 'woocommerce' ), $this->subject ),
				'placeholder' 	=> '',
				'default' 		=> ''
			),
			'heading' => array(
				'title' 		=> __( 'Email Heading', 'woocommerce' ),
				'type' 			=> 'text',
				'description' 	=> sprintf( __( 'This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.', 'woocommerce' ), $this->heading ),
				'placeholder' 	=> '',
				'default' 		=> ''
			),
			'email_type' => array(
				'title' 		=> __( 'Email type', 'woocommerce' ),
				'type' 			=> 'select',
				'description' 	=> __( 'Choose which format of email to send.', 'woocommerce' ),
				'default' 		=> 'html',
				'class'			=> 'email_type',
				'options'		=> array(
					'plain'		 	=> __( 'Plain text', 'woocommerce' ),
					'html' 			=> __( 'HTML', 'woocommerce' ),
					'multipart' 	=> __( 'Multipart', 'woocommerce' ),
				)
			)
		);
    
}
