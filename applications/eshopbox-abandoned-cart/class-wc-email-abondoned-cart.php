<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Abondoned Cart Email
 *
 * An email sent to the admin and user when a abondoned cart is created.
 *
 * @class 		WC_Email_Abondoned_Cart
 * @version		2.0.0
 * @package		Eshopbox/Classes/Emails
 * @author 		WooThemes
 * @extends 	WC_Email
 */
class WC_Email_Abondoned_Cart extends WC_Email {

	/**
	 * Constructor
	 */
	function __construct() {
                
		$this->id 				= 'abondoned_cart';
		$this->title 			= __( 'Abondoned cart', 'eshopbox' );
		$this->description		= __( 'Abondoned cart emails are sent to customer when a abondoned cart is created..', 'eshopbox' );

		$this->heading 			= __( 'Abondoned cart', 'eshopbox' );
		$this->subject      	= __( '[{blogname}] Abondoned Cart ({order_number}) - {order_date}', 'eshopbox' );
                //$this->template_base =  MYPLUGIN_PATH;
               //echo  $this->template_base;exit;
               
		$this->template_html 	= 'emails/admin-abondoned-cart.php';
		$this->template_plain 	= 'emails/plain/admin-abondoned-cart.php';
                parent::__construct();
		// Triggers for this email
		
             
               if($_SESSION['abondoned_cart'] =='true'){

                   $user_id=$_SESSION['userid'];
                    
                   $this->trigger($user_id);
                
              
               }
		// Call parent constructor
		

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
	function trigger( $user_id) {
            
             
		global $eshopbox;             
		if ( $user_id ) {
			$this->object 		= new WP_User( $user_id );
			$this->user_pass	= $user_pass;
			$this->user_login 	= stripslashes( $this->object->user_login );
			$this->user_email 	= stripslashes( $this->object->user_email );
			$this->recipient	= $this->user_email;
		}
                if ( ! $this->is_enabled() || ! $this->get_recipient() )
                {
                            return;
                }

          
		//$this->send('ashislubumohanty@gmail.com', $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
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
			'email_heading' => $this->get_heading(),
			'user_login' 	=> $this->user_login,
			'user_pass'		=> $this->user_pass,
			'blogname'		=> $this->get_blogname()
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
			'email_heading' => $this->get_heading(),
			'user_login' 	=> $this->user_login,
			'user_pass'		=> $this->user_pass,
			'blogname'		=> $this->get_blogname()
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