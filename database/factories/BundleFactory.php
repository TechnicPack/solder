<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$factory->define(\App\Bundle::class, function () {
    return [
        'build_id' => function () {
            return factory(\App\Build::class)->create()->id;
        },
        'release_id' => function () {
            return factory(\App\Release::class)->create()->id;
        },
    ];
});
