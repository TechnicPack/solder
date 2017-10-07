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

class DeleteClientTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_view_the_clients_page()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $clientB = factory(Client::class)->create(['title' => 'Test Client B', 'user_id' => $user->id]);
        $clientA = factory(Client::class)->create(['title' => 'Test Client A', 'user_id' => $user->id]);
        $clientC = factory(Client::class)->create(['title' => 'Test Client C', 'user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/profile/clients');

        $response->assertStatus(200);
        $response->assertViewIs('profile.clients');
        $response->data('clients')->assertEquals([
            $clientA,
            $clientB,
            $clientC,
        ]);
    }

    /** @test */
    public function a_guest_cannot_view_the_clients_management_page()
    {
        $response = $this->get('/profile/clients');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function a_user_can_only_see_their_own_clients()
    {
        $this->withoutExceptionHandling();
        $otherUser = factory(User::class)->create();
        $otherUsersClient = factory(Client::class)->create(['user_id' => $otherUser->id]);
        $user = factory(User::class)->create();
        $client = factory(Client::class)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/profile/clients');

        $response->data('clients')->assertContains($client);
        $response->data('clients')->assertNotContains($otherUsersClient);
    }

    /** @test */
    public function a_user_can_delete_their_own_client()
    {
        $user = factory(User::class)->create();
        $client = factory(Client::class)->create(['user_id' => $user->id]);
        $this->assertCount(1, Client::all());

        $response = $this->actingAs($user)->delete('/profile/clients/'.$client->id);

        $response->assertRedirect('/profile/clients');
        $this->assertCount(0, Client::all());
    }

    /** @test */
    public function attempting_to_delete_an_invalid_client_returns_a_404()
    {
        $user = factory(User::class)->create();
        $client = factory(Client::class)->create(['user_id' => $user->id]);
        $this->assertCount(1, Client::all());

        $response = $this->actingAs($user)->delete('/profile/clients/99');

        $response->assertStatus(404);
        $this->assertCount(1, Client::all());
    }

    /** @test */
    public function attempting_to_delete_another_users_client_returns_a_404()
    {
        $otherUser = factory(User::class)->create();
        $otherUsersClient = factory(Client::class)->create(['user_id' => $otherUser->id]);
        $user = factory(User::class)->create();
        $this->assertCount(1, Client::all());

        $response = $this->actingAs($user)->delete('/profile/clients/'.$otherUsersClient->id);

        $response->assertStatus(404);
        $this->assertCount(1, Client::all());
    }

    /** @test */
    public function a_guest_cannot_delete_a_client()
    {
        $client = factory(Client::class)->create();
        $this->assertCount(1, Client::all());

        $response = $this->delete('/profile/clients/'.$client->id);

        $response->assertRedirect('/login');
        $this->assertCount(1, Client::all());
    }
}
