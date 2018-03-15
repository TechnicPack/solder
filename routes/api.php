<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

Route::group(['middleware' => 'auth:api', 'namespace' => 'Api'], function () {
    Route::get('packages', 'PackagesController@index');
    Route::get('packages/{package}', 'PackagesController@show');
});
