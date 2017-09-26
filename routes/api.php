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

Route::group(['middleware' => 'auth:api', 'namespace' => 'Api'], function() {
    Route::get('packages', 'PackagesController@index');
    Route::get('packages/{package}', 'PackagesController@show');
});

Route::get('verify/{token}', 'Api\VerifyToken');
Route::get('modpack', 'Api\ModpackController@index');
Route::get('modpack/{slug}', 'Api\ModpackController@show');
Route::get('modpack/{slug}/{build}', 'Api\ModpackBuildController@show');
