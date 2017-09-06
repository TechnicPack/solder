<?php

use Faker\Generator as Faker;

$factory->define(App\Client::class, function (Faker $faker) {
    return [
        'token' => 'TESTTOKEN',
    ];
});
