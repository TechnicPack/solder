<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AuthenticationTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_register_an_account()
    {
        $this->browse(function ($browser) {
            $browser->visit('/register')
                ->type('name', 'Kyle Klaus')
                ->type('email', 'indemnity83@gmail.com')
                ->type('password', 'secret')
                ->type('password_confirmation', 'secret')
                ->press('Register')
                ->assertPathIs('/dashboard')
                ->clickLink('Logout');
        });
    }

    /** @test */
    public function a_user_can_login()
    {
        $user = factory(User::class)->create([
            'email' => 'indemnity83@gmail.com',
        ]);

        $this->browse(function ($browser) use ($user) {
            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', 'secret')
                ->press('Login')
                ->assertPathIs('/dashboard')
                ->clickLink('Logout');
        });
    }

    /** @test */
    public function a_user_can_request_a_password_reset_email()
    {
        $user = factory(User::class)->create([
            'email' => 'indemnity83@gmail.com',
        ]);

        $this->browse(function ($browser) use ($user) {
            $browser->visit('/password/reset')
                ->type('email', $user->email)
                ->press('Send Password Reset Link')
                ->assertSee('We have e-mailed your password reset link!');
        });
    }

    /** @test */
    public function a_user_can_change_their_password_using_a_reset_token()
    {
        $user = factory(User::class)->create([
            'email' => 'indemnity83@gmail.com',
        ]);
        $token = resolve('auth.password')->broker()->getRepository()->create($user);

        $this->browse(function ($browser) use ($user, $token) {
            $browser->visit('/password/reset/' . $token)
                ->type('email', $user->email)
                ->type('password', 'newsecret')
                ->type('password_confirmation', 'newsecret')
                ->press('Reset Password')
                ->assertSee('Your password has been reset!');
        });
    }
}
