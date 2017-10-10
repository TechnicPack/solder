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

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_delete_another_user()
    {
        $userA = factory(User::class)->create();
        $userB = factory(User::class)->create();
        $this->assertCount(2, User::all());

        $response = $this->actingAs($userA)->delete('/settings/users/'.$userB->id);

        $response->assertRedirect('/settings/users');
        $this->assertCount(1, User::all());
    }

    /** @test */
    public function a_user_cannot_delete_themselves()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->delete('/settings/users/'.$user->id);

        $response->assertStatus(403);
        $this->assertCount(1, User::all());
    }

    /** @test */
    public function a_guest_cannot_delete_a_user()
    {
        $user = factory(User::class)->create();

        $response = $this->delete('/settings/users/'.$user->id);

        $response->assertRedirect('/login');
        $this->assertCount(1, User::all());
    }

    /** @test */
    public function attempting_to_delete_non_existent_user_returns_404()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->delete('/settings/users/99');

        $response->assertStatus(404);
        $this->assertCount(1, User::all());
    }
}
