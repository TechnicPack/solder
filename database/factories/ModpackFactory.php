<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Faker\Generator as Faker;

$factory->define(App\Modpack::class, function (Faker $faker) {
    return [
        'name' => 'Test Modpack',
        'slug' => 'test-modpack',
        'status' => 'public',
    ];
});

$factory->state(App\Modpack::class, 'public', function (Faker $faker) {
    return [
        'status' => 'public',
    ];
});

$factory->state(App\Modpack::class, 'draft', function (Faker $faker) {
    return [
        'status' => 'draft',
    ];
});

$factory->state(App\Modpack::class, 'private', function (Faker $faker) {
    return [
        'status' => 'private',
    ];
});
