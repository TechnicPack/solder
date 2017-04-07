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
use App\Version;
use App\Resource;

$factory->define(Version::class, function (Faker\Generator $faker) {
    return [
        'resource_id' => function () {
            return factory(Resource::class)->create()->id;
        },
    ];
});
