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

$factory->define(App\Package::class, function (Faker $faker) {
    return [
        'name' => 'Example Package',
        'slug' => $faker->slug(3, true),
        'team_id' => function () {
            return factory(\App\Team::class)->create()->id;
        },
    ];
});
