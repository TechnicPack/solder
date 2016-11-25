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
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Mod::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'author' => $faker->name,
        'description' => $faker->paragraph,
        'link' => $faker->url,
    ];
});

$factory->define(App\Release::class, function (Faker\Generator $faker) {
    return [
        'version' => $faker->numerify('#.#.#'),
        'mod_id' => function () {
            return factory(App\Mod::class)->create()->id;
        },
    ];
});

$factory->define(App\Modpack::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->company,
        'published' => $faker->boolean,
        'link' => $faker->url,
   ];
});

$factory->define(App\Build::class, function (Faker\Generator $faker) {
    return [
        'version' => $faker->numerify('#.#.#'),
        'published' => $faker->boolean,
        'tags' => [
            'minecraft' => $faker->numerify('#.#.#'),
            'forge' => $faker->numerify('#.#.#'),
            'java' => $faker->numerify('1.#'),
            'memory' => $faker->numberBetween(512, 4096),
        ],
        'modpack_id' => function () {
            return factory(App\Modpack::class)->create()->id;
        },
    ];
});

$factory->define(App\Client::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'token' => $faker->md5,
        'is_global' => $faker->boolean,
    ];
});
