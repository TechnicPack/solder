<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

Route::group([
    'prefix' => 'settings',
    'middleware' => ['web', 'auth'],
    'namespace' => 'Platform\Http\Controllers\Settings',
], function () {

    // Key Routes ...
    Route::get('keys', 'KeysController@index');
    Route::post('keys', 'KeysController@store');
    Route::delete('keys/{key}', 'KeysController@destroy');

    // Client Routes ...
    Route::get('clients', 'ClientsController@index');
    Route::post('clients', 'ClientsController@store');
    Route::delete('clients/{client}', 'ClientsController@destroy');

});
