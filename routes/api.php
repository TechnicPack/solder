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
    Route::get('resources/{resource}/versions', 'ResourceVersionController@index');
    Route::post('resources/{resource}/versions', 'ResourceVersionController@store')->middleware('resource.item:version');
    Route::get('resources/{resource}', 'ResourcesController@show');
    Route::patch('resources/{resource}', 'ResourcesController@update')->middleware('resource.item:resource');
    Route::delete('resources/{resource}', 'ResourcesController@destroy');
    Route::get('resources', 'ResourcesController@index');
    Route::post('resources', 'ResourcesController@store')->middleware('resource.item:resource');

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
    Route::get('mod/{resource}/{version}', 'ResourceVersionsController@show');
    Route::get('mod/{resource}', 'ResourcesController@show');
    Route::get('mod', 'ResourcesController@index');
    Route::get('modpack/{modpack}/{buildVersion}', 'ModpackBuildsController@show');
    Route::get('modpack/{modpack}', 'ModpacksController@show');
    Route::get('modpack', 'ModpacksController@index');
});
