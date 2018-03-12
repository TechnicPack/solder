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
use Platform\Key;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddKeyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_admin_can_create_a_key()
    {
        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)->post('/settings/keys', [
            'token' => 'my-technic-key',
            'name' => 'Technicpack Website',
        ]);

        $response->assertRedirect('/settings/keys');
        $this->assertCount(1, Key::all());
        tap(Key::first(), function ($key) use ($response) {
            $this->assertEquals('my-technic-key', $key->token);
            $this->assertEquals('Technicpack Website', $key->name);
        });
    }

    /** @test */
    public function an_authorized_user_can_create_a_key()
    {
        $user = factory(User::class)->create();
        $user->grantRole('manage-keys');

        $response = $this->actingAs($user)->post('/settings/keys', [
            'token' => 'my-technic-key',
            'name' => 'Technicpack Website',
        ]);

        tap(Key::first(), function ($key) use ($response) {
            $this->assertEquals('my-technic-key', $key->token);
            $this->assertEquals('Technicpack Website', $key->name);

            $response->assertRedirect('/settings/keys');
        });
    }

    /** @test */
    public function an_unauthorized_user_cannot_create_a_key()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post('/settings/keys', [
            'token' => 'my-technic-key',
            'name' => 'Technicpack Website',
        ]);

        $response->assertStatus(403);
        $this->assertCount(0, Key::all());
    }

    /** @test */
    public function a_guest_cannot_create_a_key()
    {
        $response = $this->post('/settings/keys', [
            'token' => 'my-technic-key',
            'name' => 'Technicpack Website',
        ]);

        $response->assertRedirect('/login');
        $this->assertCount(0, Key::all());
    }
}
