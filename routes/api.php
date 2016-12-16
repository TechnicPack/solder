<?php

use Illuminate\Http\Request;
use Tremby\LaravelGitVersion\GitVersionHelper;

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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::get('/', function () {
    return response([
        'api' => config('app.name'),
        'version' => GitVersionHelper::getVersion(),
        'stream' => config('app.env'),
    ]);
});

// Solder 0.8.~ Oauth Protected API Endpoints
Route::group(['namespace' => 'Api', 'middleware' => 'auth:api'], function () {
    Route::get('mods/{mod}/versions', 'ModVersionController@index');
    Route::post('mods/{mod}/versions', 'ModVersionController@store')->middleware('resource.item:version');
    Route::get('mods/{mod}', 'ModsController@show');
    Route::patch('mods/{mod}', 'ModsController@update')->middleware('resource.item:mod');
    Route::delete('mods/{mod}', 'ModsController@destroy');
    Route::get('mods', 'ModsController@index');
    Route::post('mods', 'ModsController@store')->middleware('resource.item:mod');

    Route::get('versions/{version}/builds', 'VersionBuildsController@index');
    Route::get('versions/{version}', 'VersionsController@show');
    Route::patch('versions/{version}', 'VersionsController@update');
    Route::delete('versions/{version}', 'VersionsController@destroy');
    Route::get('versions', 'VersionsController@index');

    Route::get('modpacks/{modpack}/builds', 'ModpackBuildsController@index');
    Route::post('modpacks/{modpack}/builds', 'ModpackBuildsController@store')->middleware('resource.item:build');
    Route::get('modpacks/{modpack}', 'ModpacksController@show');
    Route::patch('modpacks/{modpack}', 'ModpacksController@update')->middleware('resource.item:modpack');
    Route::delete('modpacks/{modpack}', 'ModpacksController@destroy');
    Route::get('modpacks', 'ModpacksController@index');
    Route::post('modpacks', 'ModpacksController@store')->middleware('resource.item:modpack');

    Route::get('builds/{build}/versions', 'BuildVersionsController@index');
    Route::get('builds/{build}', 'BuildsController@show');
    Route::patch('builds/{build}', 'BuildsController@update')->middleware('resource.item:build');
    Route::delete('builds/{build}', 'BuildsController@destroy');
    Route::get('builds', 'BuildsController@index');
});

// Solder 0.7.~ API Endpoints
Route::group(['namespace' => 'Api\v07'], function () {
    Route::get('verify/{token}', 'TokensController@verify');
    Route::get('mod/{mod}/{version}', 'ModVersionsController@show');
    Route::get('mod/{mod}', 'ModsController@show');
    Route::get('mod', 'ModsController@index');
    Route::get('modpack/{modpack}/{buildVersion}', 'ModpackBuildsController@show');
    Route::get('modpack/{modpack}', 'ModpacksController@show');
    Route::get('modpack', 'ModpacksController@index');
});
