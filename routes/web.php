<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/login', 'auth.login')->name('auth.show-login');
Route::post('/login', 'Auth\LoginController@login')->name('auth.login');
Route::post('/logout', 'Auth\LoginController@logout')->name('auth.logout');

Route::group(['middleware' => 'auth'], function() {
    Route::get('/', 'DashboardController');
    Route::get('/modpacks/{modpack}', 'ModpacksController@show');
    Route::get('/modpacks/{modpack}/{build}', 'ModpackBuildsController@show');
    Route::post('/library/{package}/releases', 'PackageReleasesController@store');
    Route::get('/library/{package}', 'PackagesController@show');
    Route::get('/library', 'PackagesController@index');
});

