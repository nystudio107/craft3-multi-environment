<?php
/**
 * $_ENV constants are loaded by craft3-multi-environment from .env.php via web/index.php
 *
 * @author    nystudio107
 * @copyright Copyright (c) 2017 nystudio107
 * @link      https://nystudio107.com/
 * @package   craft3-multi-environment
 * @since     1.0.3
 * @license   MIT
 */

/**
 * General Configuration
 *
 * All of your system's general configuration settings go in here.
 * You can see a list of the default settings in src/config/defaults/general.php
 */

return [

    // All environments
    '*' => [
        'omitScriptNameInUrls' => true,
        'usePathInfo' => true,
        'cacheDuration' => false,
        'useEmailAsUsername' => true,
        'generateTransformsBeforePageLoad' => true,
        'enableCsrfProtection' => true,
        'securityKey' => getenv('CRAFTENV_SECURITY_KEY'),
        'siteUrl' => getenv('CRAFTENV_SITE_URL'),
        'craftEnv' => CRAFT_ENVIRONMENT,
        'defaultSearchTermOptions' => array(
            'subLeft' => true,
            'subRight' => true,
        ),
        'baseUrl' => getenv('CRAFTENV_BASE_URL'),
        'basePath' => getenv('CRAFTENV_BASE_PATH'),
        'staticAssetsVersion' => 1,
    ],

    // Live (production) environment
    'live' => [
        'isSystemOn' => true,
        'devMode' => false,
        'backupOnUpdate' => false,
        'enableTemplateCaching' => true,
        'allowAutoUpdates' => false,
    ],

    // Staging (pre-production) environment
    'staging' => [
        'isSystemOn' => false,
        'devMode' => false,
        'backupOnUpdate' => false,
        'enableTemplateCaching' => true,
        'allowAutoUpdates' => false,
    ],

    // Local (development) environment
    'local' => [
        'isSystemOn' => true,
        'devMode' => true,
        'backupOnUpdate' => true,
        'enableTemplateCaching' => false,
        'allowAutoUpdates' => true,
        'staticAssetsVersion' => time(),
    ],
];
