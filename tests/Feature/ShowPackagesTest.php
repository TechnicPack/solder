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
use App\Release;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowPackagesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_view_a_list_of_packages_in_alphabetic_order()
    {
        $user = factory(User::class)->create();
        $packageC = factory(Package::class)->create(['name' => 'Package C']);
        $packageA = factory(Package::class)->create(['name' => 'Package A']);
        $packageB = factory(Package::class)->create(['name' => 'Package B']);

        $response = $this->actingAs($user)->get('/library');

        $response->assertStatus(200);
        $response->assertViewIs('packages.index');
        $response->data('packages')->assertEquals([
            $packageA,
            $packageB,
            $packageC,
        ]);
    }

    /** @test */
    public function a_guest_cannot_view_package_index()
    {
        $response = $this->get('/library');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function a_user_can_view_package_details()
    {
        $user = factory(User::class)->create();
        $packageC = factory(Package::class)->create(['name' => 'Package C', 'slug' => 'package-c']);
        $packageA = factory(Package::class)->create(['name' => 'Package A']);
        $packageB = factory(Package::class)->create(['name' => 'Package B']);

        $releaseA = factory(Release::class)->create(['package_id' => $packageC->id, 'version' => '1.0.0']);
        $releaseB = factory(Release::class)->create(['package_id' => $packageC->id, 'version' => '1.0.0b']);
        $releaseC = factory(Release::class)->create(['package_id' => $packageC->id, 'version' => '10.0.0']);

        $response = $this->actingAs($user)->get('/library/package-c');

        $response->assertStatus(200);
        $response->assertViewIs('packages.show');
        $this->assertEquals($packageC->id, $response->data('package')->id);
        $response->data('package')->releases->assertEquals([
            $releaseC,
            $releaseB,
            $releaseA,
        ]);
        $response->data('packages')->assertEquals([
            $packageA,
            $packageB,
            $packageC,
        ]);
    }

    /** @test */
    public function a_guest_cannot_view_package_details()
    {
        factory(Package::class)->create(['name' => 'Package C', 'slug' => 'package-c']);

        $response = $this->get('/library/package-c');

        $response->assertRedirect('/login');
    }
}
