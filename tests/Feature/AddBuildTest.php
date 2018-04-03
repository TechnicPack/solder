<?php

/*
 * This file is part of TechnicPack Solder.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Feature;

use App\User;
use App\Build;
use App\Modpack;
use App\Release;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddBuildTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_admin_can_create_a_build()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);

        $response = $this->actingAs($user)->post('/modpacks/brothers-klaus/builds', [
            'version' => '1.3.4_beta-2',
            'minecraft_version' => '1.7.10',
            'status' => 'public',
            'java_version' => '1.8.0',
            'forge_version' => '1.23.4566',
            'required_memory' => '2048',
        ]);

        tap(Build::first(), function ($build) use ($modpack, $response) {
            $this->assertEquals('1.3.4_beta-2', $build->version);
            $this->assertEquals('1.7.10', $build->minecraft_version);
            $this->assertEquals('public', $build->status);
            $this->assertEquals('1.8.0', $build->java_version);
            $this->assertEquals('2048', $build->required_memory);
            $this->assertEquals('1.23.4566', $build->forge_version);
            $this->assertEquals($modpack->id, $build->modpack_id);

            $response->assertRedirect('/modpacks/brothers-klaus');
        });
    }

    /** @test */
    public function an_authorized_user_who_is_a_collaborator_can_create_a_build()
    {
        $user = factory(User::class)->create();
        $user->grantRole('update-modpack');
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $modpack->addCollaborator($user->id);

        $response = $this->actingAs($user)
            ->post('/modpacks/brothers-klaus/builds', $this->validParams());

        $response->assertRedirect('modpacks/brothers-klaus');
        $this->assertCount(1, Build::all());
    }

    /** @test */
    public function an_authorized_user_who_is_not_a_collaborator_cannot_create_a_build()
    {
        $user = factory(User::class)->create();
        $user->grantRole('update-modpack');
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);

        $response = $this->actingAs($user)
            ->post('/modpacks/brothers-klaus/builds', $this->validParams());

        $response->assertStatus(403);
        $this->assertCount(0, Build::all());
    }

    /** @test */
    public function an_unauthorized_user_cannot_create_a_build()
    {
        $user = factory(User::class)->create();
        factory(Modpack::class)->create(['slug' => 'brothers-klaus']);

        $response = $this->actingAs($user)
            ->post('/modpacks/brothers-klaus/builds', $this->validParams());

        $response->assertStatus(403);
        $this->assertCount(0, Build::all());
    }

    /** @test */
    public function a_guest_cannot_create_a_build()
    {
        factory(Modpack::class)->create(['slug' => 'brothers-klaus']);

        $response = $this->post('/modpacks/brothers-klaus/builds', [
            'version' => '1.3.4b',
            'minecraft' => '1.7.10',
            'status' => 'public',
        ]);

        $response->assertRedirect('/login');
        $this->assertCount(0, Build::all());
    }

    /** @test */
    public function version_is_required()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus')->post('/modpacks/brothers-klaus/builds', $this->validParams([
            'version' => '',
        ]));

        $response->assertRedirect('/modpacks/brothers-klaus');
        $response->assertSessionHasErrors('version');
        $this->assertEquals(0, Build::count());
    }

    /** @test */
    public function version_must_be_unique_for_the_modpack()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $build = factory(Build::class)->create(['modpack_id' => $modpack->id, 'version' => '1.2.3']);

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus')->post('/modpacks/brothers-klaus/builds', $this->validParams([
            'version' => '1.2.3',
        ]));

        $response->assertRedirect('/modpacks/brothers-klaus');
        $response->assertSessionHasErrors('version');
        $this->assertEquals(1, Build::count());
    }

    /** @test */
    public function version_may_not_contain_spaces()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus')->post('/modpacks/brothers-klaus/builds', $this->validParams([
            'version' => 'invalid version',
        ]));

        $response->assertRedirect('/modpacks/brothers-klaus');
        $response->assertSessionHasErrors('version');
        $this->assertEquals(0, Build::count());
    }

    /** @test */
    public function version_may_not_contain_symbols()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus')->post('/modpacks/brothers-klaus/builds', $this->validParams([
            'version' => '!@#$%^&*()',
        ]));

        $response->assertRedirect('/modpacks/brothers-klaus');
        $response->assertSessionHasErrors('version');
        $this->assertEquals(0, Build::count());
    }

    /** @test */
    public function version_cannot_start_with_a_dot()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus')->post('/modpacks/brothers-klaus/builds', $this->validParams([
            'version' => '.8.0',
        ]));

        $response->assertRedirect('/modpacks/brothers-klaus');
        $response->assertSessionHasErrors('version');
        $this->assertEquals(0, Build::count());
    }

    /** @test */
    public function status_is_required()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus')->post('/modpacks/brothers-klaus/builds', $this->validParams([
            'status' => '',
        ]));

        $response->assertRedirect('/modpacks/brothers-klaus');
        $response->assertSessionHasErrors('status');
        $this->assertEquals(0, Build::count());
    }

    /** @test */
    public function status_is_valid()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus')->post('/modpacks/brothers-klaus/builds', $this->validParams([
            'status' => 'invalid',
        ]));

        $response->assertRedirect('/modpacks/brothers-klaus');
        $response->assertSessionHasErrors('status');
        $this->assertEquals(0, Build::count());
    }

    /** @test */
    public function minecraft_version_is_required()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus')->post('/modpacks/brothers-klaus/builds', $this->validParams([
            'minecraft_version' => '',
        ]));

        $response->assertRedirect('/modpacks/brothers-klaus');
        $response->assertSessionHasErrors('minecraft_version');
        $this->assertEquals(0, Build::count());
    }

    /** @test */
    public function required_memory_must_be_numeric()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus')->post('/modpacks/brothers-klaus/builds', $this->validParams([
            'required_memory' => 'lots',
        ]));

        $response->assertRedirect('/modpacks/brothers-klaus');
        $response->assertSessionHasErrors('required_memory');
        $this->assertEquals(0, Build::count());
    }

    /** @test */
    public function required_memory_may_be_null()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $this->assertEquals(0, Build::count());

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus')->post('/modpacks/brothers-klaus/builds', $this->validParams([
            'required_memory' => '',
        ]));

        $response->assertRedirect('/modpacks/brothers-klaus');
        $response->assertSessionMissing('errors');
        $this->assertEquals(1, Build::count());
    }

    /** @test */
    public function java_version_may_be_null()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $this->assertEquals(0, Build::count());

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus')->post('/modpacks/brothers-klaus/builds', $this->validParams([
            'java_version' => '',
        ]));

        $response->assertRedirect('/modpacks/brothers-klaus');
        $response->assertSessionMissing('errors');
        $this->assertEquals(1, Build::count());
    }

    /** @test */
    public function forge_version_may_be_null()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $this->assertEquals(0, Build::count());

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus')->post('/modpacks/brothers-klaus/builds', $this->validParams([
            'forge_version' => '',
        ]));

        $response->assertRedirect('/modpacks/brothers-klaus');
        $response->assertSessionMissing('errors');
        $this->assertEquals(1, Build::count());
    }

    /** @test **/
    public function a_new_build_can_clone_an_existing_build()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $build = factory(Build::class)->create(['modpack_id' => $modpack->id, 'version' => '1.0.0']);
        $release = factory(Release::class)->create();
        $build->releases()->attach($release);

        $response = $this->actingAs($user)->post('/modpacks/brothers-klaus/builds', $this->validParams([
            'version' => '1.0.1',
            'clone_build_id' => $build->id,
        ]));

        $response->assertRedirect('/modpacks/brothers-klaus');
        $this->assertCount(2, $modpack->fresh()->builds);
        tap($modpack->builds()->where('version', '1.0.1')->first(), function ($clonedBuild) {
            $this->assertCount(1, $clonedBuild->releases);
        });
    }

    /** @test **/
    public function clone_build_id_must_be_valid_build_id_for_modpack()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus')
            ->post('/modpacks/brothers-klaus/builds', $this->validParams([
                'version' => '1.0.1',
                'clone_build_id' => 99,
            ]));

        $response->assertRedirect('/modpacks/brothers-klaus');
        $response->assertSessionHasErrors('clone_build_id');
        $this->assertEquals(0, Build::count());
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'version' => '1.3.4_beta-2',
            'minecraft_version' => '1.7.10',
            'status' => 'public',
        ], $overrides);
    }
}
