<?php

use Illuminate\Database\Seeder;

class ApiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a known global token
        factory(App\Client::class)->create([
            'token' => 'api_token',
            'is_global' => true,
        ]);

        // Create a known launcher token
        $client = factory(App\Client::class)->create([
            'token' => 'client_token',
            'is_global' => false,
        ]);

        // Create a mod with a release
        $mod = factory(App\Mod::class)->create([
            'name' => 'TestMod',
        ]);

        factory(App\Release::class)->create([
            'mod_id' => $mod->id,
            'version' => '0.1',
        ]);

        // Create an unpublished modpack which will be shown if a
        // valid client or global token is present
        $sneakyModpack = factory(App\Modpack::class)->create([
            'name' => 'Client Only',
            'published' => false,
        ]);

        // Create an unpublished modpack which will be shown only if
        // a valid global token is present
        $hiddenModpack = factory(App\Modpack::class)->create([
            'published' => false,
        ]);

        // Create a published modpack
        $modpack = factory(App\Modpack::class)->create([
            'name' => 'test',
            'published' => true,
        ]);

        // Create a published build for the published modpack
        factory(App\Build::class)->create([
            'modpack_id' => $modpack->id,
            'version' => '0.2',
            'published' => true,
        ]);

        // Create an unpublished build for the published modpack which
        // should be hidden from the api without a global, or client
        // token
        factory(App\Build::class)->create([
            'modpack_id' => $modpack->id,
            'version' => '0.3',
            'published' => false,
        ]);

        // Grant the client permission to see one of the unpublished
        // modpacks and the unpublished build.
        $client->modpacks()->attach($sneakyModpack);
    }
}
