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

use App\Role;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManagePermissionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_admin_can_view_the_permissions_page()
    {
        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)->get('settings/permissions');

        $response->assertStatus(200);
        $response->assertViewIs('settings.permissions');
        $response->data('users')->assertContains($user);
        $response->data('roles')->assertEquals(Role::all());
        $this->assertTrue($response->data('users')->first()->relationLoaded('roles'));
    }

    /** @test */
    public function an_authorized_user_can_view_the_permissions_page()
    {
        $user = factory(User::class)->create();
        $user->grantRole('manage-users');

        $response = $this->actingAs($user)->get('settings/permissions');

        $response->assertStatus(200);
    }

    /** @test */
    public function a_user_cannot_view_the_permissions_page()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('settings/permissions');

        $response->assertStatus(403);
    }

    /** @test */
    public function an_admin_can_submit_new_permissions()
    {
        $userA = factory(User::class)->states('admin')->create();
        $userB = factory(User::class)->create();
        $roleA = factory(Role::class)->create(['tag' => 'role-a']);
        $roleB = factory(Role::class)->create(['tag' => 'role-b']);
        $roleC = factory(Role::class)->create(['tag' => 'role-c']);

        $response = $this->actingAs($userA)->post('settings/permissions', [
            'users' => [
                $userA->id => [$roleA->id, $roleC->id],
                $userB->id => [$roleB->id, $roleC->id],
            ],
        ]);

        $response->assertRedirect('settings/permissions');
        $this->assertTrue($userA->roles()->where('tag', 'role-a')->exists());
        $this->assertFalse($userA->roles()->where('tag', 'role-b')->exists());
        $this->assertTrue($userA->roles()->where('tag', 'role-c')->exists());
        $this->assertFalse($userB->roles()->where('tag', 'role-a')->exists());
        $this->assertTrue($userB->roles()->where('tag', 'role-b')->exists());
        $this->assertTrue($userB->roles()->where('tag', 'role-c')->exists());
    }

    /** @test */
    public function an_authorized_user_can_submit_new_permissions()
    {
        $user = factory(User::class)->create();
        $user->grantRole('manage-users');
        $roleA = factory(Role::class)->create(['tag' => 'role-a']);
        $roleB = factory(Role::class)->create(['tag' => 'role-c']);

        $response = $this->actingAs($user)->post('settings/permissions', [
            'users' => [
                $user->id => [$roleA->id, $roleB->id],
            ],
        ]);

        $response->assertRedirect('settings/permissions');
        $response->assertSessionMissing('errors');
    }

    /** @test */
    public function an_unauthorized_user_cannot_submit_new_permissions()
    {
        $user = factory(User::class)->create();
        $roleA = factory(Role::class)->create(['tag' => 'role-a']);
        $roleB = factory(Role::class)->create(['tag' => 'role-c']);

        $response = $this->actingAs($user)->post('settings/permissions', [
            'users' => [
                $user->id => [$roleA->id, $roleB->id],
            ],
        ]);

        $response->assertStatus(403);
    }
}
