<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'factoryr_withoutbogo');

/** MySQL database username */
define('DB_USER', 'factoryr_bogo');

/** MySQL database password */
define('DB_PASSWORD', 'news@0071');

define('WP_MEMORY_LIMIT', '2048M');

/** MySQL hostname */
define('DB_HOST', 'localhost');

//--- Define post revisions false -----
define( 'WP_POST_REVISIONS', false );


/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

//--- Define post revisions false -----
define( 'WP_POST_REVISIONS', false );
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '!|,If%?n+ 0vbncYf21PyM[C x>J;U%d2)*FwCQ(|09vwt&=r~%d2Dq}6mIRGyi:');
define('SECURE_AUTH_KEY',  'F2k2+4#0>F++=0WA-|)ItpHI5A+J@*Yl9Mhyi:6sUQ->Yp`I&]x]AaDHmhgn&1-j');
define('LOGGED_IN_KEY',    'a2ZnhDsnP,TG7Xgm8h6HR|2;M JT;i^Taf(95UzUWhlQ^+w897ZXQ_s1>_GD|vac');
define('NONCE_KEY',        ' =Lq}Kd1GDtP&yrPjzlOw,3w,?.}ul5a-Exo+u^_-.If.`=,Y1|}Z~3t[b Ynh3`');
define('AUTH_SALT',        '/7cT?*=tSkh!x{N%Jm^~ Rj3IQ:#uk3297w*2H5+m;@wG}{+.6N/6$vi0B4^-Z[+');
define('SECURE_AUTH_SALT', '0b-Ub&9k,X(K&!:otG?|WldCtgg6?&)|nSbTrx!()Vgl-LYV#{:/9Y%[O`~bO>mD');
define('LOGGED_IN_SALT',   '|UBLzZ@~4M<$]i!GXd0`j%Tzk8%QU@ms?)F&5CV0VuRf-3&60tP9g=SS>ge>fegE');
define('NONCE_SALT',       '$~_t|^//hw$)%j}WFL:PF%Y-OJ:,Z57HFIC4GuYy(+%HdSFto#y<TsC(iZt+rX9?');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'eshop_';

define( 'WP_CONTENT_DIR', $_SERVER['DOCUMENT_ROOT'].'/eshop-frontent' );
define( 'WP_CONTENT_URL', 'http://beta.factoryrush.com/eshop-frontent');

define( 'WP_PLUGIN_DIR', $_SERVER['DOCUMENT_ROOT'].'/eshop-frontent/applications' );
define( 'WP_PLUGIN_URL', 'http://beta.factoryrush.com/eshop-frontent/applications');

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG',false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

##-- CHANGE THEME DIRECTORY FILE NAME ----------##
register_theme_directory($_SERVER['DOCUMENT_ROOT'].'/eshop-frontent/eshopthemes');
