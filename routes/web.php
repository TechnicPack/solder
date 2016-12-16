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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/security', 'SecurityController@index');
Route::get('/mods', 'ModsController@index');
Route::get('/mods/{mod}', 'ModsController@show');
Route::get('/modpacks', 'ModpacksController@index');
Route::get('/modpacks/{modpack}', 'ModpacksController@show');
Route::get('/builds/{build}', 'BuildsController@show');
Route::get('/versions/{version}', 'VersionsController@show');
