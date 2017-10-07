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

$factory->define(App\Build::class, function (Faker $faker) {
    return [
        'minecraft' => '1.7.10',
        'version' => '1.0.0',
        'status' => 'public',
        'modpack_id' => function () {
            return factory(App\Modpack::class)->states('published')->create()->id;
        },
    ];
});

$factory->state(App\Build::class, 'public', function (Faker $faker) {
    return [
        'status' => 'public',
    ];
});

$factory->state(App\Build::class, 'private', function (Faker $faker) {
    return [
        'status' => 'private',
    ];
});

$factory->state(App\Build::class, 'draft', function (Faker $faker) {
    return [
        'status' => 'draft',
    ];
});
