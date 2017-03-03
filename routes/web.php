<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
Route::resource('/modpacks', 'ModpacksController');
Route::get('/modpacks/{modpack}/overview', 'ModpacksController@overview')->name('modpacks.overview');
Route::get('/modpacks/{modpack}/help', 'ModpacksController@help')->name('modpacks.help');
Route::get('/modpacks/{modpack}/license', 'ModpacksController@license')->name('modpacks.license');
Route::get('/security', 'SecurityController@index');
