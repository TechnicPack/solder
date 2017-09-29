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

Route::group(['middleware' => 'auth'], function () {
    Route::view('/profile/tokens', 'profile.tokens');
    Route::view('/profile/clients', 'profile.clients');
    Route::get('/', 'DashboardController');
    Route::get('/modpacks/new', 'ModpacksController@create');
    Route::get('/modpacks/{modpack}', 'ModpacksController@show');
    Route::post('/modpacks/{modpack}/builds', 'ModpackBuildsController@store');
    Route::get('/modpacks/{modpack}/builds/new', 'ModpackBuildsController@create');
    Route::get('/modpacks/{modpack}/{build}', 'ModpackBuildsController@show');
    Route::post('/modpacks', 'ModpacksController@store');
    Route::get('/library/new', 'PackagesController@create');
    Route::post('/library/{package}/releases', 'PackageReleasesController@store');
    Route::get('/library/{package}', 'PackagesController@show');
    Route::post('/library', 'PackagesController@store');
    Route::get('/library', 'PackagesController@index');
    Route::delete('/releases/{release}', 'ReleasesController@destroy');
    Route::delete('/bundles', 'BundlesController@destroy');
    Route::post('/bundles', 'BundlesController@store');
    Route::get('/settings/keys', 'KeysController@index');
    Route::post('/settings/keys', 'KeysController@store');
    Route::delete('/settings/keys/{key}', 'KeysController@destroy');
});
