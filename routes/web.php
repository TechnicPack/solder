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

Route::view('/', 'template.dashboard');
Route::view('/modpacks/show', 'template.modpacks.show');
Route::view('/modpacks/show/build', 'template.builds.show');
