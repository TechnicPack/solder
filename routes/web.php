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
    Route::get('/', 'DashboardController');
    Route::get('/modpacks/{modpack}', 'ModpacksController@show');
    Route::get('/modpacks/{modpack}/{build}', 'ModpackBuildsController@show');
    Route::post('/library/{package}/releases', 'PackageReleasesController@store');
    Route::get('/library/{package}', 'PackagesController@show');
    Route::get('/library', 'PackagesController@index');
});
