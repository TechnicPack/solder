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

use App\User;
use Tests\TestCase;
use Platform\Client;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListClientsTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function list_clients()
    {
        $this->withoutAuthorization();
        $user = factory(User::class)->create();
        factory(Client::class)->create(['title' => 'Client A']);
        factory(Client::class)->create(['title' => 'Client B']);
        factory(Client::class)->create(['title' => 'Client C']);

        $response = $this->actingAs($user)
            ->getJson('/settings/clients/tokens');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                ['id', 'title', 'token', 'created_at'],
                ['id', 'title', 'token', 'created_at'],
                ['id', 'title', 'token', 'created_at'],
            ],
        ]);
        $response->assertJsonFragment(['title' => 'Client A']);
        $response->assertJsonFragment(['title' => 'Client B']);
        $response->assertJsonFragment(['title' => 'Client C']);
    }

    /** @test **/
    public function unauthenticated_requests_are_dropped()
    {
        $this->withoutAuthorization();
        factory(Client::class, 3)->create();

        $response = $this->getJson('/settings/clients/tokens');

        $response->assertStatus(401);
    }

    /** @test **/
    public function unauthorized_requests_are_forbidden()
    {
        Gate::define('clients.list', function () {
            return false;
        });
        $user = factory(User::class)->create();
        factory(Client::class, 3)->create();

        $response = $this->actingAs($user)
            ->getJson('/settings/clients/tokens');

        $response->assertStatus(403);
    }

    /**
     * Authorize all actions, effectively disabling authorization checks.
     */
    protected function withoutAuthorization()
    {
        Gate::define('clients.list', function () {
            return true;
        });
    }
}
