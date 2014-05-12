<?php
/**
 * Customer Reset Password email
 *
 * @author 		Eshopbox
 * @package 	 Eshop_email_guru/Templates/Emails
 * 
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php do_action('eshopbox_email_header', $email_heading); ?>

<p><?php _e( 'Someone requested that the password be reset for the following account:', 'eshopbox' ); ?></p>
<p><?php printf( __( 'Username: %s', 'eshopbox' ), $user_login ); ?></p>
<p><?php _e( 'If this was a mistake, just ignore this email and nothing will happen.', 'eshopbox' ); ?></p>
<p><?php _e( 'To reset your password, visit the following address:', 'eshopbox' ); ?></p>
<p>
    <a href="<?php echo esc_url( add_query_arg( array( 'key' => $reset_key, 'login' => rawurlencode( $user_login ) ), get_permalink( eshopbox_get_page_id( 'lost_password' ) ) ) ); ?>">
			<?php _e( 'Click here to reset your password', 'eshopbox' ); ?></a>
</p> 
<p></p>

<?php do_action('eshopbox_email_footer');?>