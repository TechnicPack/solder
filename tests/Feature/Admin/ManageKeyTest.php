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

use App\Key;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageKeyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_admin_can_view_the_key_management_page()
    {
        $user = factory(User::class)->states('admin')->create();
        $keyB = factory(Key::class)->create(['name' => 'Test Key B']);
        $keyA = factory(Key::class)->create(['name' => 'Test Key A']);
        $keyC = factory(Key::class)->create(['name' => 'Test Key C']);

        $response = $this->actingAs($user)->get('/settings/keys');

        $response->assertStatus(200);
        $response->assertViewIs('settings.keys');
        $response->data('keys')->assertEquals([
            $keyA,
            $keyB,
            $keyC,
        ]);
    }

    /** @test */
    public function an_authorized_user_can_view_key_management()
    {
        $user = factory(User::class)->states('admin')->create();
        $user->grantRole('manage-keys');

        $response = $this->actingAs($user)->get('/settings/keys');

        $response->assertStatus(200);
        $response->assertViewIs('settings.keys');
    }

    /** @test */
    public function an_unauthorized_user_cannot_view_key_management()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/settings/keys');

        $response->assertStatus(403);
    }

    /** @test */
    public function a_guest_cannot_view_the_key_management_page()
    {
        $response = $this->get('/settings/keys');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function a_user_can_delete_a_key()
    {
        $user = factory(User::class)->create();
        $key = factory(Key::class)->create();
        $this->assertCount(1, Key::all());

        $response = $this->actingAs($user)->delete('/settings/keys/'.$key->id);

        $response->assertRedirect('/settings/keys');
        $this->assertCount(0, Key::all());
    }

    /** @test */
    public function a_guest_cannot_delete_a_key()
    {
        $key = factory(Key::class)->create();
        $this->assertCount(1, Key::all());

        $response = $this->delete('/settings/keys/'.$key->id);

        $response->assertRedirect('/login');
        $this->assertCount(1, Key::all());
    }

    /** @test */
    public function a_user_cannot_delete_a_nonexistant_key()
    {
        $user = factory(User::class)->create();
        factory(Key::class)->create();
        $this->assertCount(1, Key::all());

        $response = $this->actingAs($user)->delete('/settings/keys/99');

        $response->assertStatus(404);
        $this->assertCount(1, Key::all());
    }
}
