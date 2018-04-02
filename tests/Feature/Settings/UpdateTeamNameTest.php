<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Feature\Settings;

use App\Team;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTeamNameTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function update_a_team_name()
    {
        $user = factory(User::class)->create();
        $team = factory(Team::class)->create([
            'name' => 'My Team',
            'slug' => 'my-team',
        ]);
        $team->users()->attach($user);

        $this->actingAs($user);

        $response = $this->patchJson("/settings/teams/{$team->id}/name", [
            'name' => 'Changed Team',
        ]);

        $response->assertStatus(200);
        $this->assertCount(1, Team::all());
        $this->assertDatabaseHas('teams', [
            'name' => 'Changed Team',
            'slug' => 'my-team',
        ]);
        $response->assertJsonStructure([
            'data' => ['id', 'name', 'slug', 'created_at'],
        ]);
        $response->assertJsonFragment([
            'name' => 'Changed Team',
            'slug' => 'my-team',
        ]);
    }

    /** @test **/
    public function drop_unauthenticated_requests()
    {
        $user = factory(User::class)->create();
        $team = factory(Team::class)->create([
            'name' => 'My Team',
            'slug' => 'my-team',
        ]);
        $team->users()->attach($user);

        $response = $this->patchJson("/settings/teams/{$team->id}/name", [
            'name' => 'Changed Team',
        ]);

        $response->assertStatus(401);
        $this->assertDatabaseHas('teams', [
            'name' => 'My Team',
            'slug' => 'my-team',
        ]);
    }

    /** @test **/
    public function cannot_update_a_team_that_doesnt_exist()
    {
        $user = factory(User::class)->create();
        $team = factory(Team::class)->create([
            'name' => 'My Team',
            'slug' => 'my-team',
        ]);
        $team->users()->attach($user);

        $this->actingAs($user);

        $response = $this->patchJson('/settings/teams/99/name', [
            'name' => 'Changed Team',
        ]);

        $response->assertStatus(404);
        $this->assertDatabaseHas('teams', [
            'name' => 'My Team',
            'slug' => 'my-team',
        ]);
    }

    /** @test **/
    public function name_is_required()
    {
        $user = factory(User::class)->create();
        $team = factory(Team::class)->create([
            'name' => 'My Team',
            'slug' => 'my-team',
        ]);
        $team->users()->attach($user);

        $this->actingAs($user);

        $response = $this->patchJson("/settings/teams/{$team->id}/name", [
            'name' => '',
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseHas('teams', [
            'name' => 'My Team',
            'slug' => 'my-team',
        ]);
    }
}
