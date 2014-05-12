<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div id="message" class="updated eshopbox-message wc-connect">
	<div class="squeezer">
		<h4><?php _e( '<strong>Welcome to EshopBox</strong> &#8211; You\'re almost ready to start selling :)', 'eshopbox' ); ?></h4>
		<p class="submit"><a href="<?php echo add_query_arg('install_eshopbox_pages', 'true', admin_url('admin.php?page=eshopbox_settings') ); ?>" class="button-primary"><?php _e( 'Install EshopBox Pages', 'eshopbox' ); ?></a> <a class="skip button-primary" href="<?php echo add_query_arg('skip_install_eshopbox_pages', 'true', admin_url('admin.php?page=eshopbox_settings') ); ?>"><?php _e( 'Skip setup', 'eshopbox' ); ?></a></p>
	</div>
</div>