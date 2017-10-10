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
use Illuminate\Foundation\Testing\RefreshDatabase;

class BrowseUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_view_the_users_list()
    {
        $userA = factory(User::class)->create(['username' => 'User A']);
        $userC = factory(User::class)->create(['username' => 'User C']);
        $userB = factory(User::class)->create(['username' => 'User B']);

        $response = $this->actingAs($userA)->get('/settings/users');

        $response->assertStatus(200);
        $response->assertViewIs('settings.users');
        $response->data('users')->assertEquals([
            $userA,
            $userB,
            $userC,
        ]);
    }

    /** @test */
    public function a_guest_cannot_view_the_users_list()
    {
        $userA = factory(User::class)->create(['username' => 'User A']);
        $userC = factory(User::class)->create(['username' => 'User C']);
        $userB = factory(User::class)->create(['username' => 'User B']);

        $response = $this->get('/settings/users');

        $response->assertRedirect('/login');
    }
}
