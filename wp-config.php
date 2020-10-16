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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

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
define( 'AUTH_KEY',         'D!mI AXAgzT_z3zTDm}0SSvC/n1L/TK3zf/J3zZNodx{dwH67uB{{PN{5BwBiDS)' );
define( 'SECURE_AUTH_KEY',  'gb6Ok_,L+x@ @=1$??&fwdm_(ng+dFE7wV4:@1.1Yj=|2=JQT 8$30}CT-y&]:6I' );
define( 'LOGGED_IN_KEY',    'y;F0woIS;@(Y8aqmn%[gV>c>O{BN(;o#ZH=.7NKVp@4V6ut@Y`0Q=@G|7pasR1D0' );
define( 'NONCE_KEY',        ',B)g.T`a.lUd m~DoH,e7n@){h2:rT)2ggzaK<iV+u`4Pj]mSabs,&ChYd[9snH_' );
define( 'AUTH_SALT',        'W?(=2N8cKSrA+w$N>vI/l3Ax`o$d5fLO8?BslqmR1Q.y.@O<,s*7WO94$Ok;@Z7y' );
define( 'SECURE_AUTH_SALT', 'w^B6hca}IeWghg/+LO=kR[)s+@C$=^n@,2}$j2Pd>bD^7x6sIrUxzK)b-<^g*&sy' );
define( 'LOGGED_IN_SALT',   '5sukos/%EGoQRBT[D&rEh`,&E[BU]uhA+~5`jWv36*vbzJxe%[Ax%aviay.nH)*J' );
define( 'NONCE_SALT',       '(6(#t~WYV^3,G|=W9f.nQ7jbKf9Px|l|By}:FoXkjtR(<*7}NIDkBaVW/]z?r9(j' );

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
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
