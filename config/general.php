<?php

/**
 * General Configuration
 *
 * All of your system's general configuration settings go in here.
 * You can see a list of the default settings in craft/app/etc/config/defaults/general.php
 */

// $_ENV constants are loaded by craft3-multi-environment from .env.php via web/index.php
return [

    // All environments
    '*' => [
        'omitScriptNameInUrls' => true,
        'usePathInfo' => true,
        'cacheDuration' => false,
        'useEmailAsUsername' => true,
        'generateTransformsBeforePageLoad' => true,
        'siteUrl' => getenv('CRAFTENV_SITE_URL'),
        'craftEnv' => CRAFT_ENVIRONMENT,

        // Set the environmental variables
        'environmentVariables' => [
            'baseUrl'  => getenv('CRAFTENV_BASE_URL'),
            'basePath' => getenv('CRAFTENV_BASE_PATH'),
        ],
    ],

    // Live (production) environment
    'live' => [
        'devMode' => false,
        'enableTemplateCaching' => true,
        'allowAutoUpdates' => false,
    ],

    // Staging (pre-production) environment
    'staging' => [
        'devMode' => false,
        'enableTemplateCaching' => true,
        'allowAutoUpdates' => false,
    ],

    // Local (development) environment
    'local' => [
        'devMode' => true,
        'enableTemplateCaching' => false,
        'allowAutoUpdates' => true,
    ],
];
