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
    Route::get('/', 'RootController@index');
});

// Legacy Endpoints
Route::group(['namespace' => 'Api'], function () {
    Route::get('verify/{key}', 'LegacyController@verify');
    Route::get('modpack/{slug}/{version}', 'LegacyController@showBuild');
    Route::get('modpack/{slug}', 'LegacyController@showModpack');
    Route::get('modpack', 'LegacyController@listModpacks');
    Route::get('mod/{slug}/{version}', 'LegacyController@showVersion');
    Route::get('mod/{slug}', 'LegacyController@showMod');
    Route::get('mod', 'LegacyController@listMods');
});
