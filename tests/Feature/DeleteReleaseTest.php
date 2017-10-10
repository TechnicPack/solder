<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Feature;

use App\User;
use App\Release;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteReleaseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_delete_a_release()
    {
        $user = factory(User::class)->create();
        $release = factory(Release::class)->create();
        $this->assertCount(1, Release::all());

        $response = $this->actingAs($user)->delete('/releases/'.$release->id);

        $response->assertStatus(204);
        $this->assertCount(0, Release::all());
    }

    /** @test */
    public function a_guest_cannot_delete_a_release()
    {
        $release = factory(Release::class)->create();
        $this->assertCount(1, Release::all());

        $response = $this->delete('/releases/'.$release->id);

        $response->assertRedirect('/login');
        $this->assertCount(1, Release::all());
    }

    /** @test */
    public function a_user_cannot_delete_a_non_existent_release()
    {
        $user = factory(User::class)->create();
        factory(Release::class)->create();
        $this->assertCount(1, Release::all());

        $response = $this->actingAs($user)->delete('/releases/99');

        $response->assertStatus(404);
        $this->assertCount(1, Release::all());
    }
}
