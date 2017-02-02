<?php

/**
 * Volume Configuration
 *
 * All of your system's volume configuration settings go in here.
 * You can see a list of the default settings in craft/app/etc/config/defaults/volumes.php
 */

// $_ENV constants are loaded by craft3-multi-environment from .env.php via web/index.php
return [

    // All environments
    '*' => [
        'basePath' => getenv('CRAFTENV_BASE_URL'),
        'baseUrl' => getenv('CRAFTENV_BASE_PATH'),
    ],

    // Live (production) environment
    'live'  => [
    ],

    // Staging (pre-production) environment
    'staging'  => [
    ],

    // Local (development) environment
    'local'  => [
    ],
];
