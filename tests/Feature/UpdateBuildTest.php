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

    private function oldAttributes($overrides = [])
    {
        return array_merge([
            'version' => '1.2.3',
            'status' => 'private',
        ], $overrides);
    }

    /** @test */
    public function a_user_can_update_a_build()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $build = factory(Build::class)->create([
            'version' => '1.2.3',
            'status' => 'private',
            'modpack_id' => $modpack->id,
        ]);

        $response = $this->actingAs($user)->patch('/modpacks/brothers-klaus/1.2.3', [
            'version' => '4.5.6',
            'status' => 'public',
        ]);

        tap($build->fresh(), function ($build) use ($response) {
            $response->assertRedirect('/modpacks/brothers-klaus/4.5.6');

            $this->assertEquals('4.5.6', $build->version);
            $this->assertEquals('public', $build->status);
        });
    }

    /** @test */
    public function a_guest_cannot_update_a_build()
    {
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $build = factory(Build::class)->create([
            'version' => '1.2.3',
            'status' => 'private',
            'modpack_id' => $modpack->id,
        ]);

        $response = $this->patch('/modpacks/brothers-klaus/1.2.3', [
            'version' => '4.5.6',
            'status' => 'public',
        ]);

        tap($build->fresh(), function ($build) use ($response) {
            $response->assertRedirect('/login');

            $this->assertEquals('1.2.3', $build->version);
            $this->assertEquals('private', $build->status);
        });
    }

    /** @test */
    public function all_parameters_are_optional()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $build = factory(Build::class)->create([
            'version' => '1.2.3',
            'status' => 'private',
            'modpack_id' => $modpack->id,
        ]);

        $response = $this->actingAs($user)->patch('/modpacks/brothers-klaus/1.2.3', [
            // empty set
        ]);

        $response->assertRedirect('/modpacks/brothers-klaus/1.2.3');
        $this->assertArraySubset([
            'version' => '1.2.3',
            'status' => 'private',
            'modpack_id' => $modpack->id,
        ], $build->fresh()->getAttributes());
    }

    /** @test */
    public function version_cannot_be_blank()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $build = factory(Build::class)->create([
            'version' => '1.2.3',
            'status' => 'private',
            'modpack_id' => $modpack->id,
        ]);

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus/1.2.3')->patch('/modpacks/brothers-klaus/1.2.3', [
            'version' => '',
        ]);

        $response->assertRedirect('/modpacks/brothers-klaus/1.2.3');
        $response->assertSessionHasErrors('version');
        $this->assertArraySubset([
            'version' => '1.2.3',
            'status' => 'private',
            'modpack_id' => $modpack->id,
        ], $build->fresh()->getAttributes());
    }

    /** @test */
    public function version_must_be_unique_per_modpack()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $build = factory(Build::class)->create([
            'version' => '1.2.3',
            'status' => 'private',
            'modpack_id' => $modpack->id,
        ]);
        $otherBuild = factory(Build::class)->create([
            'version' => '4.5.6',
            'status' => 'private',
            'modpack_id' => $modpack->id,
        ]);

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus/1.2.3')->patch('/modpacks/brothers-klaus/1.2.3', [
            'version' => '4.5.6',
        ]);

        $response->assertRedirect('/modpacks/brothers-klaus/1.2.3');
        $response->assertSessionHasErrors('version');
        $this->assertArraySubset([
            'version' => '1.2.3',
            'status' => 'private',
            'modpack_id' => $modpack->id,
        ], $build->fresh()->getAttributes());
    }

    /** @test */
    public function version_can_be_re_submitted()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $build = factory(Build::class)->create([
            'version' => '1.2.3',
            'status' => 'private',
            'modpack_id' => $modpack->id,
        ]);

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus/1.2.3')->patch('/modpacks/brothers-klaus/1.2.3', [
            'version' => '1.2.3',
        ]);

        $response->assertRedirect('/modpacks/brothers-klaus/1.2.3');
        $response->assertSessionMissing('errors');
        $this->assertArraySubset([
            'version' => '1.2.3',
            'status' => 'private',
            'modpack_id' => $modpack->id,
        ], $build->fresh()->getAttributes());
    }

    /** @test */
    public function version_only_needs_to_be_unique_per_modpack()
    {
        $user = factory(User::class)->create();
        $otherModpack = factory(Modpack::class)->create(['slug' => 'other-modpack']);
        $otherBuild = factory(Build::class)->create([
            'version' => '4.5.6',
            'modpack_id' => $otherModpack->id,
        ]);
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $build = factory(Build::class)->create([
            'version' => '1.2.3',
            'status' => 'private',
            'modpack_id' => $modpack->id,
        ]);

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus/1.2.3')->patch('/modpacks/brothers-klaus/1.2.3', [
            'version' => '4.5.6',
        ]);

        $response->assertRedirect('/modpacks/brothers-klaus/4.5.6');
        $response->assertSessionMissing('errors');
        $this->assertArraySubset([
            'version' => '4.5.6',
            'status' => 'private',
            'modpack_id' => $modpack->id,
        ], $build->fresh()->getAttributes());
    }

    /** @test */
    public function status_cannot_be_blank()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $build = factory(Build::class)->create([
            'version' => '1.2.3',
            'status' => 'private',
            'modpack_id' => $modpack->id,
        ]);

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus/1.2.3')->patch('/modpacks/brothers-klaus/1.2.3', [
            'status' => '',
        ]);

        $response->assertRedirect('/modpacks/brothers-klaus/1.2.3');
        $response->assertSessionHasErrors('status');
        $this->assertArraySubset([
            'version' => '1.2.3',
            'status' => 'private',
            'modpack_id' => $modpack->id,
        ], $build->fresh()->getAttributes());
    }

    /** @test */
    public function status_is_valid()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $build = factory(Build::class)->create([
            'version' => '1.2.3',
            'status' => 'private',
            'modpack_id' => $modpack->id,
        ]);

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus/1.2.3')->patch('/modpacks/brothers-klaus/1.2.3', [
            'status' => 'invalid',
        ]);

        $response->assertRedirect('/modpacks/brothers-klaus/1.2.3');
        $response->assertSessionHasErrors('status');
        $this->assertArraySubset([
            'version' => '1.2.3',
            'status' => 'private',
            'modpack_id' => $modpack->id,
        ], $build->fresh()->getAttributes());
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

        $response = $this->actingAs($user)->patch('/modpacks/brothers-klaus/invalid-build', $this->validParams());

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

        $response = $this->actingAs($user)->patch('/modpacks/invalid-modpack/1.2.3', $this->validParams());

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

        $response = $this->actingAs($user)->patch('/modpacks/brothers-klaus/1.2.3', $this->validParams());

        $response->assertStatus(404);
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'version' => '1.2.3',
            'status' => 'private',
        ], $overrides);
    }
}
