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
define( 'DB_NAME', 'dosa_crepe_cafe' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'jIwlGM.QijWfB$S:+F!8L)F}<~DzO|}4Ei;fR}UU0)3n>OdH@ =J#MlK>0FGk2,q' );
define( 'SECURE_AUTH_KEY',  'pf)zN,J,Z2f2$[!FUt~[]|wwIqI1/>l/-8XvH`zUUvEr&IT#1H_5q$Z`I3x9gd>g' );
define( 'LOGGED_IN_KEY',    'D3{7*J,(grSLr-~=yTqU~Hp/ 0e`ovcpI:U5@pDKI<}LJ6Doy7>z@iu3h2hvC{E4' );
define( 'NONCE_KEY',        'b,!Bra<wT*^h&mT.FU_Tmh5Zr>lHx:dOHW,an;Rc1TF4c{he8<+t1^P#B^nF{a}{' );
define( 'AUTH_SALT',        '97e4<|T:;k #UdVbOSgM4R@^hk-v{^AM1wfg_}_rRF[VaFvW:_s KK_?Zgs>aUs}' );
define( 'SECURE_AUTH_SALT', 'y-j1b@0FOs&c~qb,)97(>B=t?J<H1trjIH *+j{nk26Mc;dMFL1c/Wh|x-Tb0=J{' );
define( 'LOGGED_IN_SALT',   'Iz,D;<!J_%@Z*mR^9AWmN&[O@>g=gUa@fqJeju{aPi@GQ{gytvdHu2FnphrD7=nc' );
define( 'NONCE_SALT',       'MK#<b!+B<?<H~g>~Li2?UQ/G!i[%<@_2ue#}1T?H{2xh7k]E_}rnm^H:B{W=#sIv' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
