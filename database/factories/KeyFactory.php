<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$factory->define(App\Key::class, function () {
    return [
        'name' => 'Test Key',
        'token' => 'test-key-token',
    ];
});
