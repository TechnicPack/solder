<?php

/*
 * This file is part of TechnicPack Solder.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::view('/settings/developer', 'settings.developer')->name('settings.developer');
Route::view('/modpacks/{modpack}', 'modpack');
Route::view('/modpacks/{modpack}/builds/{build}', 'build');
