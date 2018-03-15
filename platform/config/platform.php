<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
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

    'provider' => 'TechnicSolder',

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
            'list' => 'Platform\Policies\ClientPolicy@list',
            'create' => 'Platform\Policies\ClientPolicy@create',
            'delete' => 'Platform\Policies\ClientPolicy@delete',
        ],

        'keys' => [
            'list' => 'Platform\Policies\KeyPolicy@list',
            'create' => 'Platform\Policies\KeyPolicy@create',
            'delete' => 'Platform\Policies\KeyPolicy@delete',
        ],


    ],

];
