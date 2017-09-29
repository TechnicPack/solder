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
use App\Package;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddPackageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_create_a_package()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post('/library', [
            'name' => 'Iron Tanks',
            'slug' => 'iron-tanks',
        ]);

        tap(Package::first(), function ($package) use ($response) {
            $this->assertEquals('Iron Tanks', $package->name);
            $this->assertEquals('iron-tanks', $package->slug);

            $response->assertRedirect('/library/iron-tanks');
        });
    }

    /** @test */
    public function a_guest_cannot_create_a_build()
    {
        $response = $this->post('/library', [
            'name' => 'Iron Tanks',
            'slug' => 'iron-tanks',
        ]);

        $response->assertRedirect('/login');
        $this->assertCount(0, Package::all());
    }
}
