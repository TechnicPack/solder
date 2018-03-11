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

class AddBundleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_admin_can_create_a_bundle()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'iron-tanks']);
        $build = $modpack->builds()->save(factory(Build::class)->make(['version' => '1.0.0']));
        $release = factory(Release::class)->create();

        $response = $this->actingAs($user)->post('bundles', [
            'build_id' => $build->id,
            'release_id' => $release->id,
        ]);

	$response->assertStatus(200);
        $response->assertJsonFragment([
            'status' => 'success',
            'redirect' => '/modpacks/iron-tanks/1.0.0',
        ]);
        $this->assertCount(1, Bundle::all());
        $this->assertTrue($build->fresh()->releases->first()->is($release));
    }

    /** @test */
    public function an_authorized_user_can_create_a_bundle()
    {
        $user = factory(User::class)->create();
        $user->grantRole('update-modpack');
        $modpack = factory(Modpack::class)->create(['slug' => 'iron-tanks']);
        $build = $modpack->builds()->save(factory(Build::class)->make(['version' => '1.0.0']));
        $release = factory(Release::class)->create();

        $response = $this->actingAs($user)->post('/bundles', [
            'build_id' => $build->id,
            'release_id' => $release->id,
        ]);

	$response->assertStatus(200);
        $response->assertJsonFragment([
            'status' => 'success',
            'redirect' => '/modpacks/iron-tanks/1.0.0',
        ]);
        $this->assertCount(1, Bundle::all());
        $this->assertTrue($build->fresh()->releases->first()->is($release));
    }

    /** @test */
    public function an_unauthorized_user_cannot_create_a_bundle()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'iron-tanks']);
        $build = $modpack->builds()->save(factory(Build::class)->make(['version' => '1.0.0']));
        $release = factory(Release::class)->create();

        $response = $this->actingAs($user)->post('/bundles', [
            'build_id' => $build->id,
            'release_id' => $release->id,
        ]);

        $response->assertStatus(403);
        $this->assertCount(0, Bundle::all());
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
}
