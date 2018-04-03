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
use App\Modpack;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteModpackTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_admin_can_delete_a_modpack()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create();
        $this->assertEquals(1, Modpack::count());

        $response = $this->actingAs($user)->delete("modpacks/{$modpack->slug}");

        $response->assertRedirect('/');
        $this->assertEquals(0, Modpack::count());
    }

    /** @test */
    public function an_authorized_user_who_is_a_collaborator_can_delete_a_modpack()
    {
        $user = factory(User::class)->create();
        $user->grantRole('delete-modpack');
        $modpack = factory(Modpack::class)->create();
        $modpack->addCollaborator($user->id);
        $this->assertEquals(1, Modpack::count());

        $response = $this->actingAs($user)->delete("modpacks/{$modpack->slug}");

        $response->assertRedirect('/');
        $this->assertEquals(0, Modpack::count());
    }

    /** @test */
    public function an_authorized_user_who_is_not_a_collaborator_cannot_delete_a_modpack()
    {
        $user = factory(User::class)->create();
        $user->grantRole('delete-modpack');
        $modpack = factory(Modpack::class)->create();
        $this->assertEquals(1, Modpack::count());

        $response = $this->actingAs($user)->delete("modpacks/{$modpack->slug}");

        $response->assertStatus(403);
        $this->assertEquals(1, Modpack::count());
    }

    /** @test */
    public function an_unauthorized_user_cannot_delete_a_modpack()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create();
        $this->assertEquals(1, Modpack::count());

        $response = $this->actingAs($user)->delete("modpacks/{$modpack->slug}");

        $response->assertStatus(403);
        $this->assertEquals(1, Modpack::count());
    }

    /** @test */
    public function a_guest_cannot_delete_a_modpack()
    {
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $this->assertEquals(1, Modpack::count());

        $response = $this->delete('/modpacks/brothers-klaus');

        $response->assertRedirect('/login');
        $this->assertEquals(1, Modpack::count());
    }

    /** @test */
    public function cannot_delete_a_nonexistant_modpack()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->delete('/modpacks/not-a-modpack-slug');

        $response->assertStatus(404);
    }
}
