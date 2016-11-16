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

Route::group(['namespace' => 'Api\v07'], function () {
    Route::get('verify/{token}', 'TokensController@verify');
    Route::get('mod/{mod}/{releaseVersion}', 'ModReleasesController@show');
    Route::get('mod/{mod}', 'ModsController@show');
    Route::get('mod', 'ModsController@index');
    Route::get('modpack/{modpack}/{buildVersion}', 'ModpackBuildsController@show');
    Route::get('modpack/{modpack}', 'ModpacksController@show');
    Route::get('modpack', 'ModpacksController@index');
});
