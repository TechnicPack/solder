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
        'is_published' => false,
    ];
});

$factory->state(App\Modpack::class, 'published', function (Faker $faker) {
    return [
        'is_published' => true,
    ];
});

$factory->state(App\Modpack::class, 'unpublished', function (Faker $faker) {
    return [
        'is_published' => false,
    ];
});
