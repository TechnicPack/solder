<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Feature;

use App\User;
use App\Client;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageClientTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_admin_can_view_the_clients_page()
    {
        $user = factory(User::class)->states('admin')->create();
        $clientB = factory(Client::class)->create(['title' => 'Test Client B']);
        $clientA = factory(Client::class)->create(['title' => 'Test Client A']);
        $clientC = factory(Client::class)->create(['title' => 'Test Client C']);

        $response = $this->actingAs($user)->get('/settings/clients');

        $response->assertStatus(200);
        $response->assertViewIs('settings.clients');
        $response->data('clients')->assertEquals([
            $clientA,
            $clientB,
            $clientC,
        ]);
    }

    /** @test */
    public function an_authorized_user_can_view_the_clients_page()
    {
        $user = factory(User::class)->create();
        $user->grantRole('manage-clients');

        $clientB = factory(Client::class)->create(['title' => 'Test Client B']);
        $clientA = factory(Client::class)->create(['title' => 'Test Client A']);
        $clientC = factory(Client::class)->create(['title' => 'Test Client C']);

        $response = $this->actingAs($user)->get('/settings/clients');

        $response->assertStatus(200);
        $response->assertViewIs('settings.clients');
        $response->data('clients')->assertEquals([
            $clientA,
            $clientB,
            $clientC,
        ]);
    }

    /** @test */
    public function an_unauthorized_user_cannot_view_the_clients_management_page()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/settings/clients');

        $response->assertStatus(403);
    }

    /** @test */
    public function a_guest_cannot_view_the_clients_management_page()
    {
        $response = $this->get('/settings/clients');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function an_admin_can_delete_a_client()
    {
        $user = factory(User::class)->states('admin')->create();
        $client = factory(Client::class)->create();
        $this->assertCount(1, Client::all());

        $response = $this->actingAs($user)->delete('/settings/clients/'.$client->id);

        $response->assertRedirect('/settings/clients');
        $this->assertCount(0, Client::all());
    }

    /** @test */
    public function an_authorized_user_can_delete_a_client()
    {
        $user = factory(User::class)->create();
        $user->grantRole('manage-clients');

        $client = factory(Client::class)->create();
        $this->assertCount(1, Client::all());

        $response = $this->actingAs($user)->delete('/settings/clients/'.$client->id);

        $response->assertRedirect('/settings/clients');
        $this->assertCount(0, Client::all());
    }

    /** @test */
    public function attempting_to_delete_an_invalid_client_returns_a_404()
    {
        $user = factory(User::class)->states('admin')->create();
        $client = factory(Client::class)->create();
        $this->assertCount(1, Client::all());

        $response = $this->actingAs($user)->delete('/profile/clients/99');

        $response->assertStatus(404);
        $this->assertCount(1, Client::all());
    }

    /** @test */
    public function an_unauthorized_user_cannot_delete_a_client()
    {
        $user = factory(User::class)->create();
        $client = factory(Client::class)->create();
        $this->assertCount(1, Client::all());

        $response = $this->actingAs($user)->delete('/settings/clients/'.$client->id);

        $response->assertStatus(403);
        $this->assertCount(1, Client::all());
    }

    /** @test */
    public function a_guest_cannot_delete_a_client()
    {
        $client = factory(Client::class)->create();
        $this->assertCount(1, Client::all());

        $response = $this->delete('/settings/clients/'.$client->id);

        $response->assertRedirect('/login');
        $this->assertCount(1, Client::all());
    }
}
