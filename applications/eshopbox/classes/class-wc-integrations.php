<?php
/**
 * EshopBox Integrations class
 *
 * Loads Integrations into EshopBox.
 *
 * @class 		WC_Integrations
 * @version		2.0.0
 * @package		EshopBox/Classes/Integrations
 * @category	Class
 * @author 		WooThemes
 */
class WC_Integrations {

	/** @var array Array of integration classes */
	var $integrations = array();

    /**
     * __construct function.
     *
     * @access public
     * @return void
     */
    public function __construct() {

		do_action( 'eshopbox_integrations_init' );

		$load_integrations = apply_filters( 'eshopbox_integrations', array() );

		// Load integration classes
		foreach ( $load_integrations as $integration ) {

			$load_integration = new $integration();

			$this->integrations[$load_integration->id] = $load_integration;

		}

	}

	/**
	 * Return loaded integrations.
	 *
	 * @access public
	 * @return array
	 */
	public function get_integrations() {
		return $this->integrations;
	}
}