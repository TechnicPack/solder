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
    public function a_user_can_create_a_client_token()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post('/profile/clients', [
            'title' => 'Macbook',
            'token' => 'my-launcher-client-id',
        ]);

        tap(Client::first(), function ($client) use ($response, $user) {
            $this->assertEquals('Macbook', $client->title);
            $this->assertEquals('my-launcher-client-id', $client->token);
            $this->assertEquals($user->id, $client->user->id);

            $response->assertRedirect('/profile/clients');
        });
    }

    /** @test */
    public function a_guest_cannot_create_a_client_token()
    {
        $response = $this->post('/profile/clients', [
            'title' => 'Macbook',
            'token' => 'my-launcher-client-id',
        ]);

        $response->assertRedirect('/login');
        $this->assertCount(0, Client::all());
    }

    /** @test */
    public function title_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/profile/clients')->post('/profile/clients', $this->validParams([
            'title' => '',
        ]));

        $response->assertRedirect('/profile/clients');
        $response->assertSessionHasErrors('title');
        $this->assertEquals(0, Client::count());
    }

    /** @test */
    public function token_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/profile/clients')->post('/profile/clients', $this->validParams([
            'token' => '',
        ]));

        $response->assertRedirect('/profile/clients');
        $response->assertSessionHasErrors('token');
        $this->assertEquals(0, Client::count());
    }

    /** @test */
    public function token_is_unique()
    {
        $user = factory(User::class)->create();
        $client = factory(Client::class)->create(['token' => 'some-existing-token']);

        $response = $this->actingAs($user)->from('/profile/clients')->post('/profile/clients', $this->validParams([
            'token' => 'some-existing-token',
        ]));

        $response->assertRedirect('/profile/clients');
        $response->assertSessionHasErrors('token');
        $this->assertEquals(1, Client::count());
    }
}
