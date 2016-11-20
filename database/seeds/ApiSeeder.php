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

        // Create a mod with a release
        $mod = factory(App\Mod::class)->create([
            'name' => 'TestMod',
        ]);

        $release = factory(App\Release::class)->create([
            'mod_id' => $mod->id,
            'version' => '0.1',
        ]);

        // Create a published modpack with two builds
        $modpack = factory(App\Modpack::class)->create([
            'name' => 'test',
            'published' => true,
        ]);

        factory(App\Build::class)->create([
            'modpack_id' => $modpack->id,
            'version' => '1.0.2',
            'published' => true,
        ]);

        $build = factory(App\Build::class)->create([
            'modpack_id' => $modpack->id,
            'version' => '1.0.3',
            'published' => true,
        ]);

        // Link release with build
        $build->releases()->attach($release);
    }
}
