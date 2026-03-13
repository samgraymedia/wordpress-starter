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
define( 'DB_NAME', 'store-tes' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

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
define( 'AUTH_KEY',         'PE:hP2g #|bv$GkH=%e!t)p1jg <ST;Ln>U|j#o2=K-onl$BZ}%o^q/cgzpG*)}R' );
define( 'SECURE_AUTH_KEY',  '<,vfrpwkE2o2O*bf4vbLWll`w1aprX&bkX/14uNhSZ7nK1ww)IkF$4LBaTbQjjX<' );
define( 'LOGGED_IN_KEY',    '_J}T?vd< wt3|c?wc9IpJ0^^c7Z]O]HT7J@y$=gE;Q@EZb72($q`z3a<w3{yGg~y' );
define( 'NONCE_KEY',        '>~wK@O`g4z`~B<z#ga#ip/F|3/bkX^wDcG5nck+U1!:.Hlql:`yJa^5=-S!]`u&0' );
define( 'AUTH_SALT',        '9:&!0a`]:B[PEm[*y*)v[WX#qi0 5iv4zfVjX}EL&DAHEdtxl0QRY+N<_,Rw6*Ed' );
define( 'SECURE_AUTH_SALT', 'rPV>c?KWWD@4<Pfo6Jq1$19nI}O,/(:`HRYcgC3dIQvFX6*=p(jhJ$$rB_h2]5AH' );
define( 'LOGGED_IN_SALT',   'u)WeT0FyG>k>Iy0pRJe$$l.d4#r=*p:n,j#uzG/7lf1)QY4e7P-x^X2HzK@1d7Zn' );
define( 'NONCE_SALT',       '/6/Jui:q9>/b,]@R|(?J*xc:)];uhQB3l-A}Lsv?&i(0y9x/4P 3Dv]eZ1 ,a&oT' );

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


// Enable WP_DEBUG mode
define('WP_DEBUG', false);

// Enable Debug logging to the /wp-content/debug.log file
define('WP_DEBUG_LOG', true);

// Disable display of errors and warnings 
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors',0);

// Use dev versions of core JS and CSS files (only needed if you are modifying these core files)
define('SCRIPT_DEBUG', false);


/* Add any custom values between this line and the "stop editing" line. */



	define( 'ACF_PRO_LICENSE', 'b3JkZXJfaWQ9MTQ1NjQyfHR5cGU9cGVyc29uYWx8ZGF0ZT0yMDE4LTExLTI0IDIxOjUzOjU0' );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
