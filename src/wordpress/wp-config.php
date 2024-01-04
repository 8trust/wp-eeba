<?php
// ** MySQL settings - You can get this info from your web host ** //
const ENV_PRODUCTION = 'production';
const ENV_TESTING = 'testing';
const ENV_LOCAL = 'local';
$config = [
    ENV_PRODUCTION => [
        'DB_NAME' => '',
        'DB_USER' => '',
        'DB_PASSWORD' => '',
        'DB_HOST' => '',
        'WP_HOME' => '',
        'WP_DEBUG' => false,
        'WP_CACHE' => true
    ],
    ENV_TESTING => [
        'DB_NAME' => 'eeba',
        'DB_USER' => 'eeba',
        'DB_PASSWORD' => 'aizTUSEe',
        'DB_HOST' => 'localhost',
        'WP_HOME' => 'https://eeba.testing.8trust.com',
        'WP_DEBUG' => false,
        'WP_CACHE' => false
    ],
    ENV_LOCAL => [
        'DB_NAME' => 'wordpress',
        'DB_USER' => 'root',
        'DB_PASSWORD' => 'p4ssw0rd!',
        'DB_HOST' => 'db:3306',
        'WP_HOME' => 'http://localhost',
        'WP_DEBUG' => true,
        'WP_CACHE' => false
    ]
];

if (getenv('ENV')){
	define( 'WP_DEBUG_DISPLAY', false );
	define( 'WP_DEBUG_LOG', true );
	define('ENV',getenv('ENV'));
}else if (stristr($_SERVER['SERVER_NAME'], "...")) {
  define('ENV', 'production');
} elseif (stristr($_SERVER['SERVER_NAME'], "eeba.testing.8trust.com")) {
  define('ENV', 'testing');
} else {
  define('ENV', 'local');
}


if (!key_exists(ENV, $config)) {
  die('Wrong enviroment detection');
}

foreach ($config[ENV] as $k => $v) {
  define($k, $v);
}/**
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
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',         'put your unique phrase here' );
define( 'SECURE_AUTH_KEY',  'put your unique phrase here' );
define( 'LOGGED_IN_KEY',    'put your unique phrase here' );
define( 'NONCE_KEY',        'put your unique phrase here' );
define( 'AUTH_SALT',        'put your unique phrase here' );
define( 'SECURE_AUTH_SALT', 'put your unique phrase here' );
define( 'LOGGED_IN_SALT',   'put your unique phrase here' );
define( 'NONCE_SALT',       'put your unique phrase here' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
