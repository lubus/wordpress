<?php
/**
 * EshopBox countries
 *
 * The EshopBox countries class stores country/state data.
 *
 * @class 		WC_Countries
 * @version		1.6.4
 * @package		EshopBox/Classes
 * @category	Class
 * @author 		WooThemes
 */
class WC_Countries {

	/** @var array Array of countries */
	public $countries;

	/** @var array Array of states */
	public $states;

	/** @var array Array of locales */
	public $locale;

	/** @var array Array of address formats for locales */
	public $address_formats;

	/**
	 * Constructor for the counties class - defines all countries and states.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		global $eshopbox, $states;

		$this->countries = apply_filters('eshopbox_countries', array(
			'AF' => __( 'Afghanistan', 'eshopbox' ),
			'AX' => __( '&#197;land Islands', 'eshopbox' ),
			'AL' => __( 'Albania', 'eshopbox' ),
			'DZ' => __( 'Algeria', 'eshopbox' ),
			'AD' => __( 'Andorra', 'eshopbox' ),
			'AO' => __( 'Angola', 'eshopbox' ),
			'AI' => __( 'Anguilla', 'eshopbox' ),
			'AQ' => __( 'Antarctica', 'eshopbox' ),
			'AG' => __( 'Antigua and Barbuda', 'eshopbox' ),
			'AR' => __( 'Argentina', 'eshopbox' ),
			'AM' => __( 'Armenia', 'eshopbox' ),
			'AW' => __( 'Aruba', 'eshopbox' ),
			'AU' => __( 'Australia', 'eshopbox' ),
			'AT' => __( 'Austria', 'eshopbox' ),
			'AZ' => __( 'Azerbaijan', 'eshopbox' ),
			'BS' => __( 'Bahamas', 'eshopbox' ),
			'BH' => __( 'Bahrain', 'eshopbox' ),
			'BD' => __( 'Bangladesh', 'eshopbox' ),
			'BB' => __( 'Barbados', 'eshopbox' ),
			'BY' => __( 'Belarus', 'eshopbox' ),
			'BE' => __( 'Belgium', 'eshopbox' ),
			'PW' => __( 'Belau', 'eshopbox' ),
			'BZ' => __( 'Belize', 'eshopbox' ),
			'BJ' => __( 'Benin', 'eshopbox' ),
			'BM' => __( 'Bermuda', 'eshopbox' ),
			'BT' => __( 'Bhutan', 'eshopbox' ),
			'BO' => __( 'Bolivia', 'eshopbox' ),
			'BQ' => __( 'Bonaire, Saint Eustatius and Saba', 'eshopbox' ),
			'BA' => __( 'Bosnia and Herzegovina', 'eshopbox' ),
			'BW' => __( 'Botswana', 'eshopbox' ),
			'BV' => __( 'Bouvet Island', 'eshopbox' ),
			'BR' => __( 'Brazil', 'eshopbox' ),
			'IO' => __( 'British Indian Ocean Territory', 'eshopbox' ),
			'VG' => __( 'British Virgin Islands', 'eshopbox' ),
			'BN' => __( 'Brunei', 'eshopbox' ),
			'BG' => __( 'Bulgaria', 'eshopbox' ),
			'BF' => __( 'Burkina Faso', 'eshopbox' ),
			'BI' => __( 'Burundi', 'eshopbox' ),
			'KH' => __( 'Cambodia', 'eshopbox' ),
			'CM' => __( 'Cameroon', 'eshopbox' ),
			'CA' => __( 'Canada', 'eshopbox' ),
			'CV' => __( 'Cape Verde', 'eshopbox' ),
			'KY' => __( 'Cayman Islands', 'eshopbox' ),
			'CF' => __( 'Central African Republic', 'eshopbox' ),
			'TD' => __( 'Chad', 'eshopbox' ),
			'CL' => __( 'Chile', 'eshopbox' ),
			'CN' => __( 'China', 'eshopbox' ),
			'CX' => __( 'Christmas Island', 'eshopbox' ),
			'CC' => __( 'Cocos (Keeling) Islands', 'eshopbox' ),
			'CO' => __( 'Colombia', 'eshopbox' ),
			'KM' => __( 'Comoros', 'eshopbox' ),
			'CG' => __( 'Congo (Brazzaville)', 'eshopbox' ),
			'CD' => __( 'Congo (Kinshasa)', 'eshopbox' ),
			'CK' => __( 'Cook Islands', 'eshopbox' ),
			'CR' => __( 'Costa Rica', 'eshopbox' ),
			'HR' => __( 'Croatia', 'eshopbox' ),
			'CU' => __( 'Cuba', 'eshopbox' ),
			'CW' => __( 'Cura&Ccedil;ao', 'eshopbox' ),
			'CY' => __( 'Cyprus', 'eshopbox' ),
			'CZ' => __( 'Czech Republic', 'eshopbox' ),
			'DK' => __( 'Denmark', 'eshopbox' ),
			'DJ' => __( 'Djibouti', 'eshopbox' ),
			'DM' => __( 'Dominica', 'eshopbox' ),
			'DO' => __( 'Dominican Republic', 'eshopbox' ),
			'EC' => __( 'Ecuador', 'eshopbox' ),
			'EG' => __( 'Egypt', 'eshopbox' ),
			'SV' => __( 'El Salvador', 'eshopbox' ),
			'GQ' => __( 'Equatorial Guinea', 'eshopbox' ),
			'ER' => __( 'Eritrea', 'eshopbox' ),
			'EE' => __( 'Estonia', 'eshopbox' ),
			'ET' => __( 'Ethiopia', 'eshopbox' ),
			'FK' => __( 'Falkland Islands', 'eshopbox' ),
			'FO' => __( 'Faroe Islands', 'eshopbox' ),
			'FJ' => __( 'Fiji', 'eshopbox' ),
			'FI' => __( 'Finland', 'eshopbox' ),
			'FR' => __( 'France', 'eshopbox' ),
			'GF' => __( 'French Guiana', 'eshopbox' ),
			'PF' => __( 'French Polynesia', 'eshopbox' ),
			'TF' => __( 'French Southern Territories', 'eshopbox' ),
			'GA' => __( 'Gabon', 'eshopbox' ),
			'GM' => __( 'Gambia', 'eshopbox' ),
			'GE' => __( 'Georgia', 'eshopbox' ),
			'DE' => __( 'Germany', 'eshopbox' ),
			'GH' => __( 'Ghana', 'eshopbox' ),
			'GI' => __( 'Gibraltar', 'eshopbox' ),
			'GR' => __( 'Greece', 'eshopbox' ),
			'GL' => __( 'Greenland', 'eshopbox' ),
			'GD' => __( 'Grenada', 'eshopbox' ),
			'GP' => __( 'Guadeloupe', 'eshopbox' ),
			'GT' => __( 'Guatemala', 'eshopbox' ),
			'GG' => __( 'Guernsey', 'eshopbox' ),
			'GN' => __( 'Guinea', 'eshopbox' ),
			'GW' => __( 'Guinea-Bissau', 'eshopbox' ),
			'GY' => __( 'Guyana', 'eshopbox' ),
			'HT' => __( 'Haiti', 'eshopbox' ),
			'HM' => __( 'Heard Island and McDonald Islands', 'eshopbox' ),
			'HN' => __( 'Honduras', 'eshopbox' ),
			'HK' => __( 'Hong Kong', 'eshopbox' ),
			'HU' => __( 'Hungary', 'eshopbox' ),
			'IS' => __( 'Iceland', 'eshopbox' ),
			'IN' => __( 'India', 'eshopbox' ),
			'ID' => __( 'Indonesia', 'eshopbox' ),
			'IR' => __( 'Iran', 'eshopbox' ),
			'IQ' => __( 'Iraq', 'eshopbox' ),
			'IE' => __( 'Republic of Ireland', 'eshopbox' ),
			'IM' => __( 'Isle of Man', 'eshopbox' ),
			'IL' => __( 'Israel', 'eshopbox' ),
			'IT' => __( 'Italy', 'eshopbox' ),
			'CI' => __( 'Ivory Coast', 'eshopbox' ),
			'JM' => __( 'Jamaica', 'eshopbox' ),
			'JP' => __( 'Japan', 'eshopbox' ),
			'JE' => __( 'Jersey', 'eshopbox' ),
			'JO' => __( 'Jordan', 'eshopbox' ),
			'KZ' => __( 'Kazakhstan', 'eshopbox' ),
			'KE' => __( 'Kenya', 'eshopbox' ),
			'KI' => __( 'Kiribati', 'eshopbox' ),
			'KW' => __( 'Kuwait', 'eshopbox' ),
			'KG' => __( 'Kyrgyzstan', 'eshopbox' ),
			'LA' => __( 'Laos', 'eshopbox' ),
			'LV' => __( 'Latvia', 'eshopbox' ),
			'LB' => __( 'Lebanon', 'eshopbox' ),
			'LS' => __( 'Lesotho', 'eshopbox' ),
			'LR' => __( 'Liberia', 'eshopbox' ),
			'LY' => __( 'Libya', 'eshopbox' ),
			'LI' => __( 'Liechtenstein', 'eshopbox' ),
			'LT' => __( 'Lithuania', 'eshopbox' ),
			'LU' => __( 'Luxembourg', 'eshopbox' ),
			'MO' => __( 'Macao S.A.R., China', 'eshopbox' ),
			'MK' => __( 'Macedonia', 'eshopbox' ),
			'MG' => __( 'Madagascar', 'eshopbox' ),
			'MW' => __( 'Malawi', 'eshopbox' ),
			'MY' => __( 'Malaysia', 'eshopbox' ),
			'MV' => __( 'Maldives', 'eshopbox' ),
			'ML' => __( 'Mali', 'eshopbox' ),
			'MT' => __( 'Malta', 'eshopbox' ),
			'MH' => __( 'Marshall Islands', 'eshopbox' ),
			'MQ' => __( 'Martinique', 'eshopbox' ),
			'MR' => __( 'Mauritania', 'eshopbox' ),
			'MU' => __( 'Mauritius', 'eshopbox' ),
			'YT' => __( 'Mayotte', 'eshopbox' ),
			'MX' => __( 'Mexico', 'eshopbox' ),
			'FM' => __( 'Micronesia', 'eshopbox' ),
			'MD' => __( 'Moldova', 'eshopbox' ),
			'MC' => __( 'Monaco', 'eshopbox' ),
			'MN' => __( 'Mongolia', 'eshopbox' ),
			'ME' => __( 'Montenegro', 'eshopbox' ),
			'MS' => __( 'Montserrat', 'eshopbox' ),
			'MA' => __( 'Morocco', 'eshopbox' ),
			'MZ' => __( 'Mozambique', 'eshopbox' ),
			'MM' => __( 'Myanmar', 'eshopbox' ),
			'NA' => __( 'Namibia', 'eshopbox' ),
			'NR' => __( 'Nauru', 'eshopbox' ),
			'NP' => __( 'Nepal', 'eshopbox' ),
			'NL' => __( 'Netherlands', 'eshopbox' ),
			'AN' => __( 'Netherlands Antilles', 'eshopbox' ),
			'NC' => __( 'New Caledonia', 'eshopbox' ),
			'NZ' => __( 'New Zealand', 'eshopbox' ),
			'NI' => __( 'Nicaragua', 'eshopbox' ),
			'NE' => __( 'Niger', 'eshopbox' ),
			'NG' => __( 'Nigeria', 'eshopbox' ),
			'NU' => __( 'Niue', 'eshopbox' ),
			'NF' => __( 'Norfolk Island', 'eshopbox' ),
			'KP' => __( 'North Korea', 'eshopbox' ),
			'NO' => __( 'Norway', 'eshopbox' ),
			'OM' => __( 'Oman', 'eshopbox' ),
			'PK' => __( 'Pakistan', 'eshopbox' ),
			'PS' => __( 'Palestinian Territory', 'eshopbox' ),
			'PA' => __( 'Panama', 'eshopbox' ),
			'PG' => __( 'Papua New Guinea', 'eshopbox' ),
			'PY' => __( 'Paraguay', 'eshopbox' ),
			'PE' => __( 'Peru', 'eshopbox' ),
			'PH' => __( 'Philippines', 'eshopbox' ),
			'PN' => __( 'Pitcairn', 'eshopbox' ),
			'PL' => __( 'Poland', 'eshopbox' ),
			'PT' => __( 'Portugal', 'eshopbox' ),
			'QA' => __( 'Qatar', 'eshopbox' ),
			'RE' => __( 'Reunion', 'eshopbox' ),
			'RO' => __( 'Romania', 'eshopbox' ),
			'RU' => __( 'Russia', 'eshopbox' ),
			'RW' => __( 'Rwanda', 'eshopbox' ),
			'BL' => __( 'Saint Barth&eacute;lemy', 'eshopbox' ),
			'SH' => __( 'Saint Helena', 'eshopbox' ),
			'KN' => __( 'Saint Kitts and Nevis', 'eshopbox' ),
			'LC' => __( 'Saint Lucia', 'eshopbox' ),
			'MF' => __( 'Saint Martin (French part)', 'eshopbox' ),
			'SX' => __( 'Saint Martin (Dutch part)', 'eshopbox' ),
			'PM' => __( 'Saint Pierre and Miquelon', 'eshopbox' ),
			'VC' => __( 'Saint Vincent and the Grenadines', 'eshopbox' ),
			'SM' => __( 'San Marino', 'eshopbox' ),
			'ST' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe', 'eshopbox' ),
			'SA' => __( 'Saudi Arabia', 'eshopbox' ),
			'SN' => __( 'Senegal', 'eshopbox' ),
			'RS' => __( 'Serbia', 'eshopbox' ),
			'SC' => __( 'Seychelles', 'eshopbox' ),
			'SL' => __( 'Sierra Leone', 'eshopbox' ),
			'SG' => __( 'Singapore', 'eshopbox' ),
			'SK' => __( 'Slovakia', 'eshopbox' ),
			'SI' => __( 'Slovenia', 'eshopbox' ),
			'SB' => __( 'Solomon Islands', 'eshopbox' ),
			'SO' => __( 'Somalia', 'eshopbox' ),
			'ZA' => __( 'South Africa', 'eshopbox' ),
			'GS' => __( 'South Georgia/Sandwich Islands', 'eshopbox' ),
			'KR' => __( 'South Korea', 'eshopbox' ),
			'SS' => __( 'South Sudan', 'eshopbox' ),
			'ES' => __( 'Spain', 'eshopbox' ),
			'LK' => __( 'Sri Lanka', 'eshopbox' ),
			'SD' => __( 'Sudan', 'eshopbox' ),
			'SR' => __( 'Suriname', 'eshopbox' ),
			'SJ' => __( 'Svalbard and Jan Mayen', 'eshopbox' ),
			'SZ' => __( 'Swaziland', 'eshopbox' ),
			'SE' => __( 'Sweden', 'eshopbox' ),
			'CH' => __( 'Switzerland', 'eshopbox' ),
			'SY' => __( 'Syria', 'eshopbox' ),
			'TW' => __( 'Taiwan', 'eshopbox' ),
			'TJ' => __( 'Tajikistan', 'eshopbox' ),
			'TZ' => __( 'Tanzania', 'eshopbox' ),
			'TH' => __( 'Thailand', 'eshopbox' ),
			'TL' => __( 'Timor-Leste', 'eshopbox' ),
			'TG' => __( 'Togo', 'eshopbox' ),
			'TK' => __( 'Tokelau', 'eshopbox' ),
			'TO' => __( 'Tonga', 'eshopbox' ),
			'TT' => __( 'Trinidad and Tobago', 'eshopbox' ),
			'TN' => __( 'Tunisia', 'eshopbox' ),
			'TR' => __( 'Turkey', 'eshopbox' ),
			'TM' => __( 'Turkmenistan', 'eshopbox' ),
			'TC' => __( 'Turks and Caicos Islands', 'eshopbox' ),
			'TV' => __( 'Tuvalu', 'eshopbox' ),
			'UG' => __( 'Uganda', 'eshopbox' ),
			'UA' => __( 'Ukraine', 'eshopbox' ),
			'AE' => __( 'United Arab Emirates', 'eshopbox' ),
			'GB' => __( 'United Kingdom', 'eshopbox' ),
			'US' => __( 'United States', 'eshopbox' ),
			'UY' => __( 'Uruguay', 'eshopbox' ),
			'UZ' => __( 'Uzbekistan', 'eshopbox' ),
			'VU' => __( 'Vanuatu', 'eshopbox' ),
			'VA' => __( 'Vatican', 'eshopbox' ),
			'VE' => __( 'Venezuela', 'eshopbox' ),
			'VN' => __( 'Vietnam', 'eshopbox' ),
			'WF' => __( 'Wallis and Futuna', 'eshopbox' ),
			'EH' => __( 'Western Sahara', 'eshopbox' ),
			'WS' => __( 'Western Samoa', 'eshopbox' ),
			'YE' => __( 'Yemen', 'eshopbox' ),
			'ZM' => __( 'Zambia', 'eshopbox' ),
			'ZW' => __( 'Zimbabwe', 'eshopbox' )
		));

		// States set to array() are blank i.e. the country has no use for the state field.
		$states = array(
			'AF' => array(),
			'AT' => array(),
			'BE' => array(),
			'BI' => array(),
			'CZ' => array(),
			'DE' => array(),
			'DK' => array(),
			'FI' => array(),
			'FR' => array(),
			'HU' => array(),
			'IS' => array(),
			'IL' => array(),
			'KR' => array(),
			'NL' => array(),
			'NO' => array(),
			'PL' => array(),
			'PT' => array(),
			'SG' => array(),
			'SK' => array(),
			'SI' => array(),
			'LK' => array(),
			'SE' => array(),
			'VN' => array(),
		);

		// Load only the state files the shop owner wants/needs
		$allowed = $this->get_allowed_countries();

		if ( $allowed )
			foreach ( $allowed as $CC => $country )
				if ( ! isset( $states[ $CC ] ) && file_exists( $eshopbox->plugin_path() . '/i18n/states/' . $CC . '.php' ) )
					include( $eshopbox->plugin_path() . '/i18n/states/' . $CC . '.php' );

		$this->states = apply_filters('eshopbox_states', $states );
	}


	/**
	 * Get the base country for the store.
	 *
	 * @access public
	 * @return string
	 */
	public function get_base_country() {
		$default = esc_attr( get_option('eshopbox_default_country') );
		if ( ( $pos = strpos( $default, ':' ) ) === false )
			return $default;
		return substr( $default, 0, $pos );
	}


	/**
	 * Get the base state for the state.
	 *
	 * @access public
	 * @return string
	 */
	public function get_base_state() {
		$default = esc_attr( get_option( 'eshopbox_default_country' ) );
		if ( ( $pos = strrpos( $default, ':' ) ) === false )
			return '';
		return substr( $default, $pos + 1 );
	}


	/**
	 * Get the allowed countries for the store.
	 *
	 * @access public
	 * @return array
	 */
	public function get_allowed_countries() {

		if ( apply_filters('eshopbox_sort_countries', true ) )
			asort( $this->countries );

		if ( get_option('eshopbox_allowed_countries') !== 'specific' )
			return $this->countries;

		$allowed_countries = array();

		$allowed_countries_raw = get_option( 'eshopbox_specific_allowed_countries' );

		foreach ( $allowed_countries_raw as $country )
			$allowed_countries[ $country ] = $this->countries[ $country ];

		return apply_filters( 'eshopbox_countries_allowed_countries', $allowed_countries );
	}


	/**
	 * get_allowed_country_states function.
	 *
	 * @access public
	 * @return array
	 */
	public function get_allowed_country_states() {

		if ( get_option('eshopbox_allowed_countries') !== 'specific' )
			return $this->states;

		$allowed_states = array();

		$allowed_countries_raw = get_option( 'eshopbox_specific_allowed_countries' );

		foreach ( $allowed_countries_raw as $country )
			if ( ! empty( $this->states[ $country ] ) )
				$allowed_states[ $country ] = $this->states[ $country ];

		return apply_filters( 'eshopbox_countries_allowed_country_states', $allowed_states );
	}


	/**
	 * Gets an array of countries in the EU.
	 *
	 * @access public
	 * @return array
	 */
	public function get_european_union_countries() {
		return array( 'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GB', 'GR', 'HU', 'HR', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PL', 'PT', 'RO', 'SE', 'SI', 'SK' );
	}


	/**
	 * Gets the correct string for shipping - ether 'to the' or 'to'
	 *
	 * @access public
	 * @return string
	 */
	public function shipping_to_prefix() {
		global $eshopbox;
		$return = '';
		if (in_array($eshopbox->customer->get_shipping_country(), array( 'GB', 'US', 'AE', 'CZ', 'DO', 'NL', 'PH', 'USAF' ))) $return = __( 'to the', 'eshopbox' );
		else $return = __( 'to', 'eshopbox' );
		return apply_filters('eshopbox_countries_shipping_to_prefix', $return, $eshopbox->customer->get_shipping_country());
	}


	/**
	 * Prefix certain countries with 'the'
	 *
	 * @access public
	 * @return string
	 */
	public function estimated_for_prefix() {
		$return = '';
		if (in_array($this->get_base_country(), array( 'GB', 'US', 'AE', 'CZ', 'DO', 'NL', 'PH', 'USAF' ))) $return = __( 'the', 'eshopbox' ) . ' ';
		return apply_filters('eshopbox_countries_estimated_for_prefix', $return, $this->get_base_country());
	}


	/**
	 * Correctly name tax in some countries VAT on the frontend
	 *
	 * @access public
	 * @return string
	 */
	public function tax_or_vat() {
		$return = ( in_array($this->get_base_country(), $this->get_european_union_countries()) ) ? __( 'VAT', 'eshopbox' ) : __( 'Tax', 'eshopbox' );

		return apply_filters( 'eshopbox_countries_tax_or_vat', $return );
	}


	/**
	 * Include the Inc Tax label.
	 *
	 * @access public
	 * @return string
	 */
	public function inc_tax_or_vat() {
		$return = ( in_array($this->get_base_country(), $this->get_european_union_countries()) ) ? __( '(incl. VAT)', 'eshopbox' ) : __( '(incl. tax)', 'eshopbox' );

		return apply_filters( 'eshopbox_countries_inc_tax_or_vat', $return );
	}


	/**
	 * Include the Ex Tax label.
	 *
	 * @access public
	 * @return string
	 */
	public function ex_tax_or_vat() {
		$return = ( in_array($this->get_base_country(), $this->get_european_union_countries()) ) ? __( '(ex. VAT)', 'eshopbox' ) : __( '(ex. tax)', 'eshopbox' );

		return apply_filters( 'eshopbox_countries_ex_tax_or_vat', $return );
	}


	/**
	 * Get the states for a country.
	 *
	 * @access public
	 * @param mixed $cc country code
	 * @return array of states
	 */
	public function get_states( $cc ) {
		if (isset( $this->states[$cc] )) return $this->states[$cc];
	}


	/**
	 * Outputs the list of countries and states for use in dropdown boxes.
	 *
	 * @access public
	 * @param string $selected_country (default: '')
	 * @param string $selected_state (default: '')
	 * @param bool $escape (default: false)
	 * @return void
	 */
	public function country_dropdown_options( $selected_country = '', $selected_state = '', $escape = false ) {

		if ( apply_filters('eshopbox_sort_countries', true ) )
			asort( $this->countries );

		if ( $this->countries ) foreach ( $this->countries as $key=>$value) :
			if ( $states =  $this->get_states($key) ) :
				echo '<optgroup label="'.$value.'">';
    				foreach ($states as $state_key=>$state_value) :
    					echo '<option value="'.$key.':'.$state_key.'"';

    					if ($selected_country==$key && $selected_state==$state_key) echo ' selected="selected"';

    					echo '>'.$value.' &mdash; '. ($escape ? esc_js($state_value) : $state_value) .'</option>';
    				endforeach;
    			echo '</optgroup>';
			else :
    			echo '<option';
    			if ($selected_country==$key && $selected_state=='*') echo ' selected="selected"';
    			echo ' value="'.$key.'">'. ($escape ? esc_js( $value ) : $value) .'</option>';
			endif;
		endforeach;
	}


	/**
	 * Outputs the list of countries and states for use in multiselect boxes.
	 *
	 * @access public
	 * @param string $selected_countries (default: '')
	 * @param bool $escape (default: false)
	 * @return void
	 */
	public function country_multiselect_options( $selected_countries = '', $escape = false ) {

		$countries = $this->get_allowed_countries();

		foreach ( $countries as $key => $val ) {

			echo '<option value="' . $key . '" ' . selected( isset( $selected_countries[ $key ] ) && in_array( '*', $selected_countries[ $key ] ), true, false ) . '>' . ( $escape ? esc_js( $val ) : $val ) . '</option>';

			if ( $states = $this->get_states( $key ) ) {
				foreach ($states as $state_key => $state_value ) {

	    			echo '<option value="' . $key . ':' . $state_key . '" ' . selected(  isset( $selected_countries[ $key ] ) && in_array( $state_key, $selected_countries[ $key ] ), true, false ) . '>' . ( $escape ? esc_js( $val . ' &gt; ' . $state_value ) : $val . ' &gt; ' . $state_value ) . '</option>';

	    		}
			}

		}
	}


	/**
	 * Get country address formats
	 *
	 * @access public
	 * @return array
	 */
	public function get_address_formats() {

		if (!$this->address_formats) :

			// Common formats
			$postcode_before_city = "{company}\n{name}\n{address_1}\n{address_2}\n{postcode} {city}\n{country}";

			// Define address formats
			$this->address_formats = apply_filters('eshopbox_localisation_address_formats', array(
				'default' => "{name}\n{company}\n{address_1}\n{address_2}\n{city}\n{state}\n{postcode}\n{country}",
				'AU' => "{name}\n{company}\n{address_1}\n{address_2}\n{city} {state} {postcode}\n{country}",
				'AT' => $postcode_before_city,
				'BE' => $postcode_before_city,
				'CH' => $postcode_before_city,
				'CN' => "{country} {postcode}\n{state}, {city}, {address_2}, {address_1}\n{company}\n{name}",
				'CZ' => $postcode_before_city,
				'DE' => $postcode_before_city,
				'FI' => $postcode_before_city,
				'DK' => $postcode_before_city,
				'FR' => "{company}\n{name}\n{address_1}\n{address_2}\n{postcode} {city_upper}\n{country}",
				'HK' => "{company}\n{first_name} {last_name_upper}\n{address_1}\n{address_2}\n{city_upper}\n{state_upper}\n{country}",
				'HU' => "{name}\n{company}\n{city}\n{address_1}\n{address_2}\n{postcode}\n{country}",
				'IS' => $postcode_before_city,
				'IS' => $postcode_before_city,
				'LI' => $postcode_before_city,
				'NL' => $postcode_before_city,
				'NZ' => "{name}\n{company}\n{address_1}\n{address_2}\n{city} {postcode}\n{country}",
				'NO' => $postcode_before_city,
				'PL' => $postcode_before_city,
				'SK' => $postcode_before_city,
				'SI' => $postcode_before_city,
				'ES' => "{name}\n{company}\n{address_1}\n{address_2}\n{postcode} {city}\n{state}\n{country}",
				'SE' => $postcode_before_city,
				'TR' => "{name}\n{company}\n{address_1}\n{address_2}\n{postcode} {city} {state}\n{country}",
				'US' => "{name}\n{company}\n{address_1}\n{address_2}\n{city}, {state} {postcode}\n{country}",
				'VN' => "{name}\n{company}\n{address_1}\n{city}\n{country}",
			));
		endif;

		return $this->address_formats;
	}


	/**
	 * Get country address format
	 *
	 * @access public
	 * @param array $args (default: array())
	 * @return string address
	 */
	public function get_formatted_address( $args = array() ) {

		$args = array_map( 'trim', $args );

		extract( $args );

		// Get all formats
		$formats 		= $this->get_address_formats();

		// Get format for the address' country
		$format			= ( $country && isset( $formats[ $country ] ) ) ? $formats[ $country ] : $formats['default'];

		// Handle full country name
		$full_country 	= ( isset( $this->countries[ $country ] ) ) ? $this->countries[ $country ] : $country;

		// Country is not needed if the same as base
		if ( $country == $this->get_base_country() )
			$format = str_replace( '{country}', '', $format );

		// Handle full state name
		$full_state		= ( $country && $state && isset( $this->states[ $country ][ $state ] ) ) ? $this->states[ $country ][ $state ] : $state;

		// Substitute address parts into the string
		$replace = apply_filters( 'eshopbox_formatted_address_replacements', array(
			'{first_name}'       => $first_name,
			'{last_name}'        => $last_name,
			'{name}'             => $first_name . ' ' . $last_name,
			'{company}'          => $company,
			'{address_1}'        => $address_1,
			'{address_2}'        => $address_2,
			'{city}'             => $city,
			'{state}'            => $full_state,
			'{postcode}'         => $postcode,
			'{country}'          => $full_country,
			'{first_name_upper}' => strtoupper( $first_name ),
			'{last_name_upper}'  => strtoupper( $last_name ),
			'{name_upper}'       => strtoupper( $first_name . ' ' . $last_name ),
			'{company_upper}'    => strtoupper( $company ),
			'{address_1_upper}'  => strtoupper( $address_1 ),
			'{address_2_upper}'  => strtoupper( $address_2 ),
			'{city_upper}'       => strtoupper( $city ),
			'{state_upper}'      => strtoupper( $full_state ),
			'{postcode_upper}'   => strtoupper( $postcode ),
			'{country_upper}'    => strtoupper( $full_country ),
		), $args ) ;

		$replace = array_map( 'esc_html', $replace );

		$formatted_address = str_replace( array_keys( $replace ), $replace, $format );

		// Clean up white space
		$formatted_address = preg_replace( '/  +/', ' ', trim( $formatted_address ) );
		$formatted_address = preg_replace( '/\n\n+/', "\n", $formatted_address );

		// Add html breaks
		$formatted_address = nl2br( $formatted_address );

		// We're done!
		return $formatted_address;
	}


	/**
	 * Returns the fields we show by default. This can be filtered later on.
	 *
	 * @access public
	 * @return void
	 */
	public function get_default_address_fields() {
		$fields = array(
			'country'            => array(
				'type'              => 'country',
				'label'             => __( 'Country', 'eshopbox' ),
				'required'          => true,
				'class'             => array( 'form-row-wide', 'address-field', 'update_totals_on_change' ),
			),
			'first_name'         => array(
				'label'             => __( 'First Name', 'eshopbox' ),
				'required'          => true,
				'class'             => array( 'form-row-first' ),
			),
			'last_name'          => array(
				'label'             => __( 'Last Name', 'eshopbox' ),
				'required'          => true,
				'class'             => array( 'form-row-last' ),
				'clear'             => true
			),
			'company'            => array(
				'label'             => __( 'Company Name', 'eshopbox' ),
				'class'             => array( 'form-row-wide' ),
			),
			'address_1'          => array(
				'label'             => __( 'Address', 'eshopbox' ),
				'placeholder'       => _x( 'Street address', 'placeholder', 'eshopbox' ),
				'required'          => true,
				'class'             => array( 'form-row-wide', 'address-field' ),
				'custom_attributes' => array(
					'autocomplete'     => 'no'
				)
			),
			'address_2'          => array(
				'placeholder'       => _x( 'Apartment, suite, unit etc. (optional)', 'placeholder', 'eshopbox' ),
				'class'             => array( 'form-row-wide', 'address-field' ),
				'required'          => false,
				'custom_attributes' => array(
					'autocomplete'     => 'no'
				)
			),
			'city'               => array(
				'label'             => __( 'Town / City', 'eshopbox' ),
				'placeholder'       => __( 'Town / City', 'eshopbox' ),
				'required'          => true,
				'class'             => array( 'form-row-wide', 'address-field' ),
				'custom_attributes' => array(
					'autocomplete'     => 'no'
				)
			),
			'state'              => array(
				'type'              => 'state',
				'label'             => __( 'State / County', 'eshopbox' ),
				'placeholder'       => __( 'State / County', 'eshopbox' ),
				'required'          => true,
				'class'             => array( 'form-row-first', 'address-field' ),
				'custom_attributes' => array(
					'autocomplete'     => 'no'
				)
			),
			'postcode'           => array(
				'label'             => __( 'Postcode / Zip', 'eshopbox' ),
				'placeholder'       => __( 'Postcode / Zip', 'eshopbox' ),
				'required'          => true,
				'class'             => array( 'form-row-last', 'address-field' ),
				'clear'             => true,
				'custom_attributes' => array(
					'autocomplete'     => 'no'
				)
			),
		);

		return apply_filters( 'eshopbox_default_address_fields', $fields );
	}

	/**
	 * Get country locale settings
	 *
	 * @access public
	 * @return array
	 */
	public function get_country_locale() {
		if ( ! $this->locale ) {

			// Locale information used by the checkout
			$this->locale = apply_filters('eshopbox_get_country_locale', array(
				'AF' => array(
					'state' => array(
						'required' => false,
					),
				),
				'AT' => array(
					'postcode_before_city' => true,
					'state'		=> array(
						'required' => false
					)
				),
				'BE' => array(
					'postcode_before_city' => true,
					'state' => array(
						'required' => false,
						'label'    => __( 'Province', 'eshopbox' ),
					),
				),
				'BI' => array(
					'state' => array(
						'required' => false,
					),
				),
				'CA' => array(
					'state'	=> array(
						'label'			=> __( 'Province', 'eshopbox' ),
					)
				),
				'CH' => array(
                    'postcode_before_city' => true,
                    'state' => array(
                        'label'         => __( 'Canton', 'eshopbox' ),
                        'required'      => false
                    )
                ),
				'CL' => array(
					'city'		=> array(
						'required' 	=> false,
					),
					'state'		=> array(
						'label'			=> __( 'Municipality', 'eshopbox' ),
					)
				),
				'CN' => array(
					'state'	=> array(
						'label'			=> __( 'Province', 'eshopbox' ),
					)
				),
				'CO' => array(
					'postcode' => array(
						'required' 	=> false
					)
				),
				'CZ' => array(
					'state'		=> array(
						'required' => false
					)
				),
				'DE' => array(
					'postcode_before_city' => true,
					'state'		=> array(
						'required' => false
					)
				),
				'DK' => array(
					'postcode_before_city' => true,
					'state'		=> array(
						'required' => false
					)
				),
				'FI' => array(
					'postcode_before_city' => true,
					'state'		=> array(
						'required' => false
					)
				),
				'FR' => array(
					'postcode_before_city' => true,
					'state'		=> array(
						'required' => false
					)
				),
				'HK' => array(
					'postcode'	=> array(
						'required' => false
					),
					'city'	=> array(
						'label'				=> __( 'Town / District', 'eshopbox' ),
					),
					'state'		=> array(
						'label' 		=> __( 'Region', 'eshopbox' ),
					)
				),
				'HU' => array(
					'state'		=> array(
						'required' => false
					)
				),
				'ID' => array(
	                'state' => array(
	                    'label'         => __( 'Province', 'eshopbox' ),
	                )
            	),
				'IS' => array(
					'postcode_before_city' => true,
					'state'		=> array(
						'required' => false
					)
				),
				'IL' => array(
					'postcode_before_city' => true,
					'state'		=> array(
						'required' => false
					)
				),
				'KR' => array(
					'state'		=> array(
						'required' => false
					)
				),
				'NL' => array(
					'postcode_before_city' => true,
					'state'		=> array(
						'required' => false,
						'label'    => __( 'Province', 'eshopbox' ),
					)
				),
				'NZ' => array(
					'state'		=> array(
						'required' => false
					)
				),
				'NO' => array(
					'postcode_before_city' => true,
					'state'		=> array(
						'required' => false
					)
				),
				'PL' => array(
					'postcode_before_city' => true,
					'state'		=> array(
						'required' => false
					)
				),
				'PT' => array(
					'state'		=> array(
						'required' => false
					)
				),
				'RO' => array(
					'state'		=> array(
						'required' => false
					)
				),
				'SG' => array(
					'state'		=> array(
						'required' => false
					)
				),
				'SK' => array(
					'postcode_before_city' => true,
					'state'		=> array(
						'required' => false
					)
				),
				'SI' => array(
					'postcode_before_city' => true,
					'state'		=> array(
						'required' => false
					)
				),
				'ES' => array(
					'postcode_before_city' => true,
					'state'	=> array(
						'label'			=> __( 'Province', 'eshopbox' ),
					)
				),
				'LI' => array(
                    'postcode_before_city' => true,
                    'state' => array(
                        'label'         => __( 'Municipality', 'eshopbox' ),
                        'required'      => false
                    )
                ),
				'LK' => array(
					'state'	=> array(
						'required' => false
					)
				),
				'SE' => array(
					'postcode_before_city' => true,
					'state'	=> array(
						'required' => false
					)
				),
				'TR' => array(
					'postcode_before_city' => true,
					'state'	=> array(
						'label'			=> __( 'Province', 'eshopbox' ),
					)
				),
				'US' => array(
					'postcode'	=> array(
						'label' 		=> __( 'Zip', 'eshopbox' ),
					),
					'state'		=> array(
						'label' 		=> __( 'State', 'eshopbox' ),
					)
				),
				'GB' => array(
					'postcode'	=> array(
						'label' 		=> __( 'Postcode', 'eshopbox' ),
					),
					'state'		=> array(
						'label' 		=> __( 'County', 'eshopbox' ),
						'required' 		=> false
					)
				),
				'VN' => array(
					'state'		=> array(
						'required' => false
					),
					'postcode' => array(
						'required' 	=> false,
						'hidden'	=> true
					),
					'address_2' => array(
						'required' 	=> false,
						'hidden'	=> true
					)
				),
				'WS' => array(
					'postcode' => array(
						'required' 	=> false,
						'hidden'	=> true
					),
				),
				'ZA' => array(
					'state'	=> array(
						'label'			=> __( 'Province', 'eshopbox' ),
					)
				),
				'ZW' => array(
					'postcode' => array(
						'required' 	=> false,
						'hidden'	=> true
					),
				),
			));

			$this->locale = array_intersect_key( $this->locale, $this->get_allowed_countries() );

			// Default Locale Can be filters to override fields in get_address_fields().
			// Countries with no specific locale will use default.
			$this->locale['default'] = apply_filters('eshopbox_get_country_locale_default', $this->get_default_address_fields() );

			// Filter default AND shop base locales to allow overides via a single function. These will be used when changing countries on the checkout
			if ( ! isset( $this->locale[ $this->get_base_country() ] ) )
				$this->locale[ $this->get_base_country() ] = $this->locale['default'];

			$this->locale['default'] 					= apply_filters( 'eshopbox_get_country_locale_base', $this->locale['default'] );
			$this->locale[ $this->get_base_country() ] 	= apply_filters( 'eshopbox_get_country_locale_base', $this->locale[ $this->get_base_country() ] );
		}

		return $this->locale;
	}

	/**
	 * Apply locale and get address fields
	 *
	 * @access public
	 * @param mixed $country
	 * @param string $type (default: 'billing_')
	 * @return void
	 */
	public function get_address_fields( $country, $type = 'billing_' ) {
		$fields     = $this->get_default_address_fields();
		$locale		= $this->get_country_locale();

		if ( isset( $locale[ $country ] ) ) {

			$fields = eshopbox_array_overlay( $fields, $locale[ $country ] );

			// If default country has postcode_before_city switch the fields round.
			// This is only done at this point, not if country changes on checkout.
			if ( isset( $locale[ $country ]['postcode_before_city'] ) ) {
				if ( isset( $fields['postcode'] ) ) {
					$fields['postcode']['class'] = array( 'form-row-wide', 'address-field' );

					$switch_fields = array();

					foreach ( $fields as $key => $value ) {
						if ( $key == 'city' ) {
							// Place postcode before city
							$switch_fields['postcode'] = '';
						}
						$switch_fields[$key] = $value;
					}

					$fields = $switch_fields;
				}
			}
		}

		// Prepend field keys
		$address_fields = array();

		foreach ( $fields as $key => $value ) {
			$address_fields[$type . $key] = $value;
		}

		// Billing/Shipping Specific
		if ( $type == 'billing_' ) {

			$address_fields['billing_email'] = array(
				'label' 		=> __( 'Email Address', 'eshopbox' ),
				'required' 		=> true,
				'class' 		=> array( 'form-row-first' ),
				'validate'		=> array( 'email' ),
			);
			$address_fields['billing_phone'] = array(
				'label' 		=> __( 'Phone', 'eshopbox' ),
				'required' 		=> true,
				'class' 		=> array( 'form-row-last' ),
				'clear'			=> true
			);

			$address_fields = apply_filters( 'eshopbox_billing_fields', $address_fields, $country );
		} else {
			$address_fields = apply_filters( 'eshopbox_shipping_fields', $address_fields, $country );
		}

		// Return
		return $address_fields;
	}
}
