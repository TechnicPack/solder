<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$factory->define(App\Build::class, function () {
    return [
        'minecraft_version' => '1.7.10',
        'version' => '1.0.0',
        'status' => 'public',
        'modpack_id' => function () {
            return factory(App\Modpack::class)->states('public')->create()->id;
        },
    ];
});

$factory->state(App\Build::class, 'public', function () {
    return [
        'status' => 'public',
    ];
});

$factory->state(App\Build::class, 'private', function () {
    return [
        'status' => 'private',
    ];
});

$factory->state(App\Build::class, 'draft', function () {
    return [
        'status' => 'draft',
    ];
});
