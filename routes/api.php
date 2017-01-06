<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
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
});

// Protected Endpoints
Route::group(['namespace' => 'Api', 'middleware' => 'auth:api'], function () {
    Route::post('modpacks/{modpack}/builds', 'ModpackBuildsController@store');
    Route::put('modpacks/{modpack}/{asset}', 'ModpacksController@upload');
    Route::patch('modpacks/{modpack}', 'ModpacksController@update');
    Route::delete('modpacks/{modpack}', 'ModpacksController@destroy');
    Route::post('modpacks', 'ModpacksController@store');

    Route::get('builds/{build}/{related}', 'BuildsController@related');
    Route::post('builds/{build}/{related}', 'BuildsController@store');
    Route::patch('builds/{build}', 'BuildsController@update');
    Route::delete('builds/{build}', 'BuildsController@destroy');

    Route::get('resources/{resource}/relationships/versions', 'ResourceVersionsController@show');
    Route::get('resources/{resource}/versions', 'ResourceVersionsController@index');
    Route::post('resources/{resource}/versions', 'ResourceVersionsController@store');
    Route::get('resources/{resource}', 'ResourcesController@show');
    Route::patch('resources/{resource}', 'ResourcesController@update');
    Route::delete('resources/{resource}', 'ResourcesController@destroy');
    Route::get('resources', 'ResourcesController@index');
    Route::post('resources', 'ResourcesController@store');

    Route::get('versions/{version}/relationships/assets', 'VersionAssetsController@show');
    Route::get('versions/{version}/assets', 'VersionAssetsController@index');
    Route::post('versions/{version}/assets', 'VersionAssetsController@store');
    Route::get('versions/{version}', 'VersionsController@show');
    Route::patch('versions/{version}', 'VersionsController@update');
    Route::delete('versions/{version}', 'VersionsController@destroy');
    Route::get('versions', 'VersionsController@index');
    Route::post('versions', 'VersionsController@store');

    Route::get('assets/{asset}', 'AssetsController@show');
    Route::patch('assets/{asset}', 'AssetsController@update');
    Route::delete('assets/{asset}', 'AssetsController@destroy');
    Route::get('assets', 'AssetsController@index');
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
