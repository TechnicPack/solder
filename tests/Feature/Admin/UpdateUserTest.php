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
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_update_a_user()
    {
        $user = factory(User::class)->create([
            'username' => 'John',
            'email' => 'john@example.com',
            'password' => bcrypt('super-secret-password'),
        ]);

        $response = $this->actingAs($user)->from('/settings/users')->post('/settings/users/'.$user->id, [
            'username' => 'Jane',
            'email' => 'jane@example.com',
            'password' => 'updated-password',
        ]);

        tap(User::first(), function ($user) use ($response) {
            $response->assertRedirect('/settings/users');
            $response->assertSessionMissing('errors');

            $this->assertEquals('Jane', $user->username);
            $this->assertEquals('jane@example.com', $user->email);
            $this->assertTrue(Hash::check('updated-password', $user->password));
        });
    }

    /** @test */
    public function a_guest_cannot_edit_a_user()
    {
        $user = factory(User::class)->create([
            'username' => 'John',
            'email' => 'john@example.com',
            'password' => bcrypt('super-secret-password'),
        ]);

        $response = $this->post('/settings/users/'.$user->id, $this->validParams());

        tap(User::first(), function ($user) use ($response) {
            $response->assertRedirect('/login');

            $this->assertEquals('John', $user->username);
            $this->assertEquals('john@example.com', $user->email);
            $this->assertTrue(Hash::check('super-secret-password', $user->password));
        });
    }

    /** @test */
    public function username_is_required()
    {
        $user = factory(User::class)->create($this->originalParams());

        $response = $this->actingAs($user)->from('/settings/users')->post('/settings/users/'.$user->id, $this->validParams([
            'username' => '',
        ]));

        $response->assertRedirect('/settings/users');
        $response->assertSessionHasErrors('username');
    }

    /** @test */
    public function username_is_unique()
    {
        $otherUser = factory(User::class)->create(['username' => 'Jane']);
        $user = factory(User::class)->create($this->originalParams());

        $response = $this->actingAs($user)->from('/settings/users')->post('/settings/users/'.$user->id, $this->validParams([
            'username' => 'Jane',
        ]));

        $response->assertRedirect('/settings/users');
        $response->assertSessionHasErrors('username');
    }

    /** @test */
    public function allow_resetting_same_username()
    {
        $user = factory(User::class)->create($this->originalParams([
            'username' => 'Jane',
        ]));

        $response = $this->actingAs($user)->from('/settings/users')->post('/settings/users/'.$user->id, $this->validParams([
            'username' => 'Jane',
        ]));

        $response->assertRedirect('/settings/users');
        $response->assertSessionMissing('errors');
    }

    /** @test */
    public function email_is_required()
    {
        $user = factory(User::class)->create($this->originalParams());

        $response = $this->actingAs($user)->from('/settings/users')->post('/settings/users/'.$user->id, $this->validParams([
            'email' => '',
        ]));

        $response->assertRedirect('/settings/users');
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function email_is_unique()
    {
        $otherUser = factory(User::class)->create(['email' => 'jane@example.com']);
        $user = factory(User::class)->create($this->originalParams());

        $response = $this->actingAs($user)->from('/settings/users')->post('/settings/users/'.$user->id, $this->validParams([
            'email' => 'jane@example.com',
        ]));

        $response->assertRedirect('/settings/users');
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function allow_resetting_same_email()
    {
        $user = factory(User::class)->create($this->originalParams([
            'email' => 'jane@example.com',
        ]));

        $response = $this->actingAs($user)->from('/settings/users')->post('/settings/users/'.$user->id, $this->validParams([
            'email' => 'jane@example.com',
        ]));

        $response->assertRedirect('/settings/users');
        $response->assertSessionMissing('errors');
    }

    /** @test */
    public function email_is_valid_format()
    {
        $user = factory(User::class)->create($this->originalParams());

        $response = $this->actingAs($user)->from('/settings/users')->post('/settings/users/'.$user->id, $this->validParams([
            'email' => 'not-an-email',
        ]));

        $response->assertRedirect('/settings/users');
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function password_is_optional()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create($this->originalParams([
            'password' => bcrypt('original-password'),
        ]));

        $response = $this->actingAs($user)->from('/settings/users')->post('/settings/users/'.$user->id, $this->validParams([
            'password' => '',
        ]));

        $response->assertRedirect('/settings/users');
        $response->assertSessionMissing('errors');
        $this->assertTrue(Hash::check('original-password', $user->fresh()->password));
    }

    /** @test */
    public function password_is_at_least_6_chars_long()
    {
        $user = factory(User::class)->create($this->originalParams());

        $response = $this->actingAs($user)->from('/settings/users')->post('/settings/users/'.$user->id, $this->validParams([
            'password' => 'abcde',
        ]));

        $response->assertRedirect('/settings/users');
        $response->assertSessionHasErrors('password');
    }

    /**
     * @param array $overrides
     *
     * @return array
     */
    private function originalParams($overrides = [])
    {
        return array_merge([
            'username' => 'John',
            'email' => 'john@example.com',
            'password' => bcrypt('super-secret-password'),
        ], $overrides);
    }

    /**
     * @param array $overrides
     *
     * @return array
     */
    private function validParams($overrides = [])
    {
        return array_merge([
            'username' => 'Jane',
            'email' => 'jane@example.com',
            'password' => 'updated-password',
        ], $overrides);
    }
}
