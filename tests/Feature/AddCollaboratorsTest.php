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
use App\Collaborator;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddCollaboratorsTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function an_admin_can_add_a_collaborator_to_a_modpack()
    {
        $this->withExceptionHandling();
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create();

        $response = $this->actingAs($user)->post('modpacks/'.$modpack->slug.'/collaborators', [
            'user_id' => $user->id,
        ]);

        $response->assertRedirect('modpacks/'.$modpack->slug);
        $this->assertTrue($modpack->userIsCollaborator($user));
    }

    /** @test **/
    public function an_authorized_collaborator_can_add_a_collaborator_to_a_modpack()
    {
        $this->withExceptionHandling();
        $user = factory(User::class)->create();
        $user->grantRole('update-modpack');
        $modpack = factory(Modpack::class)->create();
        $modpack->addCollaborator($user->id);

        $response = $this->actingAs($user)->post('modpacks/'.$modpack->slug.'/collaborators', [
            'user_id' => $user->id,
        ]);

        $response->assertRedirect('modpacks/'.$modpack->slug);
        $this->assertTrue($modpack->userIsCollaborator($user));
    }

    /** @test **/
    public function an_user_cannot_add_a_collaborator_to_a_modpack()
    {
        $this->withExceptionHandling();
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create();

        $response = $this->actingAs($user)->post('modpacks/'.$modpack->slug.'/collaborators', [
            'user_id' => $user->id,
        ]);

        $response->assertStatus(403);
        $this->assertFalse($modpack->userIsCollaborator($user));
    }

    /** @test **/
    public function a_guest_cannot_add_a_collaborator_to_a_modpack()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create();

        $response = $this->post('modpacks/'.$modpack->slug.'/collaborators', [
            'user_id' => $user->id,
        ]);

        $response->assertRedirect('/login');
        $this->assertFalse($modpack->userIsCollaborator($user));
    }

    /** @test **/
    public function user_id_is_required()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create();

        $response = $this->actingAs($user)
            ->from('modpacks/'.$modpack->slug)
            ->post('modpacks/'.$modpack->slug.'/collaborators', [
                // null set
            ]);

        $response->assertRedirect('modpacks/'.$modpack->slug);
        $response->assertSessionHasErrors('user_id');
        $this->assertCount(0, Collaborator::all());
    }

    /** @test **/
    public function user_id_must_be_valid()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create();

        $response = $this->actingAs($user)
            ->from('modpacks/'.$modpack->slug)
            ->post('modpacks/'.$modpack->slug.'/collaborators', [
                'user_id' => '99',
            ]);

        $response->assertRedirect('modpacks/'.$modpack->slug);
        $response->assertSessionHasErrors('user_id');
        $this->assertCount(0, Collaborator::all());
    }
}
