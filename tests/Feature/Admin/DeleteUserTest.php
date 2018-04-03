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
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_admin_can_delete_another_user()
    {
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();
        $this->assertCount(2, User::all());

        $response = $this->actingAs($admin)->delete('/settings/users/'.$user->id);

        $response->assertRedirect('/settings/users');
        $this->assertCount(1, User::all());
    }

    /** @test */
    public function a_user_with_manage_users_permissions_can_delete_another_user()
    {
        $authorizedUser = factory(User::class)->create();
        $authorizedUser->grantRole('manage-users');
        $user = factory(User::class)->create();
        $this->assertCount(2, User::all());

        $response = $this->actingAs($authorizedUser)->delete('/settings/users/'.$user->id);

        $response->assertRedirect('/settings/users');
        $this->assertCount(1, User::all());
    }

    /** @test */
    public function a_user_without_manage_users_permissions_can_not_delete_another_user()
    {
        $userA = factory(User::class)->create();
        $userB = factory(User::class)->create();
        $this->assertCount(2, User::all());

        $response = $this->actingAs($userA)->delete('/settings/users/'.$userB->id);

        $response->assertStatus(403);
        $this->assertCount(2, User::all());
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
    public function a_admin_cannot_delete_themselves()
    {
        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)->delete('/settings/users/'.$user->id);

        $response->assertStatus(403);
        $this->assertCount(1, User::all());
    }

    /** @test */
    public function a_authorized_user_cannot_delete_an_admin()
    {
        $authorizedUser = factory(User::class)->create();
        $authorizedUser->grantRole('manage-users');
        $admin = factory(User::class)->states('admin')->create();
        $this->assertCount(2, User::all());

        $response = $this->actingAs($authorizedUser)->delete('/settings/users/'.$admin->id);

        $response->assertStatus(403);
        $this->assertCount(2, User::all());
    }

    /** @test */
    public function an_admin_can_delete_an_admin()
    {
        $adminA = factory(User::class)->states('admin')->create();
        $adminA->grantRole('manage-users');
        $adminB = factory(User::class)->states('admin')->create();
        $this->assertCount(2, User::all());

        $response = $this->actingAs($adminA)->delete('/settings/users/'.$adminB->id);

        $response->assertRedirect('/settings/users');
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
