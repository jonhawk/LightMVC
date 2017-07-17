<?php

/**
 * Configuration
 *
 */

/**
 * Error reporting
 */
define('ENVIRONMENT', 'development');

if (ENVIRONMENT == 'development' || ENVIRONMENT == 'dev') {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
} elseif (ENVIRONMENT == 'production' || ENVIRONMENT == 'prod') {
    error_reporting(0);
    ini_set("display_errors", 0);
}

/**
 * URL_PUBLIC_FOLDER - folder visible to public, sub folder of project root
 * URL_PROTOCOL
 * URL_DOMAIN
 * URL_SUB_FOLDER - public_html sub folder
 * URL - auto-detected URL with trailing slash
 */
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
define('URL_PUBLIC_FOLDER', 'public');
define('URL_PROTOCOL', $protocol);
define('URL_DOMAIN', $_SERVER['HTTP_HOST']);
define('URL_SUB_FOLDER', str_replace(URL_PUBLIC_FOLDER, '', dirname($_SERVER['SCRIPT_NAME'])));
define('URL', URL_PROTOCOL . URL_DOMAIN . URL_SUB_FOLDER);

/**
 * Database configuration
 */
define('DB_TYPE', 'mysql');
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'dbname');
define('DB_USER', 'dbuser');
define('DB_PASS', 'dbpass');
define('DB_CHARSET', 'utf8');
define('DB_PRE', '');

/**
 * Language status and default language key
 */
define('LANGUAGE_ENABLED', false);
define('DEFAULT_LANGUAGE', 'lv');

/**
 * Image folders
 */
define('IMAGE_FOLDER',            APP_PUB.'assets/images');
define('IMAGE_FOLDER_URL',        URL.'assets/images');
define('IMAGE_FOLDER_CACHE',      APP_PUB.'assets/cache');
define('IMAGE_FOLDER_CACHE_URL',  URL.'assets/cache');

/**
 * Default METAs
 */
define('META_TITLE',        'My Home Page');
define('META_DESCRIPTION',  '');
define('META_KEYWORDS',     '');
define('META_AUTHOR',       'My Home Page');