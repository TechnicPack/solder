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

$factory->define(App\Release::class, function (Faker $faker) {
    return [
        'package_id' => function () {
            return factory(\App\Package::class)->create()->id;
        },
        'version' => $faker->numerify('#.#.#'),
        'path' => 'package\package-version.zip',
        'md5' => 'example-md5-hash',
        'filesize' => 1024000,
    ];
});
