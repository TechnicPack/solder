<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

Route::view('/login', 'auth.login')->name('auth.show-login');
Route::post('/login', 'Auth\LoginController@login')->name('auth.login');
Route::post('/logout', 'Auth\LoginController@logout')->name('auth.logout');

Route::middleware('auth')->group(function () {
    Route::get('/', 'DashboardController');

    Route::get('/modpacks/{modpack}', 'ModpacksController@show');
    Route::post('/modpacks', 'ModpacksController@store');
    Route::patch('/modpacks/{modpack}', 'ModpacksController@update');
    Route::delete('/modpacks/{modpack}', 'ModpacksController@destroy');

    Route::get('/modpacks/{modpack}/{build}', 'ModpackBuildsController@show');
    Route::post('/modpacks/{modpack}/builds', 'ModpackBuildsController@store');
    Route::post('/modpacks/{modpack}/{build}', 'ModpackBuildsController@update');
    Route::delete('/modpacks/{modpack}/{build}', 'ModpackBuildsController@destroy');

    Route::get('/library', 'PackagesController@index');
    Route::get('/library/{package}', 'PackagesController@show');
    Route::post('/library', 'PackagesController@store');
    Route::patch('/library/{package}', 'PackagesController@update');
    Route::delete('/library/{package}', 'PackagesController@destroy');

    Route::post('/library/{package}/releases', 'PackageReleasesController@store');

    Route::delete('/releases/{release}', 'ReleasesController@destroy');

    Route::delete('/bundles', 'BundlesController@destroy');
    Route::post('/bundles', 'BundlesController@store');
});

Route::middleware('auth')->namespace('Admin')->prefix('settings')->group(function () {
    Route::get('keys', 'KeysController@index');
    Route::post('keys', 'KeysController@store');
    Route::delete('keys/{key}', 'KeysController@destroy');

    Route::get('users', 'UsersController@index');
    Route::post('users', 'UsersController@store');
    Route::post('users/{user}', 'UsersController@update');
    Route::delete('users/{user}', 'UsersController@destroy');
});

Route::middleware('auth')->namespace('Profile')->prefix('profile')->group(function () {
    Route::view('tokens', 'profile.tokens');
    Route::view('oauth', 'profile.oauth');

    Route::get('clients', 'ClientsController@index');
    Route::post('clients', 'ClientsController@store');
    Route::delete('clients/{client}', 'ClientsController@destroy');
});
