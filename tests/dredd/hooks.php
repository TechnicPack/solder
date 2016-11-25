<?php

use Dredd\Hooks;
use Illuminate\Support\Facades\Artisan;

require __DIR__.'/../../vendor/autoload.php';

$app = require __DIR__.'/../../bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

Hooks::beforeEach(function (&$transaction) use ($app) {
    //$app->make('db')->beginTransaction();
});

Hooks::afterEach(function (&$transaction) use ($app) {
    //$app->make('db')->rollback();
});

Hooks::before('v0.8 > Releases > Delete a release', function (&$transaction) {
    $release = factory(\App\Release::class)->create();
    $release->id = '0878a8f5-576c-4e06-bace-93aa46fdcc37';
    $release->save();
});

Hooks::before('v0.8 > Builds > Delete a build', function (&$transaction) {
    $build = factory(\App\Build::class)->create();
    $build->id = 'cac555fe-0325-489c-b3ed-1dc63dcacc2f';
    $build->save();
});
