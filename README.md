# Craft3-Multi-Environment for Craft CMS 3.x

Efficient and flexible multi-environment config for Craft CMS

Related: [Craft-Multi-Environment for Craft 2.x](https://github.com/nystudio107/craft-multi-environment)

## Overview

[Multi-Environment Configs](https://craftcms.com/docs/multi-environment-configs) let you easily run a Craft CMS project in local dev, staging, and production.  They allow different people to work in different environments without painful setup or coordination. You can read a more in-depth discussion of it in the [Multi-Environment Config for Craft CMS](https://nystudio107.com/blog/multi-environment-config-for-craft-cms) article.

### Why another multi-environment config?

There are a number of good approaches to implementing a multi-environment config in Craft CMS, but they each have drawbacks.  There are two main approaches typically used are:

1. [Multi-Environment Configs](https://craftcms.com/docs/multi-environment-configs) - The problem with this approach is that it often results in data stored in a git repo (such as passwords, Stripe keys, etc.) that really shouldn't be.
2. [PHP dotenv](https://github.com/vlucas/phpdotenv) - The problem with this approach is that PHP dotenv is fairly heavy, and indeed the authors warn against using it in production.  Instantiating the Composer auto-loader and reading in the `.env` file for every request adds unnecessary overhead.

Craft-Multi-Environment (CME) is my attempt to create something that finds a middle-ground between the two approaches.

You can read more about it in the [Setting up a New Craft 3 CMS Project](https://nystudio107.com/blog/setting-up-a-craft-cms-3-project) article. You can also alternatively have Craft 3 Multi-Environment installed & configured for you via the [nystudio107/craft](https://github.com/nystudio107/craft) project package.

### How does it work?

CME works by including a `.env.php` file (which is never checked into git) via the Craft `index.php` file that is loaded for every non-static request.

The `.env.php` file sets some globally-accessible settings via `putenv()` for common things like the database password, database user, base URL, etc.  You're also free to add your own as you see fit.  The `config/general.php` and `config/db.php` can thus remain abstracted, and each environment can have their own local settings.

This is more performant than auto-loading a class and reading a `.env` file for each request, but maintains the same flexibility.  Additionally, since we are using `getenv()` to access the settings, these can be set directly by the webserver (without using the `.env.php` file at all) for additional security and performance.

Also, since we're using `getenv()`, these settings are globally accessible in the PHP session, and can for instance be used in a Craft Commerce `commerce.php` file, accessed via plugins, etc.

## Using Craft-Multi-Environment

### Assumptions

CME assumes that you have a folder structure such as this for your project root:

    .env.php
    config/
    storage/
    templates/
    web/
        index.php
    craft

If your folder structure is different, that's fine.  But you may need to adjust the path to `.env.php` in the `index.php` file, and you may need to adjust the way `CRAFTENV_BASE_PATH` is constructed in your `.env.php` (or just hardcode the path).

CME will also work fine with localized sites as well, you'll just need to adjust the aforementioned paths as appropriate.

### Setting it up

1. Copy `config/db.php`, `config/general.php` & `config/volumes.php` to your project's `config/` folder
2. Copy `web/index.php` to your project's `web/` folder
3. Copy the script `craft` to your project's root (this is the console bootstrap file for Craft)
4. Copy `example.env.php` to your project's root folder, then duplicate it, and rename the copy `.env.php`
5. Edit the `.env.php` file, replacing instances of `REPLACE_ME` with your appropriate settings
6. Add `/.env.php` to your `.gitignore` file

The `web/index.php` file included with CME just has the following that supplants the `Dotenv` lines (it is otherwise unchanged):

    // Load the local craft3-multi-environment
    if (file_exists(CRAFT_BASE_PATH . DIRECTORY_SEPARATOR . '.env.php')) {
        require_once CRAFT_BASE_PATH . DIRECTORY_SEPARATOR . '.env.php';
    }
    
    // Default environment
    if (!defined('CRAFT_ENVIRONMENT')) {
        define('CRAFT_ENVIRONMENT', getenv('CRAFTENV_CRAFT_ENVIRONMENT'));
    }

You will need to create an `.env.php` file for each environment on which your Craft CMS project will be used (other team member's local dev, staging, production, etc.), but the `db.php`, `general.php`, and `index.php` are the same on all environments.

It's recommended that the `example.env.php` **is** checked into your git repo, so others can use it for a guide when creating their own local `.env.php` file.

### Asset Volumes

Craft 3 does away with the notion of `environmentalVariables`. Instead, to configure things like your `baseUrl` and `basePath` for assets, you do this in the `volumes.php` file:

    // All environments
    '*' => [
        'ASSET_HANDLE' => [
            'url' => getenv('CRAFTENV_BASE_URL') . 'ASSET_PATH',
            'path' => getenv('CRAFTENV_BASE_PATH') . 'ASSET_PATH',
        ],
    ],

Put the Asset Volume handle in `ASSET_HANDLE` key, and put the path to the asset in `ASSET_PATH`.

Since each Asset Volume can have a different `url` and `path`, you'll need to create an array for each Asset Volume that your website uses. Here's an example:

    // All environments
    '*' => [
        'siteAssets' => [
            'url' => getenv('CRAFTENV_BASE_URL') . 'img/site',
            'path' => getenv('CRAFTENV_BASE_PATH') . 'img/site',
        ],
        'blogImages' => [
            'url' => getenv('CRAFTENV_BASE_URL') . 'img/blog',
            'path' => getenv('CRAFTENV_BASE_PATH') . 'img/blog',
        ],
    ],

This is a config with two Asset Volumes with the handles `siteAssets` and `blogImages`, with the file system & URI paths of `img/site` and `img/blog`, respectively.

### Local environments

CME suggests the following environments, each of which can have different Craft settings per environment, independent of the private settings defined in `.env.php`:

1. `*` - applies globally to all environments
2. `live` - your live production environment
3. `staging` - your staging or pre-production environment for client review, external testing, etc.
4. `local` - your local development environment

The `db.php` and `config.php` define each environment, and you can put whatever [Craft Config Settings](https://craftcms.com/docs/config-settings) you desire for each environment in each.  The names of the environments and the default settings for each are just suggestions, however.  You can change them to be whatever you like.

### Extending it

If you have additional settings that need to be globally accessible, you can just add them to the `.env.php`.  For example, let's say we need a private key for Stripe, you can add this to `.env.php` by adding it to the `$craftenv_vars` array:

    // The private Stripe key.
    'STRIPE_KEY' => 'REPLACE_ME',

CME will auto-prefix all settings in the `$craftenv_vars` with `CRAFTENV_` for semantic reasons, and to avoid namespace collisions.

You should also update the `example.env.php` to include any settings you add, for reference and your team's reference.

### Accessing the settings in `general.php`

You can access any variables defined in the `general.php` file in Twig via `{{ craft.app.config.general }}`.  e.g.:

    {% if craft.app.config.general.custom.craftEnv == "local" %}
    {% endif %}

The `custom` sub-array in the config setup is for any non-Craft defined config settings that you might want to include in `general.php`. Since Craft does a recursive merge on the config settings, you can change just the config settings you need on a per-environment basis.

### Production via webserver config

It's perfectly fine to use CME as discussed above in a production environment.  However, if you want an added measure of security and performance, you can set up your webserver to set the same globally accessible settings via webserver config.

It's slightly more secure, in that only a user with admin privileges should have access to the server config files.  It's ever so slightly more performant, in that there's no extra `.env.php` file that is being processed with each request.

This is entirely optional, but if you're interested in doing it, here's how.

1. Use the `index.php` that comes with CME, but ensure that there is no `.env.php` file in your project root. CME will gracefully not attempt to load this file if it doesn't exist.
2. Configure your webserver as described below, and then restart it

#### Apache

Inside the `<VirtualHost>` block:

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

(...and any other custom config settings you've added)

#### Nginx

Inside the `server {}` or `location ~ \.php {}` block or in the `fastcgi_params` file:

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

(...and any other custom config settings you've added)

Brought to you by [nystudio107](https://nystudio107.com/)
