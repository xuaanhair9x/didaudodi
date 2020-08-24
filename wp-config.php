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
define( 'DB_NAME', 'thieuthi_wp199' );

/** MySQL database username */
define( 'DB_USER', 'thieuthi_wp199' );

/** MySQL database password */
define( 'DB_PASSWORD', '[5.SK5qrp6' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',         'ebrjas1rizck0nqomuzwee6jkzu3ielhltw5cblentby2aansfv8suvqyu2d6pnr' );
define( 'SECURE_AUTH_KEY',  '583qj4sudsmmpyww9hjbqvmedd3px84uzg9jrya7f8ipiwcokumgl5rz4khxodl6' );
define( 'LOGGED_IN_KEY',    '6ykqfmo6aykgnawxnzezgyz9s7naupcpolnmmoo8yynygkox4xow8bhiyz0agdfo' );
define( 'NONCE_KEY',        'm9av3h4qmo05jewjgn01n47qoca9fnejs9isk8xrwf2krikrl9rul2ybqjzefqrk' );
define( 'AUTH_SALT',        '3x9bmvtmq2onsyphhqovoofm4hr2ugikbowake0hjrojvebndaqntbucupxdriuc' );
define( 'SECURE_AUTH_SALT', 'az1dgwkmhufjqau1bkemcj6whb56mwykntjqvftbgrvt7zvc7qjedxxowdlszaod' );
define( 'LOGGED_IN_SALT',   'zsv9vsvpayfk82gb3io8vatwsnne1a3hccnb2ohryunimkjztmuyrg9ngc7flhs1' );
define( 'NONCE_SALT',       'ag6yoy693lwvgk60abhxgvmwcsovpuct9jobzdxokzjvvp5odsp093ydzcum1xxs' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpok_';

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
