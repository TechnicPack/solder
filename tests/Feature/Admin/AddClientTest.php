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
use Tests\TestCase;
use Platform\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddClientTest extends TestCase
{
    use RefreshDatabase;

    private function validParams($overrides = [])
    {
        return array_merge([
            'title' => 'Macbook',
            'token' => 'my-launcher-client-id',
        ], $overrides);
    }

    /** @test */
    public function an_admin_can_create_a_client_token()
    {
        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)->post('/settings/clients', [
            'title' => 'Macbook',
            'token' => 'my-launcher-client-id',
        ]);

        tap(Client::first(), function ($client) use ($response, $user) {
            $this->assertEquals('Macbook', $client->title);
            $this->assertEquals('my-launcher-client-id', $client->token);

            $response->assertRedirect('/settings/clients');
        });
    }

    /** @test */
    public function an_authorized_user_can_create_a_client_token()
    {
        $user = factory(User::class)->create();
        $user->grantRole('manage-clients');

        $response = $this->actingAs($user)->post('/settings/clients', [
            'title' => 'Macbook',
            'token' => 'my-launcher-client-id',
        ]);

        tap(Client::first(), function ($client) use ($response, $user) {
            $this->assertEquals('Macbook', $client->title);
            $this->assertEquals('my-launcher-client-id', $client->token);

            $response->assertRedirect('/settings/clients');
        });
    }

    /** @test */
    public function an_unauthorized_user_cannot_create_a_client_token()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post('/settings/clients', [
            'title' => 'Macbook',
            'token' => 'my-launcher-client-id',
        ]);

        $response->assertStatus(403);
        $this->assertCount(0, Client::all());
    }

    /** @test */
    public function a_guest_cannot_create_a_client_token()
    {
        $response = $this->post('/settings/clients', [
            'title' => 'Macbook',
            'token' => 'my-launcher-client-id',
        ]);

        $response->assertRedirect('/login');
        $this->assertCount(0, Client::all());
    }

    /** @test */
    public function title_is_required()
    {
        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)->from('/settings/clients')->post('/settings/clients', $this->validParams([
            'title' => '',
        ]));

        $response->assertRedirect('/settings/clients');
        $response->assertSessionHasErrors('title');
        $this->assertEquals(0, Client::count());
    }

    /** @test */
    public function token_is_required()
    {
        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)->from('/settings/clients')->post('/settings/clients', $this->validParams([
            'token' => '',
        ]));

        $response->assertRedirect('/settings/clients');
        $response->assertSessionHasErrors('token');
        $this->assertEquals(0, Client::count());
    }

    /** @test */
    public function token_is_unique()
    {
        $user = factory(User::class)->states('admin')->create();
        $client = factory(Client::class)->create(['token' => 'some-existing-token']);

        $response = $this->actingAs($user)->from('/settings/clients')->post('/settings/clients', $this->validParams([
            'token' => 'some-existing-token',
        ]));

        $response->assertRedirect('/settings/clients');
        $response->assertSessionHasErrors('token');
        $this->assertEquals(1, Client::count());
    }
}
