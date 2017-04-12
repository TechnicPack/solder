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
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SupportsLegacyModpackApiTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_guest_can_only_list_public_modpacks()
    {
        factory(Modpack::class)->states(['public'])->create(['name' => 'Example Modpack 1', 'slug' => 'test1']);
        factory(Modpack::class)->states(['public'])->create(['name' => 'Example Modpack 2', 'slug' => 'test2']);
        factory(Modpack::class)->states(['unlisted'])->create(['name' => 'Unlisted Modpack', 'slug' => 'unlisted']);
        factory(Modpack::class)->states(['private'])->create(['name' => 'Private Modpack', 'slug' => 'private']);

        $response = $this->json('GET', 'api/modpack');

        $response->assertStatus(200);
        $response->assertJson([
            'modpacks' => [
                'test1' => 'Example Modpack 1',
                'test2' => 'Example Modpack 2',
            ],
        ]);
        $response->assertDontSee('Unlisted Modpack');
        $response->assertDontSee('Private Modpack');
    }

    /** @test */
    public function a_known_client_can_list_public_and_private_modpacks_but_not_unlisted()
    {
        $user = factory(User::class)->create()->addToken('Test Token', 'TESTTOKEN');
        factory(Modpack::class)->states(['public'])->create(['name' => 'Public Modpack', 'slug' => 'public']);
        factory(Modpack::class)->states(['unlisted'])->create(['name' => 'Unlisted Modpack', 'slug' => 'unlisted']);
        factory(Modpack::class)->states(['private'])->create(['name' => 'Private Modpack', 'slug' => 'private'])->authorizeUser($user);

        $response = $this->json('GET', 'api/modpack?cid=TESTTOKEN');

        $response->assertStatus(200);
        $response->assertJson([
            'modpacks' => [
                'public' => 'Public Modpack',
                'private' => 'Private Modpack',
            ],
        ]);
        $response->assertDontSee('Unlisted Modpack');
    }

    /** @test */
    public function a_guest_can_get_public_modpack_details()
    {
        factory(Modpack::class)->states(['public'])->create([
            'name' => 'Example Modpack',
            'slug' => 'test',
        ]);

        $response = $this->json('GET', 'api/modpack/test');

        $response->assertStatus(200);
        $response->assertJson([
            'name' => 'test',
            'display_name' => 'Example Modpack',
        ]);
    }

    /** @test */
    public function a_guest_can_get_list_of_public_build_of_a_public_modpack()
    {
        $modpack = factory(Modpack::class)->states(['public'])->create(['slug' => 'test']);
        $modpack->builds()->save(factory(Build::class)->states(['public'])->make(['build_number' => 'build-1']));
        $modpack->builds()->save(factory(Build::class)->states(['public'])->make(['build_number' => 'build-2']));

        $response = $this->get('api/modpack/test');

        $response->assertStatus(200);
        $response->assertJson([
            'builds' => [
                'build-1',
                'build-2',
            ],
        ]);
    }

    /** @test */
    public function a_guest_cannot_get_list_of_private_or_draft_builds_of_a_public_modpack()
    {
        $modpack = factory(Modpack::class)->states(['public'])->create(['slug' => 'test']);
        $modpack->builds()->save(factory(Build::class)->states(['private'])->make(['build_number' => 'private-build']));
        $modpack->builds()->save(factory(Build::class)->states(['draft'])->make(['build_number' => 'draft-build']));

        $response = $this->get('api/modpack/test');

        $response->assertStatus(200);
        $response->assertDontSee('private-build');
        $response->assertDontSee('draft-build');
    }

    /** @test */
    public function a_guest_can_get_unlisted_modpack_details()
    {
        factory(Modpack::class)->states(['unlisted'])->create([
            'name' => 'Example Modpack',
            'slug' => 'test',
        ]);

        $response = $this->json('GET', 'api/modpack/test');

        $response->assertStatus(200);
        $response->assertSee('Example Modpack');
    }

    /** @test */
    public function a_guest_cannot_get_private_modpack_details()
    {
        factory(Modpack::class)->states(['private'])->create([
            'name' => 'Example Modpack',
            'slug' => 'test',
        ]);

        $response = $this->json('GET', 'api/modpack/test');

        $response->assertStatus(404);
        $response->assertJson([
            'status' => 404,
            'error' => 'Modpack does not exist',
        ]);
    }

    /** @test */
    public function a_known_client_can_get_private_modpack_details()
    {
        $user = factory(User::class)->create()->addToken('Test Token', 'TESTTOKEN123');
        factory(Modpack::class)->states(['private'])->create([
            'name' => 'Example Modpack',
            'slug' => 'test',
        ])->authorizeUser($user);

        $response = $this->json('GET', 'api/modpack/test?cid=TESTTOKEN123');

        $response->assertStatus(200);
        $response->assertSee('Example Modpack');
    }

    /** @test */
    public function an_error_is_returned_when_an_invalid_modpack_slug_is_provided()
    {
        $response = $this->json('GET', 'api/modpack/INVALIDSLUG');

        $response->assertStatus(404);
        $response->assertJson([
            'status' => 404,
            'error' => 'Modpack does not exist',
        ]);
    }

    /** @test */
    public function full_details_can_be_included_in_the_mod_list_with_the_include_flag()
    {
        $modpack = factory(Modpack::class)->states(['public'])->create([
            'slug' => 'test',
            'name' => 'Test Modpack',
        ]);
        factory(Build::class)->states(['public'])->create([
            'build_number' => '1.0.0',
            'modpack_id' => $modpack->id,
        ]);

        $response = $this->json('GET', 'api/modpack?include=full');

        $response->assertStatus(200);
        $response->assertJson([
                'modpacks' => [
                    'test' => [
                    'name' => 'test',
                    'display_name' => 'Test Modpack',
                    'builds' => [
                        '1.0.0',
                    ],
                ],
            ],
        ]);
    }

    /** @test */
    public function modpacks_have_a_recommended_build()
    {
        factory(Modpack::class)->states(['public'])->create([
            'slug' => 'test',
            'recommended' => '1.0.0',
        ]);

        $response = $this->json('GET', 'api/modpack/test');

        $response->assertStatus(200);
        $response->assertJson(['recommended' => '1.0.0']);
    }

    /** @test */
    public function modpacks_have_a_latest_build()
    {
        factory(Modpack::class)->states(['public'])->create([
            'slug' => 'test',
            'latest' => '1.0.0',
        ]);

        $response = $this->json('GET', 'api/modpack/test');

        $response->assertStatus(200);
        $response->assertJson(['latest' => '1.0.0']);
    }

    /** @test */
    public function modpack_list_includes_mirror_url()
    {
        config(['app.mirror' => 'http://mirror.example.com/']);

        $response = $this->json('GET', 'api/modpack');

        $response->assertStatus(200);
        $response->assertJson(['mirror_url' => 'http://mirror.example.com/']);
    }
}
