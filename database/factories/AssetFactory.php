<?php

/*
 * This file is part of TechnicSolder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Asset;
use App\Version;

$factory->define(Asset::class, function (Faker\Generator $faker) {
    return [
        'filename' => 'testfile.txt',
        'version_id' => function () {
            return factory(Version::class)->create()->id;
        },
    ];
});
