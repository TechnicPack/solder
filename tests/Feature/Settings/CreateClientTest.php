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

class CreateClientTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function create_a_client()
    {
        $this->withoutAuthorization();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->postJson('/settings/clients/tokens', [
            'title' => 'My Client',
            'token' => 'my-client-token',
        ]);

        $response->assertStatus(201);
        $this->assertCount(1, Client::all());
        $this->assertDatabaseHas('clients', [
            'title' => 'My Client',
            'token' => 'my-client-token',
        ]);
        $response->assertJsonStructure([
            'data' => ['id', 'title', 'token', 'created_at'],
        ]);
        $response->assertJsonFragment([
            'title' => 'My Client',
            'token' => 'my-client-token',
        ]);
    }

    /** @test **/
    public function unauthenticated_requests_are_dropped()
    {
        $this->withoutAuthorization();

        $response = $this->postJson('/settings/clients/tokens', [
            'title' => 'My Client',
            'token' => 'my-client-token',
        ]);

        $response->assertStatus(401);
        $this->assertCount(0, Client::all());
    }

    /** @test **/
    public function unauthorized_requests_are_forbidden()
    {
        Gate::define('clients.create',function () {
            return false;
        });
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->postJson('/settings/clients/tokens', [
            'title' => 'My Client',
            'token' => 'my-client-token',
        ]);

        $response->assertStatus(403);
        $this->assertCount(0, Client::all());
    }

    /** @test **/
    public function title_is_required()
    {
        $this->withoutAuthorization();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->postJson('/settings/clients/tokens', [
            'title' => '',
            'token' => 'my-client-token',
        ]);

        $response->assertStatus(422);
        $this->assertCount(0, Client::all());
    }

    /** @test **/
    public function title_is_unique()
    {
        $this->withoutAuthorization();
        $user = factory(User::class)->create();
        factory(Client::class)->create([
            'title' => 'My Client',
        ]);

        $response = $this->actingAs($user)->postJson('/settings/clients/tokens', [
            'title' => 'My Client',
            'token' => 'my-client-token',
        ]);

        $response->assertStatus(422);
        $this->assertCount(1, Client::all());
    }

    /** @test **/
    public function token_is_required()
    {
        $this->withoutAuthorization();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->postJson('/settings/clients/tokens', [
            'title' => 'My Client',
            'token' => '',
        ]);

        $response->assertStatus(422);
        $this->assertCount(0, Client::all());
    }

    /** @test **/
    public function token_is_unique()
    {
        $this->withoutAuthorization();
        $user = factory(User::class)->create();
        factory(Client::class)->create([
            'token' => 'my-client-token',
        ]);

        $response = $this->actingAs($user)->postJson('/settings/clients/tokens', [
            'title' => 'My Client',
            'token' => 'my-client-token',
        ]);

        $response->assertStatus(422);
        $this->assertCount(1, Client::all());
    }

    /**
     * Authorize all actions, effectively disabling authorization checks.
     */
    protected function withoutAuthorization(): void
    {
        Gate::define('clients.create',function () {
            return true;
        });
    }
}
