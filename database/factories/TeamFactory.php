<?php

/*
 * This file is part of TechnicPack Solder.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Faker\Generator as Faker;

$factory->define(App\Team::class, function (Faker $faker) {
    return [
        'name' => $faker->streetName,
        'slug' => $faker->slug,
        'owner_id' => function () {
            return factory(\App\User::class)->create()->id;
        },
    ];
});
