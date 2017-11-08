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

class DeleteBuildTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_admin_can_delete_a_build()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $modpack->builds()->save(factory(Build::class)->make(['version' => '1.0.0']));
        $this->assertEquals(1, Build::count());

        $response = $this->actingAs($user)->delete('/modpacks/brothers-klaus/1.0.0');

        $response->assertRedirect('/modpacks/brothers-klaus');
        $this->assertEquals(0, Build::count());
    }

    /** @test */
    public function an_authorized_user_who_is_a_collaborator_can_delete_a_build()
    {
        $user = factory(User::class)->create();
        $user->grantRole('update-modpack');
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $modpack->addCollaborator($user->id);
        $modpack->builds()->save(factory(Build::class)->make(['version' => '1.0.0']));
        $this->assertEquals(1, Build::count());

        $response = $this->actingAs($user)->delete('/modpacks/brothers-klaus/1.0.0');

        $response->assertRedirect('/modpacks/brothers-klaus');
        $this->assertEquals(0, Build::count());
    }

    /** @test */
    public function an_authorized_user_who_is_not_a_collaborator_cannot_delete_a_build()
    {
        $user = factory(User::class)->create();
        $user->grantRole('update-modpack');
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $modpack->builds()->save(factory(Build::class)->make(['version' => '1.0.0']));
        $this->assertEquals(1, Build::count());

        $response = $this->actingAs($user)->delete('/modpacks/brothers-klaus/1.0.0');

        $response->assertStatus(403);
        $this->assertEquals(1, Build::count());
    }

    /** @test */
    public function an_unauthorized_user_cannot_delete_a_build()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $modpack->builds()->save(factory(Build::class)->make(['version' => '1.0.0']));
        $this->assertEquals(1, Build::count());

        $response = $this->actingAs($user)->delete('/modpacks/brothers-klaus/1.0.0');

        $response->assertStatus(403);
        $this->assertEquals(1, Build::count());
    }

    /** @test */
    public function a_guest_cannot_delete_a_build()
    {
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $modpack->builds()->save(factory(Build::class)->make(['version' => '1.0.0']));
        $this->assertEquals(1, Build::count());

        $response = $this->delete('/modpacks/brothers-klaus/1.0.0');

        $response->assertRedirect('/login');
        $this->assertEquals(1, Build::count());
    }

    /** @test */
    public function cannot_delete_build_of_a_different_modpack()
    {
        $user = factory(User::class)->states('admin')->create();
        $otherModpack = factory(Modpack::class)->create(['slug' => 'other-modpack']);
        $otherBuild = factory(Build::class)->create(['modpack_id' => $otherModpack->id, 'version' => '1.0.0']);
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $build = factory(Build::class)->create(['modpack_id' => $modpack->id, 'version' => '1.0.0']);

        $response = $this->actingAs($user)->delete('/modpacks/brothers-klaus/1.0.0');

        $response->assertRedirect('/modpacks/brothers-klaus');
        $this->assertDatabaseHas('builds', ['id' => $otherBuild->id]);
        $this->assertDatabaseMissing('builds', ['id' => $build->id]);
    }

    /** @test */
    public function attempting_to_delete_an_invalid_build_returns_404()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);

        $response = $this->actingAs($user)->delete('/modpacks/brothers-klaus/invalid-build');

        $response->assertStatus(404);
    }

    /** @test */
    public function attempting_to_delete_a_build_with_invalid_modpack_returns_404()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $build = factory(Build::class)->create(['modpack_id' => $modpack->id, 'version' => '1.0.0']);

        $response = $this->actingAs($user)->delete('/modpacks/invalid-modpack/1.0.0');

        $response->assertStatus(404);
    }
}
