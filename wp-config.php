<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
//define('WP_CACHE', true); //Added by WP-Cache Manager
//define( 'WPCACHEHOME', '/home/saekip/rivnodennya17.com/www/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('DB_NAME', 'rivnodennya17');

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
define('AUTH_KEY',         'WW]rRGW.Yur]1-vz/k)InyR,!=^PXYGwjWuJi|Zk^6,7,?srilctGU#u4RDE5}L9');
define('SECURE_AUTH_KEY',  ';:6$C~eTtE56m)07gB42C*RO=Yo[Rg`Qg<T9-i Ee8UJzsq$qqdJ-|]6H.ETwxlI');
define('LOGGED_IN_KEY',    '>Yk+NfRq[AT,[OqK*Sgc@pa9~|@[G)w?M53$a[>P}Z#e$z2T6c*R,X<FVW7N}WH<');
define('NONCE_KEY',        '<@VyT%~DZ+h|mzuW<UFS8FMy836|}-w5*oA6)v7bd3Or}NN3^~](1{S^X;)(=ll}');
define('AUTH_SALT',        'Te+Y]@;tjuvGaZ~=:w|MOJEjpCNw#<9#i/qhFri0S|scTf&vN?>kPdak&f|$+dM%');
define('SECURE_AUTH_SALT', 'q5seZzNnf1AO-c(L:[g9e.=C2S %,W&_cVqw{[E|@#-iKrVm>l(~*.Wc$7+!R{Rx');
define('LOGGED_IN_SALT',   '&Z)O`dYG%<D-KmK0[,N2jX[8V&;Tw=NP}Je3q 37WY#Ssg!JO7KCh`H/QiDf$iRD');
define('NONCE_SALT',       '!;^+4PT<lMr|kyK?|!Pqk&r?DFjAmj0^+NS;Vb-Vb;-3G{5Eoe|b|1S4>rKA/O7p');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);
// log php errors, prevent display to user, does not work if WP_DEBUG is true
@ini_set('error_reporting', 4339 );	// moderate error reporting level, ignores notices
@ini_set('html_errors', 'Off');		// startup errors off
@ini_set('display_errors','Off');	// disable public display of errors
@ini_set('log_errors','On');		// enable php error logging
// path to server-writable log file, example shown, must be changed to a valid path for your server
@ini_set('error_log','/home/content/123456/logs/PHP_errors.log');
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('DOMAIN_CURRENT_SITE', 'rivnodennya17.com');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);
define( 'AUTOMATIC_UPDATER_DISABLED', true );
define('WP_ALLOW_MULTISITE', true);
define('SUNRISE', 'on');
define('ADMIN_COOKIE_PATH', '/');
define('COOKIE_DOMAIN', '');
define('COOKIEPATH', '');
define('SITECOOKIEPATH', '');

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
