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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name'           => $faker->name,
        'email'          => $faker->safeEmail,
        'password'       => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Modpack::class, function (Faker\Generator $faker) {
    $name = $faker->words(3, true);

    return [
        'slug'           => str_slug($name),
        'name'           => $name,
        'hidden'         => $faker->boolean(5),
        'private'        => $faker->boolean(5),
        'background_url' => $faker->imageUrl(900, 600, 'abstract'),
        'icon_url'       => $faker->imageUrl(50, 50, 'cats'),
        'logo_url'       => $faker->imageUrl(370, 220, 'cats'),
    ];
});

$factory->define(App\Mod::class, function (Faker\Generator $faker) {
    $name = $faker->sentence;

    return [
        'slug'        => str_slug($name),
        'name'        => $name,
        'description' => $faker->paragraph,
        'author'      => $faker->name,
        'link'        => $faker->url,
        'donatelink'  => $faker->url,
    ];
});
