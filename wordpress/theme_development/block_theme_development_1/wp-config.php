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
define( 'DB_NAME', 'wp_practice_blocktheme_1' );

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
define( 'AUTH_KEY',         'StFtC`itWM$.0.$4I%n}yNmKYUh#>6D(^XByegL2-C-_<8S}<L?2`4Efh-LS}l{n' );
define( 'SECURE_AUTH_KEY',  '4rR(?vu0cP76J!aZ:cHy8uX4fH-[#H1KVR-dxZHd.S,>4FT>3~#Lz}NB`RKVX]uR' );
define( 'LOGGED_IN_KEY',    'K#U^@&T WU&jl)S[ct%m)(/4^C2%O||wuzTj^H*bI<4Av.$i?}(z19@+.<t!*/{C' );
define( 'NONCE_KEY',        ',I@nH`mY*2Z9?DE;#uq().Eb*eg#G,.cB8DS|PgngD?~.%R)2OuY:[W[nfA{ew0]' );
define( 'AUTH_SALT',        'paD -|lQ?D$?$&<*ZnT<lNty<Y-d8zqycj8V/B )Y`q$-E,lBRmK*AWBm/iM<9[_' );
define( 'SECURE_AUTH_SALT', 'i`!^Z8>1)i#cqd[:fYu~}-v}Ki(d4^2`TXI$(a9u~K<GirEmPKEn[TNM9Eo:kueq' );
define( 'LOGGED_IN_SALT',   '~3d&W,ySl6z0FHMXxK-*(JMC8LXInmxh?%i&.Sy7iYq>76,3+v`)<6FP,)NeueOF' );
define( 'NONCE_SALT',       '*Jk8~zjVjT6Ej<nr@?(j|kq80}|+3IKgg3(AQ/jg~2uz`$epzUmN)]J*rM%b[#S%' );

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
