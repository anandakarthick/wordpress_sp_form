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
define( 'DB_NAME', 'wordpress_sp_form' );

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
define( 'AUTH_KEY',         'Dyuoa#8FIR[OgS9^0G-1RAF&|INs;70CW9-GNi-9,}p>JF>%Zmjl?%U4K[5+e54(' );
define( 'SECURE_AUTH_KEY',  '>-Vd[;8;U}nQpR}&g;n|b98J+7a]B4cgh^8qrKSbrIbc_hehU;.<&_ikcPp.mn@?' );
define( 'LOGGED_IN_KEY',    '[qh8iheJ_7]& cbssXfv:~p-R 1.QfSmVI]eCcRQ%2pckbziGy6fi 5&RacEH~K3' );
define( 'NONCE_KEY',        'X<Ehy,=z~Aje:Q L(1d>,rNwu8:_jYCZU}BfTo}fTpz5=x-%q^4(D@w}7BUBpDeB' );
define( 'AUTH_SALT',        '>Wkkp_B e30]_=7K#0-t|(B}t3A=|k66WwD_x9[&yn`?z$qj{K15`X|P&*E_`WMG' );
define( 'SECURE_AUTH_SALT', 'UY0MCu2ciL)nIp{NIcXj,E j_X4>ZGHk2)q xg]3pEd/^y|eflE}GCC.;m#dHd/m' );
define( 'LOGGED_IN_SALT',   'K8LA<xEI(11N_|$&1(uPIBZDwARvf Y(uJNCMj#h/1)z W;PoXE!s*?tL&v^Uhg]' );
define( 'NONCE_SALT',       'iw6)y*?dzkFzq86(+tvq)g8:<kQ4nh~~6lO;^u{* }XJAQRzy8SWc_Yb@mPj!.ZK' );

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
