<?php

/*
 * This file is part of TechnicPack Solder.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Feature;

use App\User;
use App\Package;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeletePackageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_admin_can_delete_a_package()
    {
        $user = factory(User::class)->states('admin')->create();
        $package = factory(Package::class)->create();
        $this->assertCount(1, Package::all());

        $response = $this->actingAs($user)->delete("library/{$package->slug}");

        $response->assertRedirect('library');
        $this->assertCount(0, Package::all());
    }

    /** @test */
    public function an_authorized_user_can_delete_a_package()
    {
        $user = factory(User::class)->create();
        $user->grantRole('delete-package');
        $package = factory(Package::class)->create();
        $this->assertEquals(1, Package::count());

        $response = $this->actingAs($user)->delete("library/{$package->slug}");

        $response->assertRedirect('library');
        $this->assertEquals(0, Package::count());
    }

    /** @test */
    public function an_unauthorized_user_cannot_delete_a_package()
    {
        $user = factory(User::class)->create();
        $package = factory(Package::class)->create();
        $this->assertEquals(1, Package::count());

        $response = $this->actingAs($user)->delete("library/{$package->slug}");

        $response->assertStatus(403);
        $this->assertEquals(1, Package::count());
    }

    /** @test */
    public function a_guest_is_asked_to_login_before_deleting_package()
    {
        $package = factory(Package::class)->create();
        $this->assertCount(1, Package::all());

        $response = $this->delete("library/{$package->slug}");

        $response->assertRedirect('login');
        $this->assertCount(1, Package::all());
    }

    /** @test */
    public function attempting_to_delete_a_package_with_invalid_slug_returns_404()
    {
        $user = factory(User::class)->states('admin')->create();
        $package = factory(Package::class)->create();
        $this->assertCount(1, Package::all());

        $response = $this->actingAs($user)->delete('library/invalid-slug');

        $response->assertStatus(404);
        $this->assertCount(1, Package::all());
    }
}
