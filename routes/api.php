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

    Route::get('modpacks/{modpack}/relationships/builds', 'ModpackBuildsController@show');
    Route::get('modpacks/{modpack}/builds', 'ModpackBuildsController@index');
    Route::get('modpacks/{modpack}', 'ModpacksController@show');
    Route::get('modpacks/', 'ModpacksController@index');

    Route::get('builds/{build}', 'BuildsController@show');
    Route::get('builds/', 'BuildsController@index');

    Route::get('resources/{resource}/relationships/versions', 'ResourceVersionsController@show');
    Route::get('resources/{resource}/versions', 'ResourceVersionsController@index');
    Route::get('resources/{resource}', 'ResourcesController@show');
    Route::get('resources', 'ResourcesController@index');

    Route::get('versions/{version}/relationships/assets', 'VersionAssetsController@show');
    Route::get('versions/{version}/assets', 'VersionAssetsController@index');
    Route::get('versions/{version}', 'VersionsController@show');
    Route::get('versions', 'VersionsController@index');

    Route::get('assets/{asset}', 'AssetsController@show');
    Route::get('assets', 'AssetsController@index');
});

// Protected Endpoints
Route::group(['namespace' => 'Api', 'middleware' => 'auth:api'], function () {
    Route::post('modpacks/{modpack}/builds', 'ModpackBuildsController@store');
    Route::put('modpacks/{modpack}/{asset}', 'ModpacksController@upload');
    Route::patch('modpacks/{modpack}', 'ModpacksController@update');
    Route::delete('modpacks/{modpack}', 'ModpacksController@destroy');
    Route::post('modpacks', 'ModpacksController@store');

    Route::post('builds/{build}/{related}', 'BuildsController@store');
    Route::patch('builds/{build}', 'BuildsController@update');
    Route::delete('builds/{build}', 'BuildsController@destroy');
    Route::post('builds', 'BuildsController@store');

    Route::post('resources/{resource}/versions', 'ResourceVersionsController@store');
    Route::patch('resources/{resource}', 'ResourcesController@update');
    Route::delete('resources/{resource}', 'ResourcesController@destroy');
    Route::post('resources', 'ResourcesController@store');

    Route::post('versions/{version}/assets', 'VersionAssetsController@store');
    Route::patch('versions/{version}', 'VersionsController@update');
    Route::delete('versions/{version}', 'VersionsController@destroy');
    Route::post('versions', 'VersionsController@store');

    Route::put('assets/{asset}', 'AssetsController@upload');
    Route::patch('assets/{asset}', 'AssetsController@update');
    Route::delete('assets/{asset}', 'AssetsController@destroy');
    Route::post('assets', 'AssetsController@store');
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
