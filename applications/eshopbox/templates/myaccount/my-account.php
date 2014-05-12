<?php
/**
 * My Account page
 *
 * @author 		WooThemes
 * @package 	EshopBox/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $eshopbox;

$eshopbox->show_messages(); ?>

<p class="myaccount_user">
	<?php
	printf(
		__( 'Hello, <strong>%s</strong>. From your account dashboard you can view your recent orders, manage your shipping and billing addresses and <a href="%s">change your password</a>.', 'eshopbox' ),
		$current_user->display_name,
		get_permalink( eshopbox_get_page_id( 'change_password' ) )
	);
	?>
</p>

<?php do_action( 'eshopbox_before_my_account' ); ?>

<?php eshopbox_get_template( 'myaccount/my-downloads.php' ); ?>

<?php eshopbox_get_template( 'myaccount/my-orders.php', array( 'order_count' => $order_count ) ); ?>

<?php eshopbox_get_template( 'myaccount/my-address.php' ); ?>

<?php do_action( 'eshopbox_after_my_account' ); ?>