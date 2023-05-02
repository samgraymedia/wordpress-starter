<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', '' );

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
define( 'AUTH_KEY',         'PAyPX)A T>^: ynts&XKu7pfY0JPL/Q;m^bm<zCJ).%b/(2GZIl3Y+mB?x&=<{MD' );
define( 'SECURE_AUTH_KEY',  '{zwML;sknZj?zmt@wbTrk9*r7NN[gH*NX/TuSU+9&g[;&lK`jTd?{cO0GLa+oG[|' );
define( 'LOGGED_IN_KEY',    '/tvhDzEkV3g>cXo=OwMihk4x[#rqT>!.~<bdht.^kTl-5EyYx~Bi2H^gm6C~&Y/2' );
define( 'NONCE_KEY',        '(Je,.+VNF3meUmF 38,1f-fqdju1MV7&.E=.6uuETFC*I54@?d%uyUt*HBxstC>4' );
define( 'AUTH_SALT',        'm+LlP%79lI0msr]T:.`$_ASqs8PbUNaf#SHw@2A;<M1#G4.Q}9A2)LvSpDrui^#,' );
define( 'SECURE_AUTH_SALT', 'Zjh*}5^p)JZ^#rDxYuQ}+s*O<QLQdY9<dXIyVnxGN}&f2)VB^/z!wZ:11%|!5h/F' );
define( 'LOGGED_IN_SALT',   '}[0`G!+`RW[@-ZpM&ycb!3^vL+pX`8zls3Ipiv+q({ze]fFB{8RUTmy8(R6$J4+G' );
define( 'NONCE_SALT',       'we+-h^Q4ByJ.WODilM!UR2XFxD{}i5biMu0&1Jg(ZW|=S`dEOhW&[3#s.nUm!C:a' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



	define( 'ACF_PRO_LICENSE', 'b3JkZXJfaWQ9MTQ1NjQyfHR5cGU9cGVyc29uYWx8ZGF0ZT0yMDE4LTExLTI0IDIxOjUzOjU0' );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
