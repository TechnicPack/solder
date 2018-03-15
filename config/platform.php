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

    ]

];