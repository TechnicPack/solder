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
use App\Bundle;
use App\Modpack;
use App\Release;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageBundleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_create_a_bundle()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'iron-tanks']);
        $build = factory(Build::class)->create(['modpack_id' => $modpack->id, 'version' => '1.0.0']);
        $release = factory(Release::class)->create();

        $response = $this->actingAs($user)->post('/bundles', [
            'build_id' => $build->id,
            'release_id' => $release->id,
        ]);

        $response->assertRedirect('/modpacks/iron-tanks/1.0.0');
        $this->assertCount(1, Bundle::all());
        $this->assertTrue($build->fresh()->releases->first()->is($release));
    }

    /** @test */
    public function a_guest_cannot_create_a_bundle()
    {
        $modpack = factory(Modpack::class)->create(['slug' => 'iron-tanks']);
        $build = factory(Build::class)->create(['modpack_id' => $modpack->id, 'version' => '1.0.0']);
        $release = factory(Release::class)->create();

        $response = $this->post('/bundles', [
            'build_id' => $build->id,
            'release_id' => $release->id,
        ]);

        $response->assertRedirect('/login');
        $this->assertCount(0, Bundle::all());
    }

    /** @test */
    public function a_user_can_delete_a_bundle()
    {
        $user = factory(User::class)->create();
        $bundle = factory(Bundle::class)->create();
        $this->assertCount(1, Bundle::all());

        $response = $this->actingAs($user)->delete('/bundles', [
            'build_id' => $bundle->build_id,
            'release_id' => $bundle->release_id,
        ]);

        $response->assertStatus(204);
        $this->assertCount(0, Bundle::all());
    }

    /** @test */
    public function a_guest_cannot_delete_a_bundle()
    {
        $bundle = factory(Bundle::class)->create();
        $this->assertCount(1, Bundle::all());

        $response = $this->delete('/bundles', [
            'build_id' => $bundle->build_id,
            'release_id' => $bundle->release_id,
        ]);

        $response->assertRedirect('/login');
        $this->assertCount(1, Bundle::all());
    }

    /** @test */
    public function a_user_cannot_delete_a_non_existent_bundle()
    {
        $user = factory(User::class)->create();
        factory(Bundle::class)->create();
        $this->assertCount(1, Bundle::all());

        $response = $this->actingAs($user)->delete('/bundles', [
            'build_id' => '99',
            'release_id' => '99',
        ]);

        $response->assertStatus(404);
        $this->assertCount(1, Bundle::all());
    }
}
