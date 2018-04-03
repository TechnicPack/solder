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
        'name' => $faker->sentence(3, true),
        'slug' => $faker->slug(3, true),
        'status' => 'public',
        'team_id' => function () {
            return factory(\App\Team::class)->create()->id;
        },
    ];
});

$factory->state(App\Modpack::class, 'public', function () {
    return [
        'status' => 'public',
    ];
});

$factory->state(App\Modpack::class, 'draft', function () {
    return [
        'status' => 'draft',
    ];
});

$factory->state(App\Modpack::class, 'private', function () {
    return [
        'status' => 'private',
    ];
});
