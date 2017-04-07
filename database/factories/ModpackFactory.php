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

$factory->define(Modpack::class, function (Faker\Generator $faker) {
    return [
        'name' => 'Example Modpack',
        'slug' => 'example-modpack',
        'status' => Modpack::STATUS_PUBLIC,
    ];
});

$factory->state(Modpack::class, 'private', function () {
    return [
        'status' => Modpack::STATUS_PRIVATE,
    ];
});

$factory->state(Modpack::class, 'public', function () {
    return [
        'status' => Modpack::STATUS_PUBLIC,
    ];
});

$factory->state(Modpack::class, 'unlisted', function () {
    return [
        'status' => Modpack::STATUS_UNLISTED,
    ];
});
