<?php
/**
 * Craft 3 Multi-Environment
 * Efficient and flexible multi-environment config for Craft 3 CMS
 *
 * @author    nystudio107
 * @copyright Copyright (c) 2017 nystudio107
 * @link      https://nystudio107.com/
 * @package   craft3-multi-environment
 * @since     1.0.5
 * @license   MIT
 *
 * This file should be renamed to '.env.php' and it should reside in your root
 * project directory.  Add '/.env.php' to your .gitignore.  See below for production
 * usage notes.
 */

// Determine the incoming protocol
if (isset($_SERVER['HTTPS']) && (strcasecmp($_SERVER['HTTPS'], 'on') === 0 || $_SERVER['HTTPS'] == 1)
    || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') === 0
) {
    $protocol = "https://";
} else {
    $protocol = "http://";
}
// Determine the server hostname
switch ($_SERVER['HTTP_HOST']) {
    case 'REPLACE_ME_VALID_HOSTNAME_1':
    case 'REPLACE_ME_VALID_HOSTNAME_2':
        $httpHost = $_SERVER['HTTP_HOST'];
        break;
    default :
        $httpHost = '';
        break;
}
// The $craftEnvVars are all auto-prefixed with CRAFTENV_ -- you can add
// whatever you want here and access them via getenv() using the prefixed name
$craftEnvVars = [
    // The Craft environment we're running in ('local', 'staging', 'live', etc.).
    'CRAFT_ENVIRONMENT' => 'REPLACE_ME_CRAFT_ENVIRONMENT',

    // The database driver that will used ('mysql' or 'pgsql')
    'DB_DRIVER' => 'REPLACE_ME_DB_DRIVER',

    // The database server name or IP address (usually this is 'localhost' or '127.0.0.1')
    'DB_SERVER' => 'REPLACE_ME_DB_SERVER',

    // The database username to connect with
    'DB_USER' => 'REPLACE_ME_DB_USER',

    // The database password to connect with
    'DB_PASSWORD' => 'REPLACE_ME_DB_PASSWORD',

    // The name of the database to select
    'DB_DATABASE' => 'REPLACE_ME_DB_DATABASE',

    // The database schema that will be used (PostgreSQL only, usually 'public')
    'DB_SCHEMA' => 'REPLACE_ME_DB_SCHEMA',

    // The prefix that should be added to generated table names (usually '', it's only necessary
    // if multiple things are sharing the same database)
    'DB_TABLE_PREFIX' => 'REPLACE_ME_DB_TABLE_PREFIX',

    // The port to connect to the database with. Default ports are 3306 for MySQL and 5432 for PostgreSQL.
    'DB_PORT' => 'REPLACE_ME_DB_PORT',

    // The secure key Craft will use for hashing and encrypting data, see:
    // https://craftcms.com/docs/config-settings#validationKey
    'SECURITY_KEY' => 'REPLACE_ME_SECURITY_KEY',

    // The site url to use; it can be hard-coded as well
    'SITE_URL' => $protocol . $httpHost . '/',

    // The base url environmentVariable to use for Assets; it can be hard-coded as well
    // You will also need to configure `config/volumes.php` for your Asset Volumes
    'BASE_URL' => $protocol . $httpHost . '/',

    // The base path environmentVariable for Assets; it can be hard-coded as well
    // You will also need to configure `config/volumes.php` for your Asset Volumes
    'BASE_PATH' => realpath(dirname(__FILE__)) . '/web/',
];

// Set all of the .env values, auto-prefixed with `CRAFTENV_`
foreach ($craftEnvVars as $key => $value) {
    putenv("CRAFTENV_{$key}={$value}");
}

/**
 * For production environments, this .env.php file can be used, or preferably,
 * (for security & speed), set the $_ENV variables directly from the server config.
 *
 * Apache - inside the <VirtualHost> block:

SetEnv CRAFTENV_CRAFT_ENVIRONMENT "REPLACE_ME_CRAFT_ENVIRONMENT"
SetEnv CRAFTENV_DB_DRIVER "REPLACE_ME_DB_DRIVER"
SetEnv CRAFTENV_DB_SERVER "REPLACE_ME_DB_SERVER"
SetEnv CRAFTENV_DB_USER "REPLACE_ME_DB_USER"
SetEnv CRAFTENV_DB_PASSWORD "REPLACE_ME_DB_PASSWORD"
SetEnv CRAFTENV_DB_DATABASE "REPLACE_ME_DB_DATABASE"
SetEnv CRAFTENV_DB_SCHEMA "REPLACE_ME_DB_SCHEMA"
SetEnv CRAFTENV_DB_TABLE_PREFIX "REPLACE_ME_DB_TABLE_PREFIX"
SetEnv CRAFTENV_DB_PORT "REPLACE_ME_DB_PORT"
SetEnv CRAFTENV_SECURITY_KEY "REPLACE_ME_SECURITY_KEY"
SetEnv CRAFTENV_SITE_URL "REPLACE_ME_SITE_URL"
SetEnv CRAFTENV_BASE_URL "REPLACE_ME_BASE_URL"
SetEnv CRAFTENV_BASE_PATH "REPLACE_ME_BASE_PATH"

 * Nginx - inside the server {} or location ~ \.php$ {} block:

fastcgi_param CRAFTENV_CRAFT_ENVIRONMENT "REPLACE_ME_CRAFT_ENVIRONMENT";
fastcgi_param CRAFTENV_DB_DRIVER "REPLACE_ME_DB_DRIVER";
fastcgi_param CRAFTENV_DB_SERVER "REPLACE_ME_DB_SERVER";
fastcgi_param CRAFTENV_DB_USER "REPLACE_ME_DB_USER";
fastcgi_param CRAFTENV_DB_PASSWORD "REPLACE_ME_DB_PASSWORD";
fastcgi_param CRAFTENV_DB_DATABASE "REPLACE_ME_DB_DATABASE";
fastcgi_param CRAFTENV_DB_SCHEMA "REPLACE_ME_DB_SCHEMA";
fastcgi_param CRAFTENV_DB_TABLE_PREFIX "REPLACE_ME_DB_TABLE_PREFIX";
fastcgi_param CRAFTENV_DB_PORT "REPLACE_ME_DB_PORT";
fastcgi_param CRAFTENV_SECURITY_KEY "REPLACE_ME_SECURITY_KEY";
fastcgi_param CRAFTENV_SITE_URL "REPLACE_ME_SITE_URL";
fastcgi_param CRAFTENV_BASE_URL "REPLACE_ME_BASE_URL";
fastcgi_param CRAFTENV_BASE_PATH "REPLACE_ME_BASE_PATH";

 */
