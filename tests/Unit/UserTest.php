<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
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
}
