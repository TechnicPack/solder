<?php

use Dredd\Hooks;

require __DIR__.'/../../vendor/autoload.php';

$app = require __DIR__.'/../../bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$store = [];

Hooks::beforeAll(function () use (&$store) {
    // Create a user and get an API token to use
    $user = factory(\App\User::class)->create();
    $store['token'] = $user->createToken('Dredd Test')->accessToken;
});

Hooks::beforeEach(function (&$transaction) use (&$store) {
    $transaction->request->headers->Authorization = 'Bearer '.$store['token'];
});

Hooks::before('v0.8 > Releases > Delete a release', function () {
    // Have to re-create the release, order of operations deletes it with the mod
    $release = factory(\App\Release::class)->create();
    $release->id = '0878a8f5-576c-4e06-bace-93aa46fdcc37';
    $release->save();
});

Hooks::before('v0.8 > Builds > Delete a build', function () {
    // Have to re-create the build, order of operations deletes it with the modpack
    $build = factory(\App\Build::class)->create();
    $build->id = 'cac555fe-0325-489c-b3ed-1dc63dcacc2f';
    $build->save();
});

Hooks::before('v0.7 > Verify API Key > Verify API Key', function () {
    // Create an api token to test with
    factory(App\Client::class)->create([
        'name' => 'Technicpack.net',
        'token' => '0d64794852c2a4dcecb1a57cdf531d28',
        'is_global' => true,
    ]);
});

Hooks::before('v0.7 > Mods > List mods', function () {
    // Setup the v0.7 test data
    $mod = factory(App\Mod::class)->create([
        'name' => 'TestMod',
    ]);

    $release = factory(App\Release::class)->create([
        'mod_id' => $mod->id,
        'version' => '0.1',
    ]);

    $modpack = factory(App\Modpack::class)->states('published')->create([
        'name' => 'test',
    ]);

    $build = factory(App\Build::class)->states('published')->create([
        'modpack_id' => $modpack->id,
        'version' => '1.0.3',
    ]);

    $build->releases()->attach($release);
});
