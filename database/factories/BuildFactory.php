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
use App\Build;
use App\Modpack;
use App\Privacy;

$factory->define(Build::class, function (Faker\Generator $faker) {
    return [
        'build_number' => $faker->numerify('#.#.#'),
        'minecraft_version' => '1.2.3',
        'status' => Build::STATE_PUBLIC,
        'modpack_id' => function () {
            return factory(Modpack::class)->create()->id;
        },
    ];
});

$factory->state(Build::class, 'public', function () {
    return [
        'status' => Build::STATE_PUBLIC,
    ];
});

$factory->state(Build::class, 'draft', function () {
    return [
        'status' => Build::STATE_DRAFT,
    ];
});

$factory->state(Build::class, 'private', function () {
    return [
        'status' => Build::STATE_PRIVATE,
    ];
});
