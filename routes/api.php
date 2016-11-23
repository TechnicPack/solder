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

// Solder 0.8.~ API Endpoints
Route::group(['namespace' => 'Api'], function () {
    Route::get('mods/{mod}/releases', 'ModReleasesController@index');
    Route::post('mods/{mod}/releases', 'ModReleasesController@store')->middleware('resource.item:release');
    Route::get('mods/{mod}', 'ModsController@show');
    Route::put('mods/{mod}', 'ModsController@update')->middleware('resource.item:mod');
    Route::delete('mods/{mod}', 'ModsController@destroy');
    Route::get('mods', 'ModsController@index');
    Route::post('mods', 'ModsController@store')->middleware('resource.item:mod');

    Route::get('releases/{release}', 'ReleasesController@show');
    Route::put('releases/{release}', 'ReleasesController@update');
    Route::delete('releases/{release}', 'ReleasesController@destroy');
    Route::get('releases', 'ReleasesController@index');

    Route::get('modpacks/{modpack}/builds', 'ModpackBuildsController@index');
    Route::post('modpacks/{modpack}/builds', 'ModpackBuildsController@store')->middleware('resource.item:build');
    Route::get('modpacks/{modpack}', 'ModpacksController@show');
    Route::put('modpacks/{modpack}', 'ModpacksController@update')->middleware('resource.item:modpack');
    Route::delete('modpacks/{modpack}', 'ModpacksController@destroy');
    Route::get('modpacks', 'ModpacksController@index');
    Route::post('modpacks', 'ModpacksController@store')->middleware('resource.item:modpack');

    Route::get('builds/{build}', 'BuildsController@show');
    Route::put('builds/{build}', 'BuildsController@update')->middleware('resource.item:build');
    Route::delete('builds/{build}', 'BuildsController@destroy');
    Route::get('builds', 'BuildsController@index');
});

// Solder 0.7.~ API Endpoints
Route::group(['namespace' => 'Api\v07'], function () {
    Route::get('verify/{token}', 'TokensController@verify');
    Route::get('mod/{mod}/{releaseVersion}', 'ModReleasesController@show');
    Route::get('mod/{mod}', 'ModsController@show');
    Route::get('mod', 'ModsController@index');
    Route::get('modpack/{modpack}/{buildVersion}', 'ModpackBuildsController@show');
    Route::get('modpack/{modpack}', 'ModpacksController@show');
    Route::get('modpack', 'ModpacksController@index');
});
