<?php
/**
 * Lost password form
 *
 * @author 		WooThemes
 * @package 	EshopBox/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $eshopbox, $post;

?>

<?php $eshopbox->show_messages(); ?>

<form action="<?php echo esc_url( get_permalink($post->ID) ); ?>" method="post" class="lost_reset_password">

	<?php	if( 'lost_password' == $args['form'] ) : ?>

    <p><?php echo apply_filters( 'eshopbox_lost_password_message', __( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'eshopbox' ) ); ?></p>

    <p class="form-row form-row-first"><label for="user_login"><?php _e( 'Username or email', 'eshopbox' ); ?></label> <input class="input-text" type="text" name="user_login" id="user_login" /></p>

	<?php else : ?>

    <p><?php echo apply_filters( 'eshopbox_reset_password_message', __( 'Enter a new password below.', 'eshopbox') ); ?></p>

    <p class="form-row form-row-first">
        <label for="password_1"><?php _e( 'New password', 'eshopbox' ); ?> <span class="required">*</span></label>
        <input type="password" class="input-text" name="password_1" id="password_1" />
    </p>
    <p class="form-row form-row-last">
        <label for="password_2"><?php _e( 'Re-enter new password', 'eshopbox' ); ?> <span class="required">*</span></label>
        <input type="password" class="input-text" name="password_2" id="password_2" />
    </p>

    <input type="hidden" name="reset_key" value="<?php echo isset( $args['key'] ) ? $args['key'] : ''; ?>" />
    <input type="hidden" name="reset_login" value="<?php echo isset( $args['login'] ) ? $args['login'] : ''; ?>" />
	<?php endif; ?>

    <div class="clear"></div>

    <p class="form-row"><input type="submit" class="button" name="reset" value="<?php echo 'lost_password' == $args['form'] ? __( 'Reset Password', 'eshopbox' ) : __( 'Save', 'eshopbox' ); ?>" /></p>
	<?php $eshopbox->nonce_field( $args['form'] ); ?>

</form>