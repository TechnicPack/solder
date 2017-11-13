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

    Route::resource('modpacks', 'ModpacksController')->only([
        'show',
        'store',
        'update',
        'destroy',
    ]);

    Route::post('/modpacks/{modpack}/collaborators', 'ModpackCollaboratorsController@store');

    Route::delete('/collaborators/{collaborator}', 'CollaboratorsController@destroy');

    Route::resource('modpacks/{modpack}/builds', 'ModpackBuildsController')->only([
        'show',
        'store',
        'update',
        'destroy',
    ]);

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
    Route::view('about', 'settings.about');

    Route::get('permissions', 'PermissionsController@index');
    Route::post('permissions', 'PermissionsController@update');

    Route::get('keys', 'KeysController@index');
    Route::post('keys', 'KeysController@store');
    Route::delete('keys/{key}', 'KeysController@destroy');

    Route::get('clients', 'ClientsController@index');
    Route::post('clients', 'ClientsController@store');
    Route::delete('clients/{client}', 'ClientsController@destroy');

    Route::get('users', 'UsersController@index');
    Route::post('users', 'UsersController@store');
    Route::post('users/{user}', 'UsersController@update');
    Route::delete('users/{user}', 'UsersController@destroy');
});

Route::middleware('auth')->namespace('Profile')->prefix('profile')->group(function () {
    Route::view('tokens', 'profile.tokens');
    Route::view('oauth', 'profile.oauth');
});
