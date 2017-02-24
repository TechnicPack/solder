<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Modpack;
use App\Privacy;

$factory->define(Modpack::class, function (Faker\Generator $faker) {
    return [
        'name' => 'Example Modpack',
        'privacy' => Privacy::PUBLIC,
    ];
});

$factory->state(Modpack::class, 'public', function () {
    return [
        'privacy' => Privacy::PUBLIC,
    ];
});

$factory->state(Modpack::class, 'unlisted', function () {
    return [
        'privacy' => Privacy::UNLISTED,
    ];
});

$factory->state(Modpack::class, 'private', function () {
    return [
        'privacy' => Privacy::PRIVATE,
    ];
});
