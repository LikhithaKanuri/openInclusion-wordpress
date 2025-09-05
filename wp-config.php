<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('WP_CACHE', true);
define( 'WPCACHEHOME', '/home/customer/www/staging4.openinclusion.com/public_html/wp-content/plugins/wp-super-cache/' );
define('DB_NAME', 'dbt6qqazh87fvr');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define( 'DB_PASSWORD', '' );

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
define('AUTH_KEY',         'hrvt4hsqddmpytwpyv7sfyir5x9oe2adfpyip0crwwc6oczrmvfgnwg9ze3tkftv');
define('SECURE_AUTH_KEY',  'jk8ky2xtvfh18u3tdcu8oaotwcvw6oyalvmviwiloiy7kqefwipeeen7stxl796o');
define('LOGGED_IN_KEY',    'v2dnwnhqu7cqkuzmkkh4517aazoprfejdtaq2gfie96qkhg66nbnvdaqiqsylaee');
define('NONCE_KEY',        'z7nrgj8kcult2jcamgto9ofhrnyzvrfxhfrpcaftyuukmntcbecmhhxhbul82jqv');
define('AUTH_SALT',        'ffp2fp0igr0zmze9jsdvniw7ar85wowzfxr3szllrzwqlpji9afwb0mrnmupxvwc');
define('SECURE_AUTH_SALT', 'j3tvwikhdtkqoyzjfpdl3gh2tjttowhvriizvlutsekhcgc0tpspqgknwniifoye');
define('LOGGED_IN_SALT',   '5hesgvnwrhufukhtqdthw9zu9ijbfwni6iy0hrlwxh30otupxa2qihkix42iu62m');
define('NONCE_SALT',       'qwyza1njflzjbjztq4mzy0mzgyywrkzduwmmu0n2i1nzu0njbimtmyzmqxzjg2mz');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'qgj_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
// define('WP_DEBUG', false);
define( 'WP_MEMORY_LIMIT', '128M' );
define('FORCE_SSL_ADMIN', true);

define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);

define('FS_METHOD', 'direct');

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
@include_once('/var/lib/sec/wp-settings-pre.php'); // Added by SiteGround WordPress management system
require_once(ABSPATH . 'wp-settings.php');



@include_once('/var/lib/sec/wp-settings.php'); // Added by SiteGround WordPress management system
