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
use App\User;
use App\Token;

$factory->define(Token::class, function (Faker\Generator $faker) {
    return [
        'name' => 'Test Token',
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
    ];
});
