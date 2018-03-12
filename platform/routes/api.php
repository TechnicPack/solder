<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

Route::group([
    'prefix' => 'api',
    'middleware' => 'api',
    'namespace' => 'Platform\Http\Controllers',
], function () {
    Route::get('/', function () {
        return response()->json([
            'api' => 'SolderIO',
            'version' => config('app.version'),
            'stream' => config('app.env'),
        ]);
    });

    Route::get('verify/{token}', 'Api\VerifyToken');
    Route::get('modpack', 'Api\ModpackController@index');
    Route::get('modpack/{slug}', 'Api\ModpackController@show');
    Route::get('modpack/{slug}/{build}', 'Api\ModpackBuildController@show');
});
