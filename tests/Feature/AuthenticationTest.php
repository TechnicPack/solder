<?php

/*
 * This file is part of solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    /** @test */
    public function a_user_can_register_an_account()
    {
        $response = $this->post('/register', [
            'name' => 'indemnity83',
            'email' => 'indemnity83@gmail.com',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', [
            'name' => 'indemnity83',
            'email' => 'indemnity83@gmail.com',
        ]);
    }

    /** @test */
    public function a_user_can_login()
    {
        $user = factory(User::class)->create([
            'email' => 'indemnity83@gmail.com',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret',
        ]);

        $response->assertRedirect('/dashboard');
    }

    /** @test */
    public function a_user_can_request_a_password_reset_email()
    {
        $user = factory(User::class)->create([
            'email' => 'indemnity83@gmail.com',
        ]);

        $response = $this->post('/password/email', [
            'email' => $user->email,
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('status', 'We have e-mailed your password reset link!');
    }

    /** @test */
    public function a_user_can_change_their_password_using_a_reset_token()
    {
        $user = factory(User::class)->create([
            'email' => 'indemnity83@gmail.com',
        ]);
        $token = resolve('auth.password')->broker()->getRepository()->create($user);

        $response = $this->post('/password/reset', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'newsecret',
            'password_confirmation' => 'newsecret',
        ]);

        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('status', 'Your password has been reset!');
    }
}
