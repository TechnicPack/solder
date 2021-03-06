<?php

/*
 * This file is part of TechnicPack Solder.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Unit;

use App\Role;
use App\Team;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_be_granted_an_existing_role_by_tag()
    {
        $role = Role::first();
        $user = factory(User::class)->create();
        $this->assertFalse($user->roles->contains($role));

        $user->grantRole($role->tag);

        $this->assertTrue($user->fresh()->roles->contains($role));
    }

    /** @test **/
    public function a_user_has_a_current_team()
    {
        $team = factory(Team::class)->create();
        $user = factory(User::class)->create();
        $user->teams()->attach($team);

        $user->switchToTeam($team);

        $this->assertTrue($user->currentTeam->is($team));
    }

    /** @test **/
    public function users_current_team_defaults_to_first_team()
    {
        $teamA = factory(Team::class)->create();
        $teamB = factory(Team::class)->create();
        $user = factory(User::class)->create(['current_team_id' => null]);
        $user->teams()->attach($teamA);
        $user->teams()->attach($teamB);

        $this->assertTrue($user->currentTeam->is($teamA));
    }

    /** @test **/
    public function determining_if_a_user_has_teams()
    {
        $userA = factory(User::class)->create();
        $userB = factory(User::class)->create();
        $team = factory(Team::class)->create();

        $team->users()->attach($userA);

        $this->assertTrue($userA->hasTeams());
        $this->assertFalse($userB->hasTeams());
    }

    /** @test **/
    public function determining_if_a_user_is_on_a_team()
    {
        $userA = factory(User::class)->create();
        $userB = factory(User::class)->create();
        $team = factory(Team::class)->create();

        $team->users()->attach($userA);

        $this->assertTrue($userA->onTeam($team));
        $this->assertFalse($userB->onTeam($team));
    }
}
