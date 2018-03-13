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
    'namespace' => 'Platform\Http\Controllers\Api',
], function () {

    // API Root
    Route::get('/', 'DescribeApi');

    // Verify Token Routes ...
    Route::get('verify/{token}', 'VerifyToken');

    // Modpack Routes ...
    Route::get('modpack', 'ModpackController@index');
    Route::get('modpack/{slug}', 'ModpackController@show');
    Route::get('modpack/{slug}/{build}', 'ModpackBuildController@show');
});
