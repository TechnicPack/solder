<?php

/*
 * This file is part of TechnicPack Solder.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Feature\Settings;

use App\Team;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteTeamsTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function delete_a_team()
    {
        $team = factory(Team::class)->create();

        $this->actingAs(factory(User::class)->create());
        $this->assertCount(1, Team::all());

        $response = $this->deleteJson("/settings/teams/{$team->id}");

        $response->assertStatus(204);
        $this->assertCount(0, Team::all());
    }

    /** @test **/
    public function unauthenticated_requests_are_dropped()
    {
        $team = factory(Team::class)->create();

        $this->assertCount(1, Team::all());

        $response = $this->deleteJson("/settings/teams/{$team->id}");

        $response->assertStatus(401);
        $this->assertCount(1, Team::all());
    }

    /** @test **/
    public function invalid_requests_are_dropped()
    {
        factory(Team::class)->create();

        $this->actingAs(factory(User::class)->create());
        $this->assertCount(1, Team::all());

        $response = $this->deleteJson('/settings/teams/tokens/99');

        $response->assertStatus(404);
        $this->assertCount(1, Team::all());
    }
}
