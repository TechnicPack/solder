<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Carbon\Carbon;

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Resource::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
    ];
});

$factory->define(App\Version::class, function (Faker\Generator $faker) {
    return [
        'version' => $faker->numerify('#.#.#'),
        'resource_id' => function () {
            return factory(App\Resource::class)->create()->id;
        },
    ];
});

$factory->define(App\Modpack::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->company,
    ];
});

$factory->state(App\Modpack::class, 'published', function () {
    return [
        'published_at' => Carbon::parse('-1 week'),
    ];
});

$factory->state(App\Modpack::class, 'unpublished', function () {
    return [
        'published_at' => null,
    ];
});

$factory->state(App\Modpack::class, 'scheduled', function () {
    return [
        'published_at' => Carbon::parse('+1 week'),
    ];
});

$factory->define(App\Build::class, function (Faker\Generator $faker) {
    return [
        'version' => $faker->numerify('#.#.#'),
        'modpack_id' => function () {
            return factory(App\Modpack::class)->create()->id;
        },
    ];
});

$factory->state(App\Build::class, 'published', function () {
    return [
        'published_at' => Carbon::parse('-1 week'),
    ];
});

$factory->define(App\Client::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'token' => $faker->md5,
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'is_global' => $faker->boolean,
    ];
});
