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
    public function a_user_can_view_the_add_package_form()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $packageC = factory(Package::class)->create(['name' => 'Package C']);
        $packageA = factory(Package::class)->create(['name' => 'Package A']);
        $packageB = factory(Package::class)->create(['name' => 'Package B']);

        $response = $this->actingAs($user)->get('/library/new');

        $response->assertStatus(200);
        $response->assertViewIs('packages.create');
        $response->data('packages')->assertEquals([
            $packageA,
            $packageB,
            $packageC,
        ]);
    }

    /** @test */
    public function a_guest_cannot_view_the_add_package_form()
    {
        $response = $this->get('/library/new');

        $response->assertRedirect('/login');
    }

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
