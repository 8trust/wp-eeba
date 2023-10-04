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
        'DB_NAME' => '',
        'DB_USER' => '',
        'DB_PASSWORD' => '',
        'DB_HOST' => 'localhost',
        'WP_HOME' => '',
        'WP_DEBUG' => false,
        'WP_CACHE' => true
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
} elseif (stristr($_SERVER['SERVER_NAME'], "model.testing.8trust.com")) {
  define('ENV', 'testing');
} else {
  define('ENV', 'local');
}


if (!key_exists(ENV, $config)) {
  die('Wrong enviroment detection');
}

foreach ($config[ENV] as $k => $v) {
  define($k, $v);
}
