<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Feature;

use App\User;
use App\Build;
use App\Modpack;
use App\Resource;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SupportsLegacyBuildApiTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_guest_can_get_build_details_for_a_public_build_of_a_public_modpack()
    {
        factory(Modpack::class)->states(['public'])->create(['slug' => 'public-modpack'])
            ->builds()->save(factory(Build::class)->states(['public'])->make([
                'build_number' => 'public-build',
                'minecraft_version' => 'minecraft123',
            ]));

        $response = $this->get('api/modpack/public-modpack/public-build');

        $response->assertStatus(200);
        $response->assertSee('minecraft123');
    }

    /** @test */
    public function a_guest_can_get_build_details_for_a_public_build_of_an_unlisted_modpack()
    {
        factory(Modpack::class)->states(['unlisted'])->create(['slug' => 'unlisted-modpack'])
            ->builds()->save(factory(Build::class)->states(['public'])->make([
                'build_number' => 'public-build',
                'minecraft_version' => 'minecraft123',
            ]));

        $response = $this->get('api/modpack/unlisted-modpack/public-build');

        $response->assertStatus(200);
        $response->assertSee('minecraft123');
    }

    /** @test */
    public function a_guest_cannot_get_build_details_for_a_private_modpack()
    {
        factory(Modpack::class)->states(['private'])->create(['slug' => 'private-modpack'])
            ->builds()->save(factory(Build::class)->states(['public'])->make(['build_number' => 'public-build']));

        $response = $this->get('api/modpack/private-modpack/public-build');

        $response->assertStatus(404);
        $response->assertExactJson([
            'status' => 404,
            'error' => 'Modpack does not exist',
        ]);
    }

    /** @test */
    public function a_guest_cannot_get_build_details_for_a_draft_build()
    {
        factory(Modpack::class)->states(['public'])->create(['slug' => 'public-modpack'])
            ->builds()->save(factory(Build::class)->states(['draft'])->make(['build_number' => 'draft-build']));

        $response = $this->get('api/modpack/public-modpack/draft-build');

        $response->assertStatus(404);
        $response->assertExactJson([
            'status' => 404,
            'error' => 'Build does not exist',
        ]);
    }

    /** @test */
    public function a_guest_cannot_get_build_details_for_a_private_build()
    {
        factory(Modpack::class)->states(['public'])->create(['slug' => 'public-modpack'])
            ->builds()->save(factory(Build::class)->states(['private'])->make(['build_number' => 'private-build']));

        $response = $this->get('api/modpack/public-modpack/private-build');

        $response->assertStatus(404);
        $response->assertExactJson([
            'status' => 404,
            'error' => 'Build does not exist',
        ]);
    }

    /** @test */
    public function a_known_client_cannot_get_build_details_for_a_draft_build()
    {
        factory(Modpack::class)->states(['public'])->create(['slug' => 'public-modpack'])
            ->authorizeUser(factory(User::class)->create()->addToken('Test Token', 'TESTTOKEN123'))
            ->builds()->save(factory(Build::class)->states(['draft'])->make(['build_number' => 'private-build', 'minecraft_version' => 'minecraft123']));

        $response = $this->get('api/modpack/public-modpack/draft-build?cid=TESTTOKEN123');

        $response->assertStatus(404);
        $response->assertJson([
            'status' => 404,
            'error' => 'Build does not exist',
        ]);
    }

    /** @test */
    public function a_known_client_can_get_build_details_for_a_private_build()
    {
        factory(Modpack::class)->states(['public'])->create(['slug' => 'public-modpack'])
            ->authorizeUser(factory(User::class)->create()->addToken('Test Token', 'TESTTOKEN123'))
            ->builds()->save(factory(Build::class)->states(['private'])->make(['build_number' => 'private-build', 'minecraft_version' => 'minecraft123']));

        $response = $this->get('api/modpack/public-modpack/private-build?cid=TESTTOKEN123');

        $response->assertStatus(200);
        $response->assertSee('minecraft123');
    }

    /** @test */
    public function an_error_is_returned_when_an_invalid_build_number_is_provided()
    {
        factory(Modpack::class)->create(['slug' => 'test']);

        $response = $this->json('GET', 'api/modpack/test/INVALIDBUILD');

        $response->assertStatus(404);
        $response->assertJson([
            'status' => 404,
            'error' => 'Build does not exist',
        ]);
    }

    /** @test */
    public function build_details_include_a_mod_list()
    {
        \Storage::shouldReceive('url', 'url')->andReturn('http://example.com/mod-version-1.zip', 'http://example.com/mod-version-2.zip');
        $modpack = factory(Modpack::class)->states(['public'])->create(['slug' => 'test']);
        $build = $modpack->builds()->save(factory(Build::class)->states(['public'])->make(['build_number' => '1.0.0']));
        $modVersion1 = factory(Resource::class)->create(['slug' => 'mod-1'])->versions()->create(['version_number' => '1.2.3', 'zip_md5' => 'somehash123', 'zip_path' => 'path-to-file-1']);
        $modVersion2 = factory(Resource::class)->create(['slug' => 'mod-2'])->versions()->create(['version_number' => '4.5.6', 'zip_md5' => 'somehash123', 'zip_path' => 'path-to-file-2']);
        $build->addVersion($modVersion1)->addVersion($modVersion2);

        $response = $this->get('api/modpack/test/1.0.0');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'mods' => [
                [
                    'name' => 'mod-1',
                    'version' => '1.2.3',
                    'md5' => 'somehash123',
                    'url' => 'http://example.com/mod-version-1.zip',
                ],
                [
                    'name' => 'mod-2',
                    'version' => '4.5.6',
                    'md5' => 'somehash123',
                    'url' => 'http://example.com/mod-version-2.zip',
                ],
            ],
        ]);
    }
}
