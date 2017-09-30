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
use App\Modpack;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddModpackTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_create_a_modpack()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post('/modpacks', [
            'name' => 'Iron Tanks',
            'slug' => 'iron-tanks',
            'status' => 'public',
        ]);

        tap(Modpack::first(), function ($modpack) use ($response) {
            $this->assertEquals('Iron Tanks', $modpack->name);
            $this->assertEquals('iron-tanks', $modpack->slug);
            $this->assertEquals('public', $modpack->status);

            $response->assertRedirect('/modpacks/iron-tanks');
        });
    }

    /** @test */
    public function a_guest_cannot_create_a_modpack()
    {
        $response = $this->post('/modpacks', [
            'name' => 'Iron Tanks',
            'slug' => 'iron-tanks',
            'status' => 'public',
        ]);

        $response->assertRedirect('/login');
    }
}
