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

class DeleteCollaboratorsTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function an_admin_can_remove_a_collaborator_from_a_modpack()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create();
        $collaborator = $modpack->addCollaborator($user->id);

        $this->assertTrue($modpack->userIsCollaborator($user));

        $response = $this->actingAs($user)->delete('collaborators/'.$collaborator->id);

        $response->assertRedirect('modpacks/'.$modpack->slug);
        $this->assertFalse($modpack->userIsCollaborator($user));
    }

    /** @test **/
    public function an_authorized_collaborator_can_remove_a_collaborator_from_a_modpack()
    {
        $user = factory(User::class)->create();
        $user->grantRole('update-modpack');
        $modpack = factory(Modpack::class)->create();
        $collaborator = $modpack->addCollaborator($user->id);

        $this->assertTrue($modpack->userIsCollaborator($user));

        $response = $this->actingAs($user)->delete('collaborators/'.$collaborator->id);

        $response->assertRedirect('modpacks/'.$modpack->slug);
        $this->assertFalse($modpack->userIsCollaborator($user));
    }

    /** @test **/
    public function an_user_cannot_remove_a_collaborator_from_a_modpack()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create();
        $collaborator = $modpack->addCollaborator($user->id);

        $this->assertTrue($modpack->userIsCollaborator($user));

        $response = $this->actingAs($user)->delete('collaborators/'.$collaborator->id);

        $response->assertStatus(403);
        $this->assertTrue($modpack->userIsCollaborator($user));
    }

    /** @test **/
    public function a_guest_cannot_remove_a_collaborator_from_a_modpack()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create();
        $collaborator = $modpack->addCollaborator($user->id);

        $this->assertTrue($modpack->userIsCollaborator($user));

        $response = $this->delete('collaborators/'.$collaborator->id);

        $response->assertRedirect('/login');
        $this->assertTrue($modpack->userIsCollaborator($user));
    }
}
