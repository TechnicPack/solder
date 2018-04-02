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

class CreateTeamsTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function create_a_team()
    {
        $this->actingAs(new User);

        $response = $this->postJson('/settings/teams', [
            'name' => 'My Team',
            'slug' => 'my-team',
        ]);

        $response->assertStatus(201);
        $this->assertCount(1, Team::all());
        $this->assertDatabaseHas('teams', [
            'name' => 'My Team',
            'slug' => 'my-team',
        ]);
        $response->assertJsonStructure([
            'data' => ['id', 'name', 'slug', 'created_at'],
        ]);
        $response->assertJsonFragment([
            'name' => 'My Team',
            'slug' => 'my-team',
        ]);
    }

    /** @test **/
    public function unauthenticated_requests_are_dropped()
    {
        $response = $this->postJson('/settings/teams', [
            'name' => 'My Team',
            'slug' => 'my-team',
        ]);

        $response->assertStatus(401);
        $this->assertCount(0, Team::all());
    }

    /** @test **/
    public function name_is_required()
    {
        $this->actingAs(new User);

        $response = $this->postJson('/settings/teams', [
            'name' => '',
            'slug' => 'my-team',
        ]);

        $response->assertStatus(422);
        $this->assertCount(0, Team::all());
    }

    /** @test **/
    public function slug_is_required()
    {
        $this->actingAs(new User);

        $response = $this->postJson('/settings/teams', [
            'name' => 'My Team',
            'slug' => '',
        ]);

        $response->assertStatus(422);
        $this->assertCount(0, Team::all());
    }

    /** @test **/
    public function slug_is_unique()
    {
        factory(Team::class)->create(['slug' => 'existing-slug']);
        $this->actingAs(new User);

        $response = $this->postJson('/settings/teams', [
            'name' => 'My Team',
            'slug' => 'existing-slug',
        ]);

        $response->assertStatus(422);
        $this->assertCount(1, Team::all());
    }
}
