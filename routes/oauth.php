<?php

/*
 * This file is part of TechnicPack Solder.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

Route::get('scopes', '\Laravel\Passport\Http\Controllers\ScopeController@all');
Route::get('personal-access-tokens', '\Laravel\Passport\Http\Controllers\PersonalAccessTokenController@forUser');
Route::post('personal-access-tokens', '\Laravel\Passport\Http\Controllers\PersonalAccessTokenController@store');
Route::delete('personal-access-tokens/{token_id}', '\Laravel\Passport\Http\Controllers\PersonalAccessTokenController@destroy');

// Transient Access Token Routs ...
Route::post('token/refresh', '\Laravel\Passport\Http\Controllers\TransientTokenController@refresh');
