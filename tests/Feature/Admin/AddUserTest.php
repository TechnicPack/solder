<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Feature\Admin;

use App\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddUserTest extends TestCase
{
    use RefreshDatabase;

    private function validParams($overrides = [])
    {
        return array_merge([
            'username' => 'John',
            'email' => 'john@example.com',
            'password' => 'super-secret-password',
        ], $overrides);
    }

    /** @test */
    public function a_user_can_add_another_user()
    {
        $user = factory(User::class)->create(['created_at' => Carbon::parse('-1 day')]);

        $response = $this->actingAs($user)->post('/settings/users', [
           'username' => 'John',
           'email' => 'john@example.com',
           'password' => 'super-secret-password',
        ]);

        tap(User::orderBy('created_at', 'desc')->first(), function ($user) use ($response) {
            $response->assertRedirect('/settings/users');
            $this->assertEquals('John', $user->username);
            $this->assertEquals('john@example.com', $user->email);
            $this->assertTrue(Hash::check('super-secret-password', $user->password));
        });
    }

    /** @test */
    public function a_guest_cannot_add_a_user()
    {
        $response = $this->post('/settings/users', [
            'username' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'super-secret-password',
        ]);

        $response->assertRedirect('/login');
        $this->assertCount(0, User::all());
    }

    /** @test */
    public function username_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/settings/users')->post('/settings/users', $this->validParams([
            'username' => '',
        ]));

        $response->assertRedirect('/settings/users');
        $response->assertSessionHasErrors('username');
    }

    /** @test */
    public function username_is_unique()
    {
        $user = factory(User::class)->create([
            'username' => 'Jane',
        ]);

        $response = $this->actingAs($user)->from('/settings/users')->post('/settings/users', $this->validParams([
            'username' => 'Jane',
        ]));

        $response->assertRedirect('/settings/users');
        $response->assertSessionHasErrors('username');
    }

    /** @test */
    public function email_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/settings/users')->post('/settings/users', $this->validParams([
            'email' => '',
        ]));

        $response->assertRedirect('/settings/users');
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function email_is_unique()
    {
        $user = factory(User::class)->create([
            'email' => 'jane@example.com',
        ]);

        $response = $this->actingAs($user)->from('/settings/users')->post('/settings/users', $this->validParams([
            'email' => 'jane@example.com',
        ]));

        $response->assertRedirect('/settings/users');
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function email_is_valid_format()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/settings/users')->post('/settings/users', $this->validParams([
            'email' => 'not-an-email',
        ]));

        $response->assertRedirect('/settings/users');
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function password_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/settings/users')->post('/settings/users', $this->validParams([
            'password' => '',
        ]));

        $response->assertRedirect('/settings/users');
        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function password_is_at_least_6_chars_long()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/settings/users')->post('/settings/users', $this->validParams([
            'password' => 'abcde',
        ]));

        $response->assertRedirect('/settings/users');
        $response->assertSessionHasErrors('password');
    }
}
