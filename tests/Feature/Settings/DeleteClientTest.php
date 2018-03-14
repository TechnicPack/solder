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

class DeleteClientTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function delete_a_client()
    {
        $this->withoutAuthorization();
        $user = factory(User::class)->create();
        $client = factory(Client::class)->create();
        $this->assertCount(1, Client::all());

        $response = $this->actingAs($user)
            ->deleteJson("/settings/clients/tokens/{$client->id}");

        $response->assertStatus(204);
        $this->assertCount(0, Client::all());
    }

    /** @test **/
    public function unauthenticated_requests_are_dropped()
    {
        $this->withoutAuthorization();
        $client = factory(Client::class)->create();
        $this->assertCount(1, Client::all());

        $response = $this
            ->deleteJson("/settings/clients/tokens/{$client->id}");

        $response->assertStatus(401);
        $this->assertCount(1, Client::all());
    }

    /** @test **/
    public function invalid_requests_are_dropped()
    {
        $this->withoutAuthorization();
        $user = factory(User::class)->create();
        factory(Client::class)->create();
        $this->assertCount(1, Client::all());

        $response = $this->actingAs($user)
            ->deleteJson('/settings/clients/tokens/99');

        $response->assertStatus(404);
        $this->assertCount(1, Client::all());
    }

    /** @test **/
    public function unauthorized_requests_are_forbidden()
    {
        Gate::define('clients.delete', function () {
            return false;
        });
        $user = factory(User::class)->create();
        $client = factory(Client::class)->create();
        $this->assertCount(1, Client::all());

        $response = $this->actingAs($user)
            ->deleteJson("/settings/clients/tokens/{$client->id}");

        $response->assertStatus(403);
        $this->assertCount(1, Client::all());
    }

    /**
     * Authorize all actions, effectively disabling authorization checks.
     */
    protected function withoutAuthorization()
    {
        Gate::define('clients.delete', function () {
            return true;
        });
    }
}
