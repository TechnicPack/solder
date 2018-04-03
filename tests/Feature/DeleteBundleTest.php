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
use App\Bundle;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteBundleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_admin_can_delete_a_bundle()
    {
        $user = factory(User::class)->states('admin')->create();
        $bundle = factory(Bundle::class)->create();
        $this->assertCount(1, Bundle::all());

        $response = $this->actingAs($user)->delete('bundles', [
            'build_id' => $bundle->build_id,
            'release_id' => $bundle->release_id,
        ]);

        $response->assertStatus(204);
        $this->assertCount(0, Bundle::all());
    }

    /** @test */
    public function an_authorized_user_can_delete_a_bundle()
    {
        $user = factory(User::class)->create();
        $user->grantRole('update-modpack');
        $bundle = factory(Bundle::class)->create();
        $this->assertEquals(1, Bundle::count());

        $response = $this->actingAs($user)->delete('bundles', [
            'build_id' => $bundle->build_id,
            'release_id' => $bundle->release_id,
        ]);

        $response->assertStatus(204);
        $this->assertEquals(0, Bundle::count());
    }

    /** @test */
    public function an_unauthorized_user_cannot_delete_a_bundle()
    {
        $user = factory(User::class)->create();
        $bundle = factory(Bundle::class)->create();
        $this->assertEquals(1, Bundle::count());

        $response = $this->actingAs($user)->delete('bundles', [
            'build_id' => $bundle->build_id,
            'release_id' => $bundle->release_id,
        ]);

        $response->assertStatus(403);
        $this->assertEquals(1, Bundle::count());
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
