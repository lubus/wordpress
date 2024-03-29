<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * EshopBox Integration class
 *
 * Extended by individual integrations to offer additional functionality.
 *
 * @class 		WC_Integration
 * @extends		WC_Settings_API
 * @version		2.0.0
 * @package		EshopBox/Abstracts
 * @category	Abstract Class
 * @author 		WooThemes
 */
abstract class WC_Integration extends WC_Settings_API {

	/**
	 * Admin Options
	 *
	 * Setup the gateway settings screen.
	 * Override this in your gateway.
	 *
	 * @access public
	 * @return void
	 */
	function admin_options() { ?>

		<h3><?php echo isset( $this->method_title ) ? $this->method_title : __( 'Settings', 'eshopbox' ) ; ?></h3>

		<?php echo isset( $this->method_description ) ? wpautop( $this->method_description ) : ''; ?>

		<table class="form-table">
			<?php $this->generate_settings_html(); ?>
		</table>

		<!-- Section -->
		<div><input type="hidden" name="section" value="<?php echo $this->id; ?>" /></div>

		<?php
	}

}