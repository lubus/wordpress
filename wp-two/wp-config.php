<?php
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
define('DB_NAME', 'wordpresstwo');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'fS?`h{cHr:l^ekj&QUHL2$[nh.D69B?4,O(t2U}IR&- pQ]GG_R.4bM`]YXl9xOG');
define('SECURE_AUTH_KEY',  'H)7S0N[AcK;:F+u?,|wk9uz^o*k+fsp(AL LV4u[U(Y]A}[YslCRNo:V.b~Ws+*&');
define('LOGGED_IN_KEY',    'ijBatadP`a7O-0$pQptn}Y@1bOT@%]w7*E8-EVv[|<h:hFtZx?MO6_< YkO~/uZ[');
define('NONCE_KEY',        'W%?T#~5x-(G-RT-^!4s-q^v}[~h9Wid$lpxdtzSSE5[O|N7F6d`^;5(hW^!AEza5');
define('AUTH_SALT',        '`>VDI6%{l iFd*<s: >?MHL{h+`q9`EXBrg1^K:+[g@ex#qflXSId6$i-U*vZN$@');
define('SECURE_AUTH_SALT', 'al)-Y-vn{+&KH-B7+1V_f$x57Rn^I7O{vV6mpe@2Yx(=K{tg(w*GRTz*_%73Cw1?');
define('LOGGED_IN_SALT',   'c2>+7_.NJy@6WA1<HWt>U|IHiD~d@fB+]8cZ+&l!-O[*>A)NpfY)+<,tXT=d=9>8');
define('NONCE_SALT',       'p+bbi.J9k6VrYTIVo#Zq9/?r,Ex< ^HBMMr(M1/C7l6Uf9nY(ZOVeJ6tS.}A[_]$');
define( 'WP_PLUGIN_DIR', 'C:/xampp/htdocs/wordpress/applications' );
define( 'WP_PLUGIN_URL', 'ashis.com/applications' );
define( 'PLUGINDIR', 'C:/xampp/htdocs/wordpress/applications' );
define( 'WP_CONTENT_DIR', 'C:/xampp/htdocs/wordpress/wp-two' );
define( 'WP_CONTENT_URL', 'ashis.com/wp-two' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', 'C:/xampp/htdocs/wordpress/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
