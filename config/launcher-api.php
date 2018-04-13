<?php

/*
 * This file is part of TechnicPack Launcher Api.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | API Version
    |--------------------------------------------------------------------------
    |
    | The version of the API. Consumers can use this to understand
    | functionality and features available at the API.
    |
    */

    'version' => '0.7.3.1',

    /*
    |--------------------------------------------------------------------------
    | API Provider
    |--------------------------------------------------------------------------
    |
    | The software used to generate the API responses. This is meta information
    | provided to API consumers to let them know what tool or package is being
    | used to generate API responses.
    |
    */

    'provider' => 'Solder',

    /*
    |--------------------------------------------------------------------------
    | API Stream
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" the API is currently
    | running in. It should mirror the state of the Application Environment.
    |
    */

    'steam' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Mirror URL
    |--------------------------------------------------------------------------
    |
    | This is where the launcher will be told to search for your files. This
    | location is only searched if the mod url is a file path and not a url.
    |
    */

    'mirror_url' => env('APP_URL').'/storage',

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    |
    | These should point to the models used in the primary application to
    | represent data for the technic platform.
    |
    */

    'model' => [
        'modpack' => 'App\Modpack',
        'build' => 'App\Build',
    ],

    /*
    |--------------------------------------------------------------------------
    | Model Attributes
    |--------------------------------------------------------------------------
    |
    | Set the attribute that should be loaded from your model for each of the
    | given attributes the platform is expecting.
    |
    */

    'attributes' => [

        'modpack' => [
            'name' => 'slug',
            'display_name' => 'name',
            'recommended' => 'recommended_build_version',
            'latest' => 'latest_build_version',
        ],

        'build' => [
            'version' => 'version',
            'minecraft' => 'minecraft',
            'java' => 'min_java',
            'memory' => 'min_memory',
            'forge' => 'forge',
        ],

        'mod' => [
            'name' => 'name',
            'version' => 'version',
            'md5' => 'md5',
            'url' => 'url',
            'filesize' => 'filesize',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Authorization Gates
    |--------------------------------------------------------------------------
    |
    | Define Gates that determine if a user is authorized to perform a given
    | action. Gates must be defined using a Class@method style callback string,
    | like controllers.
    |
    */

    'authorize' => [

        'clients' => [
            'list' => 'App\Policies\ClientPolicy@list',
            'create' => 'App\Policies\ClientPolicy@create',
            'delete' => 'App\Policies\ClientPolicy@delete',
        ],

        'keys' => [
            'list' => 'App\Policies\KeyPolicy@list',
            'create' => 'App\Policies\KeyPolicy@create',
            'delete' => 'App\Policies\KeyPolicy@delete',
        ],

    ],

];
