<?php
/**
 * Option API
 *
 * @package WordPress
 * @subpackage Option
 */

/**
 * Retrieve option value based on name of option.
 *
 * If the option does not exist or does not have a value, then the return value
 * will be false. This is useful to check whether you need to install an option
 * and is commonly used during installation of plugin options and to test
 * whether upgrading is required.
 *
 * If the option was serialized then it will be unserialized when it is returned.
 *
 * @since 1.5.0
 *
 * @param string $option Name of option to retrieve. Expected to not be SQL-escaped.
 * @param mixed $default Optional. Default value to return if the option does not exist.
 * @return mixed Value set for the option.
 */
function get_option( $option, $default = false ) {
	global $wpdb;

	$option = trim( $option );
	if ( empty( $option ) )
		return false;

	/**
	 * Filter the value of an existing option before it is retrieved.
	 *
	 * The dynamic portion of the hook name, $option, refers to the option name.
	 *
	 * Passing a truthy value to the filter will short-circuit retrieving
	 * the option value, returning the passed value instead.
	 *
	 * @since 1.5.0
	 *
	 * @param bool|mixed $pre_option Value to return instead of the option value.
	 *                               Default false to skip it.
	 */
	$pre = apply_filters( 'pre_option_' . $option, false );
	if ( false !== $pre )
		return $pre;

	if ( defined( 'WP_SETUP_CONFIG' ) )
		return false;

	if ( ! defined( 'WP_INSTALLING' ) ) {
		// prevent non-existent options from triggering multiple queries
		$notoptions = wp_cache_get( 'notoptions', 'options' );
		if ( isset( $notoptions[$option] ) )

			/**
			 * Filter the default value for an option.
			 *
			 * The dynamic portion of the hook name, $option, refers
			 * to the option name.
			 *
			 * @since 3.4.0
			 *
			 * @param mixed $default The default value to return if the option
			 *                       does not exist in the database.
			 */
			return apply_filters( 'default_option_' . $option, $default );

		$alloptions = wp_load_alloptions();

		if ( isset( $alloptions[$option] ) ) {
			$value = $alloptions[$option];
		} else {
			$value = wp_cache_get( $option, 'options' );

			if ( false === $value ) {
				$row = $wpdb->get_row( $wpdb->prepare( "SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1", $option ) );

				// Has to be get_row instead of get_var because of funkiness with 0, false, null values
				if ( is_object( $row ) ) {
					$value = $row->option_value;
					wp_cache_add( $option, $value, 'options' );
				} else { // option does not exist, so we must cache its non-existence
					$notoptions[$option] = true;
					wp_cache_set( 'notoptions', $notoptions, 'options' );

					/** This filter is documented in eshop-includes/option.php */
					return apply_filters( 'default_option_' . $option, $default );
				}
			}
		}
	} else {
		$suppress = $wpdb->suppress_errors();
		$row = $wpdb->get_row( $wpdb->prepare( "SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1", $option ) );
		$wpdb->suppress_errors( $suppress );
		if ( is_object( $row ) ) {
			$value = $row->option_value;
		} else {
			/** This filter is documented in eshop-includes/option.php */
			return apply_filters( 'default_option_' . $option, $default );
		}
	}

	// If home is not set use siteurl.
	if ( 'home' == $option && '' == $value )
		return get_option( 'siteurl' );

	if ( in_array( $option, array('siteurl', 'home', 'category_base', 'tag_base') ) )
		$value = untrailingslashit( $value );

	/**
	 * Filter the value of an existing option.
	 *
	 * The dynamic portion of the hook name, $option, refers to the option name.
	 *
	 * @since 1.5.0 As 'option_' . $setting
	 * @since 3.0.0
	 *
	 * @param mixed $value Value of the option. If stored serialized, it will be
	 *                     unserialized prior to being returned.
	 */
	return apply_filters( 'option_' . $option, maybe_unserialize( $value ) );
}

/**
 * Protect WordPress special option from being modified.
 *
 * Will die if $option is in protected list. Protected options are 'alloptions'
 * and 'notoptions' options.
 *
 * @since 2.2.0
 *
 * @param string $option Option name.
 */
function wp_protect_special_option( $option ) {
	if ( 'alloptions' === $option || 'notoptions' === $option )
		wp_die( sprintf( __( '%s is a protected WP option and may not be modified' ), esc_html( $option ) ) );
}

/**
 * Print option value after sanitizing for forms.
 *
 * @uses attr Sanitizes value.
 * @since 1.5.0
 *
 * @param string $option Option name.
 */
function form_option( $option ) {
	echo esc_attr( get_option( $option ) );
}

/**
 * Loads and caches all autoloaded options, if available or all options.
 *
 * @since 2.2.0
 *
 * @return array List of all options.
 */
function wp_load_alloptions() {
	global $wpdb;

	if ( !defined( 'WP_INSTALLING' ) || !is_multisite() )
		$alloptions = wp_cache_get( 'alloptions', 'options' );
	else
		$alloptions = false;

	if ( !$alloptions ) {
		$suppress = $wpdb->suppress_errors();
		if ( !$alloptions_db = $wpdb->get_results( "SELECT option_name, option_value FROM $wpdb->options WHERE autoload = 'yes'" ) )
			$alloptions_db = $wpdb->get_results( "SELECT option_name, option_value FROM $wpdb->options" );
		$wpdb->suppress_errors($suppress);
		$alloptions = array();
		foreach ( (array) $alloptions_db as $o ) {
			$alloptions[$o->option_name] = $o->option_value;
		}
		if ( !defined( 'WP_INSTALLING' ) || !is_multisite() )
			wp_cache_add( 'alloptions', $alloptions, 'options' );
	}

	return $alloptions;
}

/**
 * Loads and caches certain often requested site options if is_multisite() and a persistent cache is not being used.
 *
 * @since 3.0.0
 *
 * @param int $site_id Optional site ID for which to query the options. Defaults to the current site.
 */
function wp_load_core_site_options( $site_id = null ) {
	global $wpdb;

	if ( !is_multisite() || wp_using_ext_object_cache() || defined( 'WP_INSTALLING' ) )
		return;

	if ( empty($site_id) )
		$site_id = $wpdb->siteid;

	$core_options = array('site_name', 'siteurl', 'active_sitewide_plugins', '_site_transient_timeout_theme_roots', '_site_transient_theme_roots', 'site_admins', 'can_compress_scripts', 'global_terms_enabled', 'ms_files_rewriting' );

	$core_options_in = "'" . implode("', '", $core_options) . "'";
	$options = $wpdb->get_results( $wpdb->prepare("SELECT meta_key, meta_value FROM $wpdb->sitemeta WHERE meta_key IN ($core_options_in) AND site_id = %d", $site_id) );

	foreach ( $options as $option ) {
		$key = $option->meta_key;
		$cache_key = "{$site_id}:$key";
		$option->meta_value = maybe_unserialize( $option->meta_value );

		wp_cache_set( $cache_key, $option->meta_value, 'site-options' );
	}
}

/**
 * Update the value of an option that was already added.
 *
 * You do not need to serialize values. If the value needs to be serialized, then
 * it will be serialized before it is inserted into the database. Remember,
 * resources can not be serialized or added as an option.
 *
 * If the option does not exist, then the option will be added with the option
 * value, but you will not be able to set whether it is autoloaded. If you want
 * to set whether an option is autoloaded, then you need to use the add_option().
 *
 * @since 1.0.0
 *
 * @param string $option Option name. Expected to not be SQL-escaped.
 * @param mixed $value Option value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
 * @return bool False if value was not updated and true if value was updated.
 */
function update_option( $option, $value ) {
	global $wpdb;

	$option = trim($option);
	if ( empty($option) )
		return false;

	wp_protect_special_option( $option );

	if ( is_object( $value ) )
		$value = clone $value;

	$value = sanitize_option( $option, $value );
	$old_value = get_option( $option );

	/**
	 * Filter a specific option before its value is (maybe) serialized and updated.
	 *
	 * The dynamic portion of the hook name, $option, refers to the option name.
	 *
	 * @since 2.6.0
	 *
	 * @param mixed $value     The new, unserialized option value.
	 * @param mixed $old_value The old option value.
	 */
	$value = apply_filters( 'pre_update_option_' . $option, $value, $old_value );

	/**
	 * Filter an option before its value is (maybe) serialized and updated.
	 *
	 * @since 3.9.0
	 *
	 * @param mixed  $value     The new, unserialized option value.
	 * @param string $option    Name of the option.
	 * @param mixed  $old_value The old option value.
	 */
	$value = apply_filters( 'pre_update_option', $value, $option, $old_value );

	// If the new and old values are the same, no need to update.
	if ( $value === $old_value )
		return false;

	if ( false === $old_value )
		return add_option( $option, $value );

	$serialized_value = maybe_serialize( $value );

	/**
	 * Fires immediately before an option value is updated.
	 *
	 * @since 2.9.0
	 *
	 * @param string $option    Name of the option to update.
	 * @param mixed  $old_value The old option value.
	 * @param mixed  $value     The new option value.
	 */
	do_action( 'update_option', $option, $old_value, $value );

	$result = $wpdb->update( $wpdb->options, array( 'option_value' => $serialized_value ), array( 'option_name' => $option ) );
	if ( ! $result )
		return false;

	$notoptions = wp_cache_get( 'notoptions', 'options' );
	if ( is_array( $notoptions ) && isset( $notoptions[$option] ) ) {
		unset( $notoptions[$option] );
		wp_cache_set( 'notoptions', $notoptions, 'options' );
	}

	if ( ! defined( 'WP_INSTALLING' ) ) {
		$alloptions = wp_load_alloptions();
		if ( isset( $alloptions[$option] ) ) {
			$alloptions[ $option ] = $serialized_value;
			wp_cache_set( 'alloptions', $alloptions, 'options' );
		} else {
			wp_cache_set( $option, $serialized_value, 'options' );
		}
	}

	/**
	 * Fires after the value of a specific option has been successfully updated.
	 *
	 * The dynamic portion of the hook name, $option, refers to the option name.
	 *
	 * @since 2.0.1
	 *
	 * @param mixed $old_value The old option value.
	 * @param mixed $value     The new option value.
	 */
	do_action( "update_option_{$option}", $old_value, $value );

	/**
	 * Fires after the value of an option has been successfully updated.
	 *
	 * @since 2.9.0
	 *
	 * @param string $option    Name of the updated option.
	 * @param mixed  $old_value The old option value.
	 * @param mixed  $value     The new option value.
	 */
	do_action( 'updated_option', $option, $old_value, $value );
	return true;
}

/**
 * Add a new option.
 *
 * You do not need to serialize values. If the value needs to be serialized, then
 * it will be serialized before it is inserted into the database. Remember,
 * resources can not be serialized or added as an option.
 *
 * You can create options without values and then update the values later.
 * Existing options will not be updated and checks are performed to ensure that you
 * aren't adding a protected WordPress option. Care should be taken to not name
 * options the same as the ones which are protected.
 *
 * @since 1.0.0
 *
 * @param string $option Name of option to add. Expected to not be SQL-escaped.
 * @param mixed $value Optional. Option value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
 * @param mixed $deprecated Optional. Description. Not used anymore.
 * @param bool $autoload Optional. Default is enabled. Whether to load the option when WordPress starts up.
 * @return bool False if option was not added and true if option was added.
 */
function add_option( $option, $value = '', $deprecated = '', $autoload = 'yes' ) {
	global $wpdb;

	if ( !empty( $deprecated ) )
		_deprecated_argument( __FUNCTION__, '2.3' );

	$option = trim($option);
	if ( empty($option) )
		return false;

	wp_protect_special_option( $option );

	if ( is_object($value) )
		$value = clone $value;

	$value = sanitize_option( $option, $value );

	// Make sure the option doesn't already exist. We can check the 'notoptions' cache before we ask for a db query
	$notoptions = wp_cache_get( 'notoptions', 'options' );
	if ( !is_array( $notoptions ) || !isset( $notoptions[$option] ) )
		if ( false !== get_option( $option ) )
			return false;

	$serialized_value = maybe_serialize( $value );
	$autoload = ( 'no' === $autoload ) ? 'no' : 'yes';

	/**
	 * Fires before an option is added.
	 *
	 * @since 2.9.0
	 *
	 * @param string $option Name of the option to add.
	 * @param mixed  $value  Value of the option.
	 */
	do_action( 'add_option', $option, $value );

	$result = $wpdb->query( $wpdb->prepare( "INSERT INTO `$wpdb->options` (`option_name`, `option_value`, `autoload`) VALUES (%s, %s, %s) ON DUPLICATE KEY UPDATE `option_name` = VALUES(`option_name`), `option_value` = VALUES(`option_value`), `autoload` = VALUES(`autoload`)", $option, $serialized_value, $autoload ) );
	if ( ! $result )
		return false;

	if ( ! defined( 'WP_INSTALLING' ) ) {
		if ( 'yes' == $autoload ) {
			$alloptions = wp_load_alloptions();
			$alloptions[ $option ] = $serialized_value;
			wp_cache_set( 'alloptions', $alloptions, 'options' );
		} else {
			wp_cache_set( $option, $serialized_value, 'options' );
		}
	}

	// This option exists now
	$notoptions = wp_cache_get( 'notoptions', 'options' ); // yes, again... we need it to be fresh
	if ( is_array( $notoptions ) && isset( $notoptions[$option] ) ) {
		unset( $notoptions[$option] );
		wp_cache_set( 'notoptions', $notoptions, 'options' );
	}

	/**
	 * Fires after a specific option has been added.
	 *
	 * The dynamic portion of the hook name, $option, refers to the option name.
	 *
	 * @since 2.5.0 As "add_option_{$name}"
	 * @since 3.0.0
	 *
	 * @param string $option Name of the option to add.
	 * @param mixed  $value  Value of the option.
	 */
	do_action( "add_option_{$option}", $option, $value );

	/**
	 * Fires after an option has been added.
	 *
	 * @since 2.9.0
	 *
	 * @param string $option Name of the added option.
	 * @param mixed  $value  Value of the option.
	 */
	do_action( 'added_option', $option, $value );
	return true;
}

/**
 * Removes option by name. Prevents removal of protected WordPress options.
 *
 * @since 1.2.0
 *
 * @param string $option Name of option to remove. Expected to not be SQL-escaped.
 * @return bool True, if option is successfully deleted. False on failure.
 */
function delete_option( $option ) {
	global $wpdb;

	$option = trim( $option );
	if ( empty( $option ) )
		return false;

	wp_protect_special_option( $option );

	// Get the ID, if no ID then return
	$row = $wpdb->get_row( $wpdb->prepare( "SELECT autoload FROM $wpdb->options WHERE option_name = %s", $option ) );
	if ( is_null( $row ) )
		return false;

	/**
	 * Fires immediately before an option is deleted.
	 *
	 * @since 2.9.0
	 *
	 * @param string $option Name of the option to delete.
	 */
	do_action( 'delete_option', $option );

	$result = $wpdb->delete( $wpdb->options, array( 'option_name' => $option ) );
	if ( ! defined( 'WP_INSTALLING' ) ) {
		if ( 'yes' == $row->autoload ) {
			$alloptions = wp_load_alloptions();
			if ( is_array( $alloptions ) && isset( $alloptions[$option] ) ) {
				unset( $alloptions[$option] );
				wp_cache_set( 'alloptions', $alloptions, 'options' );
			}
		} else {
			wp_cache_delete( $option, 'options' );
		}
	}
	if ( $result ) {

		/**
		 * Fires after a specific option has been deleted.
		 *
		 * The dynamic portion of the hook name, $option, refers to the option name.
		 *
		 * @since 3.0.0
		 *
		 * @param string $option Name of the deleted option.
		 */
		do_action( "delete_option_$option", $option );

		/**
		 * Fires after an option has been deleted.
		 *
		 * @since 2.9.0
		 *
		 * @param string $option Name of the deleted option.
		 */
		do_action( 'deleted_option', $option );
		return true;
	}
	return false;
}

/**
 * Delete a transient.
 *
 * @since 2.8.0
 *
 * @param string $transient Transient name. Expected to not be SQL-escaped.
 * @return bool true if successful, false otherwise
 */
function delete_transient( $transient ) {

	/**
	 * Fires immediately before a specific transient is deleted.
	 *
	 * The dynamic portion of the hook name, $transient, refers to the transient name.
	 *
	 * @since 3.0.0
	 *
	 * @param string $transient Transient name.
	 */
	do_action( 'delete_transient_' . $transient, $transient );

	if ( wp_using_ext_object_cache() ) {
		$result = wp_cache_delete( $transient, 'transient' );
	} else {
		$option_timeout = '_transient_timeout_' . $transient;
		$option = '_transient_' . $transient;
		$result = delete_option( $option );
		if ( $result )
			delete_option( $option_timeout );
	}

	if ( $result ) {

		/**
		 * Fires after a transient is deleted.
		 *
		 * @since 3.0.0
		 *
		 * @param string $transient Deleted transient name.
		 */
		do_action( 'deleted_transient', $transient );
	}

	return $result;
}

/**
 * Get the value of a transient.
 *
 * If the transient does not exist or does not have a value, then the return value
 * will be false.
 *
 * @since 2.8.0
 *
 * @param string $transient Transient name. Expected to not be SQL-escaped
 * @return mixed Value of transient
 */
function get_transient( $transient ) {

 	/**
	 * Filter the value of an existing transient.
	 *
	 * The dynamic portion of the hook name, $transient, refers to the transient name.
	 *
	 * Passing a truthy value to the filter will effectively short-circuit retrieval
	 * of the transient, returning the passed value instead.
	 *
	 * @since 2.8.0
	 *
	 * @param mixed $pre_transient The default value to return if the transient does not exist.
	 *                             Any value other than false will short-circuit the retrieval
	 *                             of the transient, and return the returned value.
	 */
	$pre = apply_filters( 'pre_transient_' . $transient, false );
	if ( false !== $pre )
		return $pre;

	if ( wp_using_ext_object_cache() ) {
		$value = wp_cache_get( $transient, 'transient' );
	} else {
		$transient_option = '_transient_' . $transient;
		if ( ! defined( 'WP_INSTALLING' ) ) {
			// If option is not in alloptions, it is not autoloaded and thus has a timeout
			$alloptions = wp_load_alloptions();
			if ( !isset( $alloptions[$transient_option] ) ) {
				$transient_timeout = '_transient_timeout_' . $transient;
				if ( get_option( $transient_timeout ) < time() ) {
					delete_option( $transient_option  );
					delete_option( $transient_timeout );
					$value = false;
				}
			}
		}

		if ( ! isset( $value ) )
			$value = get_option( $transient_option );
	}

	/**
	 * Filter an existing transient's value.
	 *
	 * The dynamic portion of the hook name, $transient, refers to the transient name.
	 *
	 * @since 2.8.0
	 *
	 * @param mixed $value Value of transient.
	 */
	return apply_filters( 'transient_' . $transient, $value );
}

/**
 * Set/update the value of a transient.
 *
 * You do not need to serialize values. If the value needs to be serialized, then
 * it will be serialized before it is set.
 *
 * @since 2.8.0
 *
 * @param string $transient Transient name. Expected to not be SQL-escaped.
 * @param mixed $value Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
 * @param int $expiration Time until expiration in seconds, default 0
 * @return bool False if value was not set and true if value was set.
 */
function set_transient( $transient, $value, $expiration = 0 ) {

	/**
	 * Filter a specific transient before its value is set.
	 *
	 * The dynamic portion of the hook name, $transient, refers to the transient name.
	 *
	 * @since 3.0.0
	 *
	 * @param mixed $value New value of transient.
	 */
	$value = apply_filters( 'pre_set_transient_' . $transient, $value );

	$expiration = (int) $expiration;

	if ( wp_using_ext_object_cache() ) {
		$result = wp_cache_set( $transient, $value, 'transient', $expiration );
	} else {
		$transient_timeout = '_transient_timeout_' . $transient;
		$transient = '_transient_' . $transient;
		if ( false === get_option( $transient ) ) {
			$autoload = 'yes';
			if ( $expiration ) {
				$autoload = 'no';
				add_option( $transient_timeout, time() + $expiration, '', 'no' );
			}
			$result = add_option( $transient, $value, '', $autoload );
		} else {
			// If expiration is requested, but the transient has no timeout option,
			// delete, then re-create transient rather than update.
			$update = true;
			if ( $expiration ) {
				if ( false === get_option( $transient_timeout ) ) {
					delete_option( $transient );
					add_option( $transient_timeout, time() + $expiration, '', 'no' );
					$result = add_option( $transient, $value, '', 'no' );
					$update = false;
				} else {
					update_option( $transient_timeout, time() + $expiration );
				}
			}
			if ( $update ) {
				$result = update_option( $transient, $value );
			}
		}
	}

	if ( $result ) {

		/**
		 * Fires after the value for a specific transient has been set.
		 *
		 * The dynamic portion of the hook name, $transient, refers to the transient name.
		 *
		 * @since 3.0.0
		 *
		 * @param mixed $value      Transient value.
		 * @param int   $expiration Time until expiration in seconds. Default 0.
		 */
		do_action( 'set_transient_' . $transient, $value, $expiration );

		/**
		 * Fires after the value for a transient has been set.
		 *
		 * @since 3.0.0
		 *
		 * @param string $transient  The name of the transient.
		 * @param mixed  $value      Transient value.
		 * @param int    $expiration Time until expiration in seconds. Default 0.
		 */
		do_action( 'setted_transient', $transient, $value, $expiration );
	}
	return $result;
}

/**
 * Saves and restores user interface settings stored in a cookie.
 *
 * Checks if the current user-settings cookie is updated and stores it. When no
 * cookie exists (different browser used), adds the last saved cookie restoring
 * the settings.
 *
 * @since 2.7.0
 */
function wp_user_settings() {

	if ( ! is_admin() )
		return;

	if ( defined('DOING_AJAX') )
		return;

	if ( ! $user_id = get_current_user_id() )
		return;

	if ( is_super_admin() && ! is_user_member_of_blog() )
		return;

	$settings = (string) get_user_option( 'user-settings', $user_id );

	if ( isset( $_COOKIE['wp-settings-' . $user_id] ) ) {
		$cookie = preg_replace( '/[^A-Za-z0-9=&_]/', '', $_COOKIE['wp-settings-' . $user_id] );

		// No change or both empty
		if ( $cookie == $settings )
			return;

		$last_saved = (int) get_user_option( 'user-settings-time', $user_id );
		$current = isset( $_COOKIE['wp-settings-time-' . $user_id]) ? preg_replace( '/[^0-9]/', '', $_COOKIE['wp-settings-time-' . $user_id] ) : 0;

		// The cookie is newer than the saved value. Update the user_option and leave the cookie as-is
		if ( $current > $last_saved ) {
			update_user_option( $user_id, 'user-settings', $cookie, false );
			update_user_option( $user_id, 'user-settings-time', time() - 5, false );
			return;
		}
	}

	// The cookie is not set in the current browser or the saved value is newer.
	setcookie( 'wp-settings-' . $user_id, $settings, time() + YEAR_IN_SECONDS, SITECOOKIEPATH );
	setcookie( 'wp-settings-time-' . $user_id, time(), time() + YEAR_IN_SECONDS, SITECOOKIEPATH );
	$_COOKIE['wp-settings-' . $user_id] = $settings;
}

/**
 * Retrieve user interface setting value based on setting name.
 *
 * @since 2.7.0
 *
 * @param string $name The name of the setting.
 * @param string $default Optional default value to return when $name is not set.
 * @return mixed the last saved user setting or the default value/false if it doesn't exist.
 */
function get_user_setting( $name, $default = false ) {
	$all_user_settings = get_all_user_settings();

	return isset( $all_user_settings[$name] ) ? $all_user_settings[$name] : $default;
}

/**
 * Add or update user interface setting.
 *
 * Both $name and $value can contain only ASCII letters, numbers and underscores.
 * This function has to be used before any output has started as it calls setcookie().
 *
 * @since 2.8.0
 *
 * @param string $name The name of the setting.
 * @param string $value The value for the setting.
 * @return bool true if set successfully/false if not.
 */
function set_user_setting( $name, $value ) {

	if ( headers_sent() )
		return false;

	$all_user_settings = get_all_user_settings();
	$all_user_settings[$name] = $value;

	return wp_set_all_user_settings( $all_user_settings );
}

/**
 * Delete user interface settings.
 *
 * Deleting settings would reset them to the defaults.
 * This function has to be used before any output has started as it calls setcookie().
 *
 * @since 2.7.0
 *
 * @param mixed $names The name or array of names of the setting to be deleted.
 * @return bool true if deleted successfully/false if not.
 */
function delete_user_setting( $names ) {

	if ( headers_sent() )
		return false;

	$all_user_settings = get_all_user_settings();
	$names = (array) $names;
	$deleted = false;

	foreach ( $names as $name ) {
		if ( isset( $all_user_settings[$name] ) ) {
			unset( $all_user_settings[$name] );
			$deleted = true;
		}
	}

	if ( $deleted )
		return wp_set_all_user_settings( $all_user_settings );

	return false;
}

/**
 * Retrieve all user interface settings.
 *
 * @since 2.7.0
 *
 * @return array the last saved user settings or empty array.
 */
function get_all_user_settings() {
	global $_updated_user_settings;

	if ( ! $user_id = get_current_user_id() )
		return array();

	if ( isset( $_updated_user_settings ) && is_array( $_updated_user_settings ) )
		return $_updated_user_settings;

	$user_settings = array();
	if ( isset( $_COOKIE['wp-settings-' . $user_id] ) ) {
		$cookie = preg_replace( '/[^A-Za-z0-9=&_]/', '', $_COOKIE['wp-settings-' . $user_id] );

		if ( $cookie && strpos( $cookie, '=' ) ) // '=' cannot be 1st char
			parse_str( $cookie, $user_settings );

	} else {
		$option = get_user_option( 'user-settings', $user_id );
		if ( $option && is_string($option) )
			parse_str( $option, $user_settings );
	}

	$_updated_user_settings = $user_settings;
	return $user_settings;
}

/**
 * Private. Set all user interface settings.
 *
 * @since 2.8.0
 *
 * @param array $user_settings
 * @return bool
 */
function wp_set_all_user_settings( $user_settings ) {
	global $_updated_user_settings;

	if ( ! $user_id = get_current_user_id() )
		return false;

	if ( is_super_admin() && ! is_user_member_of_blog() )
		return;

	$settings = '';
	foreach ( $user_settings as $name => $value ) {
		$_name = preg_replace( '/[^A-Za-z0-9_]+/', '', $name );
		$_value = preg_replace( '/[^A-Za-z0-9_]+/', '', $value );

		if ( ! empty( $_name ) )
			$settings .= $_name . '=' . $_value . '&';
	}

	$settings = rtrim($settings, '&');
	parse_str( $settings, $_updated_user_settings );

	update_user_option( $user_id, 'user-settings', $settings, false );
	update_user_option( $user_id, 'user-settings-time', time(), false );

	return true;
}

/**
 * Delete the user settings of the current user.
 *
 * @since 2.7.0
 */
function delete_all_user_settings() {
	if ( ! $user_id = get_current_user_id() )
		return;

	update_user_option( $user_id, 'user-settings', '', false );
	setcookie('wp-settings-' . $user_id, ' ', time() - YEAR_IN_SECONDS, SITECOOKIEPATH);
}

/**
 * Retrieve site option value based on name of option.
 *
 * @since 2.8.0
 *
 * @see get_option()
 *
 * @param string $option Name of option to retrieve. Expected to not be SQL-escaped.
 * @param mixed $default Optional value to return if option doesn't exist. Default false.
 * @param bool $use_cache Whether to use cache. Multisite only. Default true.
 * @return mixed Value set for the option.
 */
function get_site_option( $option, $default = false, $use_cache = true ) {
	global $wpdb;

	/**
	 * Filter an existing site option before it is retrieved.
	 *
	 * The dynamic portion of the hook name, $option, refers to the option name.
	 *
	 * Passing a truthy value to the filter will effectively short-circuit retrieval,
	 * returning the passed value instead.
	 *
	 * @since 2.9.0 As 'pre_site_option_' . $key
	 * @since 3.0.0
	 *
	 * @param mixed $pre_option The default value to return if the option does not exist.
	 */
 	$pre = apply_filters( 'pre_site_option_' . $option, false );

 	if ( false !== $pre )
 		return $pre;

	// prevent non-existent options from triggering multiple queries
	$notoptions_key = "{$wpdb->siteid}:notoptions";
	$notoptions = wp_cache_get( $notoptions_key, 'site-options' );

	if ( isset( $notoptions[$option] ) ) {

		/**
		 * Filter a specific default site option.
		 *
		 * The dynamic portion of the hook name, $option, refers to the option name.
		 *
		 * @since 3.4.0
		 *
		 * @param mixed $default The value to return if the site option does not exist
		 *                       in the database.
		 */
		return apply_filters( 'default_site_option_' . $option, $default );
	}

	if ( ! is_multisite() ) {

		/** This filter is documented in eshop-includes/option.php */
		$default = apply_filters( 'default_site_option_' . $option, $default );
		$value = get_option($option, $default);
	} else {
		$cache_key = "{$wpdb->siteid}:$option";
		if ( $use_cache )
			$value = wp_cache_get($cache_key, 'site-options');

		if ( !isset($value) || (false === $value) ) {
			$row = $wpdb->get_row( $wpdb->prepare("SELECT meta_value FROM $wpdb->sitemeta WHERE meta_key = %s AND site_id = %d", $option, $wpdb->siteid ) );

			// Has to be get_row instead of get_var because of funkiness with 0, false, null values
			if ( is_object( $row ) ) {
				$value = $row->meta_value;
				$value = maybe_unserialize( $value );
				wp_cache_set( $cache_key, $value, 'site-options' );
			} else {
				$notoptions[$option] = true;
				wp_cache_set( $notoptions_key, $notoptions, 'site-options' );

				/** This filter is documented in eshop-includes/option.php */
				$value = apply_filters( 'default_site_option_' . $option, $default );
			}
		}
	}

	/**
	 * Filter the value of an existing site option.
	 *
	 * The dynamic portion of the hook name, $option, refers to the option name.
	 *
	 * @since 2.9.0 As 'site_option_' . $key
	 * @since 3.0.0
	 *
	 * @param mixed $value Value of site option.
	 */
 	return apply_filters( 'site_option_' . $option, $value );
}

/**
 * Add a new site option.
 *
 * Existing options will not be updated. Note that prior to 3.3 this wasn't the case.
 *
 * @since 2.8.0
 *
 * @see add_option()
 *
 * @param string $option Name of option to add. Expected to not be SQL-escaped.
 * @param mixed $value Optional. Option value, can be anything. Expected to not be SQL-escaped.
 * @return bool False if option was not added and true if option was added.
 */
function add_site_option( $option, $value ) {
	global $wpdb;

	wp_protect_special_option( $option );

	/**
	 * Filter the value of a specific site option before it is added.
	 *
	 * The dynamic portion of the hook name, $option, refers to the option name.
	 *
	 * @since 2.9.0 As 'pre_add_site_option_' . $key
	 * @since 3.0.0
	 *
	 * @param mixed $value Value of site option.
	 */
	$value = apply_filters( 'pre_add_site_option_' . $option, $value );

	$notoptions_key = "{$wpdb->siteid}:notoptions";

	if ( !is_multisite() ) {
		$result = add_option( $option, $value );
	} else {
		$cache_key = "{$wpdb->siteid}:$option";

		// Make sure the option doesn't already exist. We can check the 'notoptions' cache before we ask for a db query
		$notoptions = wp_cache_get( $notoptions_key, 'site-options' );
		if ( ! is_array( $notoptions ) || ! isset( $notoptions[$option] ) )
			if ( false !== get_site_option( $option ) )
				return false;

		$value = sanitize_option( $option, $value );

		$serialized_value = maybe_serialize( $value );
		$result = $wpdb->insert( $wpdb->sitemeta, array('site_id' => $wpdb->siteid, 'meta_key' => $option, 'meta_value' => $serialized_value ) );

		if ( ! $result )
			return false;

		wp_cache_set( $cache_key, $value, 'site-options' );

		// This option exists now
		$notoptions = wp_cache_get( $notoptions_key, 'site-options' ); // yes, again... we need it to be fresh
		if ( is_array( $notoptions ) && isset( $notoptions[$option] ) ) {
			unset( $notoptions[$option] );
			wp_cache_set( $notoptions_key, $notoptions, 'site-options' );
		}
	}

	if ( $result ) {

		/**
		 * Fires after a specific site option has been successfully added.
		 *
		 * The dynamic portion of the hook name, $option, refers to the option name.
		 *
		 * @since 2.9.0 As "add_site_option_{$key}"
		 * @since 3.0.0
		 *
		 * @param string $option Name of site option.
		 * @param mixed  $value  Value of site option.
		 */
		do_action( "add_site_option_{$option}", $option, $value );

		/**
		 * Fires after a site option has been successfully added.
		 *
		 * @since 3.0.0
		 *
		 * @param string $option Name of site option.
		 * @param mixed  $value  Value of site option.
		 */
		do_action( "add_site_option", $option, $value );

		return true;
	}
	return false;
}

/**
 * Removes site option by name.
 *
 * @since 2.8.0
 *
 * @see delete_option()
 *
 * @param string $option Name of option to remove. Expected to not be SQL-escaped.
 * @return bool True, if succeed. False, if failure.
 */
function delete_site_option( $option ) {
	global $wpdb;

	// ms_protect_special_option( $option ); @todo

	/**
	 * Fires immediately before a specific site option is deleted.
	 *
	 * The dynamic portion of the hook name, $option, refers to the option name.
	 *
	 * @since 3.0.0
	 */
	do_action( 'pre_delete_site_option_' . $option );

	if ( !is_multisite() ) {
		$result = delete_option( $option );
	} else {
		$row = $wpdb->get_row( $wpdb->prepare( "SELECT meta_id FROM {$wpdb->sitemeta} WHERE meta_key = %s AND site_id = %d", $option, $wpdb->siteid ) );
		if ( is_null( $row ) || !$row->meta_id )
			return false;
		$cache_key = "{$wpdb->siteid}:$option";
		wp_cache_delete( $cache_key, 'site-options' );

		$result = $wpdb->delete( $wpdb->sitemeta, array( 'meta_key' => $option, 'site_id' => $wpdb->siteid ) );
	}

	if ( $result ) {

		/**
		 * Fires after a specific site option has been deleted.
		 *
		 * The dynamic portion of the hook name, $option, refers to the option name.
		 *
		 * @since 2.9.0 As "delete_site_option_{$key}"
		 * @since 3.0.0
		 *
		 * @param string $option Name of the site option.
		 */
		do_action( "delete_site_option_{$option}", $option );

		/**
		 * Fires after a site option has been deleted.
		 *
		 * @since 3.0.0
		 *
		 * @param string $option Name of the site option.
		 */
		do_action( "delete_site_option", $option );

		return true;
	}
	return false;
}

/**
 * Update the value of a site option that was already added.
 *
 * @since 2.8.0
 *
 * @see update_option()
 *
 * @param string $option Name of option. Expected to not be SQL-escaped.
 * @param mixed $value Option value. Expected to not be SQL-escaped.
 * @return bool False if value was not updated and true if value was updated.
 */
function update_site_option( $option, $value ) {
	global $wpdb;

	wp_protect_special_option( $option );

	$old_value = get_site_option( $option );

	/**
	 * Filter a specific site option before its value is updated.
	 *
	 * The dynamic portion of the hook name, $option, refers to the option name.
	 *
	 * @since 2.9.0 As 'pre_update_site_option_' . $key
	 * @since 3.0.0
	 *
	 * @param mixed $value     New value of site option.
	 * @param mixed $old_value Old value of site option.
	 */
	$value = apply_filters( 'pre_update_site_option_' . $option, $value, $old_value );

	if ( $value === $old_value )
		return false;

	if ( false === $old_value )
		return add_site_option( $option, $value );

	$notoptions_key = "{$wpdb->siteid}:notoptions";
	$notoptions = wp_cache_get( $notoptions_key, 'site-options' );
	if ( is_array( $notoptions ) && isset( $notoptions[$option] ) ) {
		unset( $notoptions[$option] );
		wp_cache_set( $notoptions_key, $notoptions, 'site-options' );
	}

	if ( !is_multisite() ) {
		$result = update_option( $option, $value );
	} else {
		$value = sanitize_option( $option, $value );

		$serialized_value = maybe_serialize( $value );
		$result = $wpdb->update( $wpdb->sitemeta, array( 'meta_value' => $serialized_value ), array( 'site_id' => $wpdb->siteid, 'meta_key' => $option ) );

		if ( $result ) {
			$cache_key = "{$wpdb->siteid}:$option";
			wp_cache_set( $cache_key, $value, 'site-options' );
		}
	}

	if ( $result ) {

		/**
		 * Fires after the value of a specific site option has been successfully updated.
		 *
		 * The dynamic portion of the hook name, $option, refers to the option name.
		 *
		 * @since 2.9.0 As "update_site_option_{$key}"
		 * @since 3.0.0
		 *
		 * @param string $option    Name of site option.
		 * @param mixed  $value     Current value of site option.
		 * @param mixed  $old_value Old value of site option.
		 */
		do_action( "update_site_option_{$option}", $option, $value, $old_value );

		/**
		 * Fires after the value of a site option has been successfully updated.
		 *
		 * @since 3.0.0
		 *
		 * @param string $option    Name of site option.
		 * @param mixed  $value     Current value of site option.
		 * @param mixed  $old_value Old value of site option.
		 */
		do_action( "update_site_option", $option, $value, $old_value );

		return true;
	}
	return false;
}

/**
 * Delete a site transient.
 *
 * @since 2.9.0
 *
 * @param string $transient Transient name. Expected to not be SQL-escaped.
 * @return bool True if successful, false otherwise
 */
function delete_site_transient( $transient ) {

	/**
	 * Fires immediately before a specific site transient is deleted.
	 *
	 * The dynamic portion of the hook name, $transient, refers to the transient name.
	 *
	 * @since 3.0.0
	 *
	 * @param string $transient Transient name.
	 */
	do_action( 'delete_site_transient_' . $transient, $transient );

	if ( wp_using_ext_object_cache() ) {
		$result = wp_cache_delete( $transient, 'site-transient' );
	} else {
		$option_timeout = '_site_transient_timeout_' . $transient;
		$option = '_site_transient_' . $transient;
		$result = delete_site_option( $option );
		if ( $result )
			delete_site_option( $option_timeout );
	}
	if ( $result ) {

		/**
		 * Fires after a transient is deleted.
		 *
		 * @since 3.0.0
		 *
		 * @param string $transient Deleted transient name.
		 */
		do_action( 'deleted_site_transient', $transient );
	}

	return $result;
}

/**
 * Get the value of a site transient.
 *
 * If the transient does not exist or does not have a value, then the return value
 * will be false.
 *
 * @since 2.9.0
 *
 * @see get_transient()
 *
 * @param string $transient Transient name. Expected to not be SQL-escaped.
 * @return mixed Value of transient
 */
function get_site_transient( $transient ) {

	/**
	 * Filter the value of an existing site transient.
	 *
	 * The dynamic portion of the hook name, $transient, refers to the transient name.
	 *
	 * Passing a truthy value to the filter will effectively short-circuit retrieval,
	 * returning the passed value instead.
	 *
	 * @since 2.9.0
	 *
	 * @param mixed $pre_site_transient The default value to return if the site transient does not exist.
	 *                                  Any value other than false will short-circuit the retrieval
	 *                                  of the transient, and return the returned value.
	 */
	$pre = apply_filters( 'pre_site_transient_' . $transient, false );

	if ( false !== $pre )
		return $pre;

	if ( wp_using_ext_object_cache() ) {
		$value = wp_cache_get( $transient, 'site-transient' );
	} else {
		// Core transients that do not have a timeout. Listed here so querying timeouts can be avoided.
		$no_timeout = array('update_core', 'update_plugins', 'update_themes');
		$transient_option = '_site_transient_' . $transient;
		if ( ! in_array( $transient, $no_timeout ) ) {
			$transient_timeout = '_site_transient_timeout_' . $transient;
			$timeout = get_site_option( $transient_timeout );
			if ( false !== $timeout && $timeout < time() ) {
				delete_site_option( $transient_option  );
				delete_site_option( $transient_timeout );
				$value = false;
			}
		}

		if ( ! isset( $value ) )
			$value = get_site_option( $transient_option );
	}

	/**
	 * Filter the value of an existing site transient.
	 *
	 * The dynamic portion of the hook name, $transient, refers to the transient name.
	 *
	 * @since 2.9.0
	 *
	 * @param mixed $value Value of site transient.
	 */
	return apply_filters( 'site_transient_' . $transient, $value );
}

/**
 * Set/update the value of a site transient.
 *
 * You do not need to serialize values, if the value needs to be serialize, then
 * it will be serialized before it is set.
 *
 * @since 2.9.0
 *
 * @see set_transient()
 *
 * @param string $transient Transient name. Expected to not be SQL-escaped.
 * @param mixed $value Transient value. Expected to not be SQL-escaped.
 * @param int $expiration Time until expiration in seconds, default 0
 * @return bool False if value was not set and true if value was set.
 */
function set_site_transient( $transient, $value, $expiration = 0 ) {

	/**
	 * Filter the value of a specific site transient before it is set.
	 *
	 * The dynamic portion of the hook name, $transient, refers to the transient name.
	 *
	 * @since 3.0.0
	 *
	 * @param mixed $value Value of site transient.
	 */
	$value = apply_filters( 'pre_set_site_transient_' . $transient, $value );

	$expiration = (int) $expiration;

	if ( wp_using_ext_object_cache() ) {
		$result = wp_cache_set( $transient, $value, 'site-transient', $expiration );
	} else {
		$transient_timeout = '_site_transient_timeout_' . $transient;
		$option = '_site_transient_' . $transient;
		if ( false === get_site_option( $option ) ) {
			if ( $expiration )
				add_site_option( $transient_timeout, time() + $expiration );
			$result = add_site_option( $option, $value );
		} else {
			if ( $expiration )
				update_site_option( $transient_timeout, time() + $expiration );
			$result = update_site_option( $option, $value );
		}
	}
	if ( $result ) {

		/**
		 * Fires after the value for a specific site transient has been set.
		 *
		 * The dynamic portion of the hook name, $transient, refers to the transient name.
		 *
		 * @since 3.0.0
		 *
		 * @param mixed $value      Site transient value.
		 * @param int   $expiration Time until expiration in seconds. Default 0.
		 */
		do_action( 'set_site_transient_' . $transient, $value, $expiration );

		/**
		 * Fires after the value for a site transient has been set.
		 *
		 * @since 3.0.0
		 *
		 * @param string $transient  The name of the site transient.
		 * @param mixed  $value      Site transient value.
		 * @param int    $expiration Time until expiration in seconds. Default 0.
		 */
		do_action( 'setted_site_transient', $transient, $value, $expiration );
	}
	return $result;
}
