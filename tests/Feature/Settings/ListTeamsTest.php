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

class ListTeamsTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function list_teams()
    {
        factory(Team::class)->create(['name' => 'Team A']);
        factory(Team::class)->create(['name' => 'Team B']);
        factory(Team::class)->create(['name' => 'Team C']);

        $this->actingAs(new User);

        $response = $this->getJson('/settings/teams');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                ['id', 'name', 'slug', 'created_at'],
                ['id', 'name', 'slug', 'created_at'],
                ['id', 'name', 'slug', 'created_at'],
            ],
        ]);
        $response->assertJsonFragment(['name' => 'Team A']);
        $response->assertJsonFragment(['name' => 'Team B']);
        $response->assertJsonFragment(['name' => 'Team C']);
    }

    /** @test **/
    public function unauthenticated_requests_are_dropped()
    {
        factory(Team::class, 3)->create();

        $response = $this->getJson('/settings/teams');

        $response->assertStatus(401);
    }
}
