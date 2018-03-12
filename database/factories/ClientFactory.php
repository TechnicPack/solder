<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$factory->define(App\Client::class, function () {
    return [
        'title' => 'Test Client',
        'token' => 'TESTTOKEN',
    ];
});
