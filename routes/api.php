<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// Public Endpoints
Route::group(['namespace' => 'Api'], function () {
    Route::get('/', 'ApiController@index');
});

// Legacy Endpoints
Route::group(['namespace' => 'Api'], function () {
    Route::get('verify/{tokenValue}', 'LegacyTokenController@show');
    Route::get('modpack/{modpackSlug}/{buildNumber}', 'LegacyBuildController@show');
    Route::get('modpack/{modpackSlug}', 'LegacyModpackController@show');
    Route::get('modpack', 'LegacyModpackController@index');
});
