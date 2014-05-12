<?php
/**
 * Customer new account email
 *
 * @author 		WooThemes
 * @package 	EshopBox/Templates/Emails
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php do_action( 'eshopbox_email_header', $email_heading ); ?>

<p><?php printf(__("Thanks for creating an account on %s. Your username is <strong>%s</strong>.", 'eshopbox'), esc_html( $blogname ), esc_html( $user_login ) ); ?></p>

<p><?php printf(__( 'You can access your account area here: %s.', 'eshopbox' ), get_permalink(eshopbox_get_page_id('myaccount'))); ?></p>

<?php do_action( 'eshopbox_email_footer' ); ?>