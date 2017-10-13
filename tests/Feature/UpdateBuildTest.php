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
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateBuildTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_update_a_build()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $build = $modpack->builds()->save(factory(Build::class)->make([
            'version' => '1.2.3',
            'status' => 'private',
            'minecraft_version' => '1.6.4',
            'forge_version' => '1.2.3456',
            'java_version' => '1.8',
            'required_memory' => 1024,
        ]));

        $response = $this->actingAs($user)
            ->post('/modpacks/brothers-klaus/1.2.3', [
                'version' => '4.5.6',
                'status' => 'public',
                'minecraft_version' => '1.11.2',
                'forge_version' => '7.8.910',
                'java_version' => '1.8',
                'required_memory' => 2048,
            ]);

        tap($build->fresh(), function ($build) use ($response) {
            $response->assertRedirect('/modpacks/brothers-klaus/4.5.6');

            $this->assertEquals('4.5.6', $build->version);
            $this->assertEquals('public', $build->status);
            $this->assertEquals('1.11.2', $build->minecraft_version);
            $this->assertEquals('7.8.910', $build->forge_version);
            $this->assertEquals('1.8', $build->java_version);
            $this->assertEquals(2048, $build->required_memory);
        });
    }

    /** @test */
    public function a_guest_cannot_update_a_build()
    {
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $build = $modpack->builds()->save(factory(Build::class)->make($this->originalParams()));

        $response = $this->post('/modpacks/brothers-klaus/1.2.3', $this->validParams());

        $response->assertRedirect('/login');
        $this->assertArraySubset($this->originalParams(), $build->fresh()->getAttributes());
    }

    /** @test */
    public function all_attributes_are_optional()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $build = $modpack->builds()->save(factory(Build::class)->make($this->originalParams(['version' => '1.2.3'])));

        $response = $this->actingAs($user)
            ->post('/modpacks/brothers-klaus/1.2.3', [
                // empty set
            ]);

        $response->assertSessionMissing('errors');
        $response->assertRedirect('/modpacks/brothers-klaus/1.2.3');
        $this->assertArraySubset($this->originalParams(['version' => '1.2.3']), $build->fresh()->getAttributes());
    }

    /** @test */
    public function version_is_required()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $build = $modpack->builds()->save(factory(Build::class)->make($this->originalParams(['version' => '1.2.3'])));

        $response = $this->actingAs($user)
            ->from('/modpacks/brothers-klaus/1.2.3')
            ->post('/modpacks/brothers-klaus/1.2.3', $this->validParams([
                'version' => '',
            ]));

        $response->assertRedirect('/modpacks/brothers-klaus/1.2.3');
        $response->assertSessionHasErrors('version');
        $this->assertArraySubset($this->originalParams(), $build->fresh()->getAttributes());
    }

    /** @test */
    public function minecraft_version_is_required()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $build = $modpack->builds()->save(factory(Build::class)->make($this->originalParams(['version' => '1.2.3'])));

        $response = $this->actingAs($user)
            ->from('/modpacks/brothers-klaus/1.2.3')
            ->post('/modpacks/brothers-klaus/1.2.3', $this->validParams([
                'minecraft_version' => '',
            ]));

        $response->assertRedirect('/modpacks/brothers-klaus/1.2.3');
        $response->assertSessionHasErrors('minecraft_version');
        $this->assertArraySubset($this->originalParams(), $build->fresh()->getAttributes());
    }

    /** @test */
    public function version_must_be_unique_per_modpack()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $buildA = $modpack->builds()->save(factory(Build::class)->make($this->originalParams(['version' => '1.2.3'])));
        $buildB = $modpack->builds()->save(factory(Build::class)->make($this->originalParams(['version' => '4.5.6'])));

        $response = $this->actingAs($user)
            ->from('/modpacks/brothers-klaus/1.2.3')
            ->post('/modpacks/brothers-klaus/1.2.3', [
                'version' => '4.5.6',
            ]);

        $response->assertRedirect('/modpacks/brothers-klaus/1.2.3');
        $response->assertSessionHasErrors('version');
        $this->assertArraySubset($this->originalParams(), $buildA->fresh()->getAttributes());
    }

    /** @test */
    public function version_can_be_re_submitted()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $build = $modpack->builds()->save(factory(Build::class)->make($this->originalParams(['version' => '1.2.3'])));

        $response = $this->actingAs($user)
            ->post('/modpacks/brothers-klaus/1.2.3', $this->validParams([
                'version' => '1.2.3',
            ]));

        $response->assertRedirect('/modpacks/brothers-klaus/1.2.3');
        $response->assertSessionMissing('errors');
        $this->assertArraySubset($this->validParams(['version' => '1.2.3']), $build->fresh()->getAttributes());
    }

    /** @test */
    public function version_only_needs_to_be_unique_per_modpack()
    {
        $user = factory(User::class)->create();
        $modpackA = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $buildA = $modpackA->builds()->save(factory(Build::class)->make($this->originalParams(['version' => '1.2.3'])));
        $modpackB = factory(Modpack::class)->create();
        $buildB = $modpackB->builds()->save(factory(Build::class)->make($this->originalParams(['version' => '4.5.6'])));

        $response = $this->actingAs($user)
            ->from('/modpacks/brothers-klaus/1.2.3')
            ->post('/modpacks/brothers-klaus/1.2.3', $this->validParams([
                'version' => '4.5.6',
            ]));

        $response->assertRedirect('/modpacks/brothers-klaus/4.5.6');
        $response->assertSessionMissing('errors');
        $this->assertArraySubset($this->validParams(['version' => '4.5.6']), $buildA->fresh()->getAttributes());
    }

    /** @test */
    public function status_is_required()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $build = $modpack->builds()->save(factory(Build::class)->make($this->originalParams(['version' => '1.2.3'])));

        $response = $this->actingAs($user)
            ->from('/modpacks/brothers-klaus/1.2.3')
            ->post('/modpacks/brothers-klaus/1.2.3', $this->validParams([
                'status' => '',
            ]));

        $response->assertRedirect('/modpacks/brothers-klaus/1.2.3');
        $response->assertSessionHasErrors('status');
        $this->assertArraySubset($this->originalParams(), $build->fresh()->getAttributes());
    }

    /** @test */
    public function status_is_valid()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $build = $modpack->builds()->save(factory(Build::class)->make($this->originalParams(['version' => '1.2.3'])));

        $response = $this->actingAs($user)
            ->from('/modpacks/brothers-klaus/1.2.3')
            ->post('/modpacks/brothers-klaus/1.2.3', $this->validParams([
                'status' => 'invalid',
            ]));

        $response->assertRedirect('/modpacks/brothers-klaus/1.2.3');
        $response->assertSessionHasErrors('status');
        $this->assertArraySubset($this->originalParams(), $build->fresh()->getAttributes());
    }

    /** @test */
    public function required_memory_may_be_null()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $build = $modpack->builds()->save(factory(Build::class)->make($this->originalParams(['version' => '1.2.3'])));

        $response = $this->actingAs($user)
            ->post('/modpacks/brothers-klaus/1.2.3', $this->validParams([
                'required_memory' => '',
            ]));

        $response->assertRedirect('/modpacks/brothers-klaus/4.5.6');
        $response->assertSessionMissing('errors');
        $this->assertArraySubset($this->validParams(['required_memory' => '']), $build->fresh()->getAttributes());
    }

    /** @test */
    public function required_memory_must_be_numeric()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $build = $modpack->builds()->save(factory(Build::class)->make($this->originalParams(['version' => '1.2.3', 'required_memory' => 1024])));

        $response = $this->actingAs($user)
            ->from('/modpacks/brothers-klaus/1.2.3')
            ->post('/modpacks/brothers-klaus/1.2.3', $this->validParams([
                'required_memory' => '2GB',
            ]));

        $response->assertRedirect('/modpacks/brothers-klaus/1.2.3');
        $response->assertSessionHasErrors('required_memory');
        $this->assertArraySubset($this->originalParams(['required_memory' => 1024]), $build->fresh()->getAttributes());
    }

    /** @test */
    public function java_version_may_be_null()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $build = $modpack->builds()->save(factory(Build::class)->make($this->originalParams(['version' => '1.2.3'])));

        $response = $this->actingAs($user)
            ->post('/modpacks/brothers-klaus/1.2.3', $this->validParams([
                'java_version' => '',
            ]));

        $response->assertRedirect('/modpacks/brothers-klaus/4.5.6');
        $response->assertSessionMissing('errors');
        $this->assertArraySubset($this->validParams(['java_version' => '']), $build->fresh()->getAttributes());
    }

    /** @test */
    public function forge_version_may_be_null()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $build = $modpack->builds()->save(factory(Build::class)->make($this->originalParams(['version' => '1.2.3'])));

        $response = $this->actingAs($user)
            ->post('/modpacks/brothers-klaus/1.2.3', $this->validParams([
                'forge_version' => '',
            ]));

        $response->assertRedirect('/modpacks/brothers-klaus/4.5.6');
        $response->assertSessionMissing('errors');
        $this->assertArraySubset($this->validParams(['forge_version' => '']), $build->fresh()->getAttributes());
    }

    /** @test */
    public function attempting_to_update_an_invalid_build_returns_404()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $build = factory(Build::class)->create([
            'version' => '1.2.3',
            'status' => 'private',
            'modpack_id' => $modpack->id,
        ]);

        $response = $this->actingAs($user)->post('/modpacks/brothers-klaus/invalid-build', $this->validParams());

        $response->assertStatus(404);
    }

    /** @test */
    public function attempting_to_update_a_build_with_invalid_modpack_returns_404()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $build = factory(Build::class)->create([
            'version' => '1.2.3',
            'status' => 'private',
            'modpack_id' => $modpack->id,
        ]);

        $response = $this->actingAs($user)->post('/modpacks/invalid-modpack/1.2.3', $this->validParams());

        $response->assertStatus(404);
    }

    /** @test */
    public function attempting_to_access_a_mismatched_modpack_and_build_returns_404()
    {
        $user = factory(User::class)->create();
        factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        factory(Build::class)->create([
            'version' => '1.2.3',
            'modpack_id' => '99',
        ]);

        $response = $this->actingAs($user)->post('/modpacks/brothers-klaus/1.2.3', $this->validParams());

        $response->assertStatus(404);
    }

    private function originalParams($overrides = [])
    {
        return array_merge([
            'version' => '1.2.3',
            'status' => 'private',
            'minecraft_version' => '1.6.4',
            'forge_version' => '1.2.3456',
            'java_version' => '1.8',
            'required_memory' => 1024,
        ], $overrides);
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'version' => '4.5.6',
            'status' => 'public',
            'minecraft_version' => '1.11.2',
            'forge_version' => '7.8.910',
            'java_version' => '1.8',
            'required_memory' => 2048,
        ], $overrides);
    }
}
