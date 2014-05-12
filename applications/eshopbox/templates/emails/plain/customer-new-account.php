<?php
/**
 * Customer new account email
 *
 * @author 		WooThemes
 * @package 	EshopBox/Templates/Emails/Plain
 * @version     2.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

echo $email_heading . "\n\n";

echo sprintf( __( "Thanks for creating an account on %s. Your username is <strong>%s</strong>.", 'eshopbox' ), $blogname, $user_login ) . "\n\n";

echo sprintf(__( 'You can access your account area here: %s.', 'eshopbox' ), get_permalink( eshopbox_get_page_id( 'myaccount' ) ) ) . "\n\n";

echo "\n****************************************************\n\n";

echo apply_filters( 'eshopbox_email_footer_text', get_option( 'eshopbox_email_footer_text' ) );