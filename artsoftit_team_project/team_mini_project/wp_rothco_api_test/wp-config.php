<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wp_rothco_api_test' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '_}4@/1=<PkgS?psk}Gm<{eHRiFi}sJ@aRABlh8y3oq5}<Zk0yM`Fuye& wQiyD(F' );
define( 'SECURE_AUTH_KEY',  '~Fr4dgerryxP0.)Uuk.(h&lYGD&xVsvL4.(oxHd8zbdWfI_8;gz0D!Pfw=u!z6O[' );
define( 'LOGGED_IN_KEY',    ':%}TIMBn]|r{UfTY8,xD*M-8eB;=b.QNKZGiF=d;_^)S74_9@V$2ed>1i$L?f1d@' );
define( 'NONCE_KEY',        '+IthOg<tYH}N*oSUfSya)q*`,6)7q!y3#i3y[6x,)!|2_!k?G~Mm0CKla~M]pT`V' );
define( 'AUTH_SALT',        'xulR0O)nX&5Nd2R>Mm=HATZ&L@WqkU#*6Ro_D0rn6!{HlNU3)nm{O%)tO76]DM+(' );
define( 'SECURE_AUTH_SALT', '2s0@6Vi^+tghE0QtVpWOdW0.273Ia9 49xBK.z6LC8u=SEW?gVxzw|BWp)J`|p)!' );
define( 'LOGGED_IN_SALT',   'BvI!QU|ctY_oF&6wPKiZy[O}xR?aYG8.ESH Ejms|<E?r@ptzBbc[GV=!|~fduX8' );
define( 'NONCE_SALT',       '0OFojf=!FoJQQwQjlic^PI/XFE9-G}*b_QVQZG4dZ3L8/QL1?5;&h3iI73b~T9ul' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
