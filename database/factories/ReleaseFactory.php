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

$factory->define(App\Release::class, function (Faker $faker) {
    return [
        'package_id' => function() {
            return factory(\App\Package::class)->create()->id;
        },
        'version' => $faker->numerify('#.#.#'),
        'md5' => 'example-md5-hash',
        'url' => 'http://example.com/example-file.zip',
    ];
});
