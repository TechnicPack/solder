<?php

/*
 * This file is part of TechnicPack Solder.
 *
 * (c) Syndicate LLC
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
            'is_admin' => 'off',
        ], $overrides);
    }

    /** @test */
    public function an_admin_can_add_another_admin()
    {
        $user = factory(User::class)->states('admin')->create(['created_at' => Carbon::parse('-1 day')]);
        $this->assertTrue($user->is_admin);

        $response = $this->actingAs($user)->post('/settings/users', [
            'username' => 'John',
            'email' => 'john@example.com',
            'password' => 'super-secret-password',
            'is_admin' => 'on',
        ]);

        $this->assertCount(2, User::all());
        tap(User::latest()->first(), function ($user) use ($response) {
            $response->assertRedirect('/settings/users');
            $this->assertEquals('John', $user->username);
            $this->assertEquals('john@example.com', $user->email);
            $this->assertTrue($user->is_admin);
            $this->assertTrue(Hash::check('super-secret-password', $user->password));
        });
    }

    /** @test */
    public function a_user_with_the_manage_users_permission_can_add_another_user()
    {
        $user = factory(User::class)->create(['created_at' => Carbon::parse('-1 day')]);
        $user->grantRole('manage-users');

        $response = $this->actingAs($user)->post('/settings/users', $this->validParams());

        $this->assertCount(2, User::all());
        tap(User::latest()->first(), function ($user) use ($response) {
            $response->assertRedirect('/settings/users');
            $this->assertEquals('John', $user->username);
            $this->assertEquals('john@example.com', $user->email);
            $this->assertFalse($user->is_admin);
            $this->assertTrue(Hash::check('super-secret-password', $user->password));
        });
    }

    /** @test */
    public function a_user_with_the_manage_users_permission_can_not_create_an_admin()
    {
        $user = factory(User::class)->create();
        $user->grantRole('manage-users');
        $this->assertFalse($user->fresh()->is_admin);

        $response = $this->actingAs($user)->post('/settings/users', $this->validParams([
            'is_admin' => 'on',
        ]));

        $response->assertStatus(403);
        $this->assertCount(1, User::all());
    }

    /** @test */
    public function user_without_manage_users_permission_cannot_create_a_user()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post('/settings/users', $this->validParams());

        $response->assertStatus(403);
        $this->assertCount(1, User::all());
    }

    /** @test */
    public function a_guest_cannot_add_a_user()
    {
        $response = $this->post('/settings/users', $this->validParams());

        $response->assertRedirect('/login');
        $this->assertCount(0, User::all());
    }

    /** @test */
    public function username_is_required()
    {
        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)->from('/settings/users')->post('/settings/users', $this->validParams([
            'username' => '',
        ]));

        $response->assertRedirect('/settings/users');
        $response->assertSessionHasErrors('username');
    }

    /** @test */
    public function username_is_unique()
    {
        $user = factory(User::class)->states('admin')->create([
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
        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)->from('/settings/users')->post('/settings/users', $this->validParams([
            'email' => '',
        ]));

        $response->assertRedirect('/settings/users');
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function email_is_unique()
    {
        $user = factory(User::class)->states('admin')->create([
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
        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)->from('/settings/users')->post('/settings/users', $this->validParams([
            'email' => 'not-an-email',
        ]));

        $response->assertRedirect('/settings/users');
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function password_is_required()
    {
        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)->from('/settings/users')->post('/settings/users', $this->validParams([
            'password' => '',
        ]));

        $response->assertRedirect('/settings/users');
        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function password_is_at_least_6_chars_long()
    {
        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)->from('/settings/users')->post('/settings/users', $this->validParams([
            'password' => 'abcde',
        ]));

        $response->assertRedirect('/settings/users');
        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function if_is_admin_is_missing_its_unchecked()
    {
        $user = factory(User::class)->states('admin')->create(['created_at' => Carbon::parse('-1 day')]);

        $response = $this->actingAs($user)->post('/settings/users', [
            'username' => 'John',
            'email' => 'john@example.com',
            'password' => 'super-secret-password',
        ]);

        tap(User::orderBy('created_at', 'desc')->first(), function ($user) use ($response) {
            $response->assertRedirect('/settings/users');
            $this->assertEquals('John', $user->username);
            $this->assertEquals('john@example.com', $user->email);
            $this->assertFalse($user->is_admin);
            $this->assertTrue(Hash::check('super-secret-password', $user->password));
        });
    }
}
