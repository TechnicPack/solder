<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Unit;

use App\Role;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_be_granted_an_existing_role_by_tag()
    {
        $role = Role::first();
        $user = factory(User::class)->create();
        $this->assertFalse($user->roles->contains($role));

        $user->grantRole($role->tag);

        $this->assertTrue($user->fresh()->roles->contains($role));
    }
}
