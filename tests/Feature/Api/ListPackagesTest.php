<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Feature\Api;

use App\User;
use App\Package;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListPackagesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_list_all_packages()
    {
        $user = factory(User::class)->create();
        $packageA = factory(Package::class)->create(['name' => 'Iron Tanks']);
        $packageB = factory(Package::class)->create(['name' => 'Buildcraft']);

        $response = $this->actingAs($user, 'api')->getJson('/api/packages');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'data' => [
                ['id' => $packageA->id, 'name' => 'Iron Tanks'],
                ['id' => $packageB->id, 'name' => 'Buildcraft'],
            ],
        ]);
    }

    /** @test */
    public function a_guest_cannot_list_packages()
    {
        $response = $this->getJson('/api/packages');

        $response->assertStatus(401);
    }

    /** @test */
    public function a_user_can_get_details_about_a_package()
    {
        $user = factory(User::class)->create();
        $package = factory(Package::class)->create(['name' => 'Buildcraft']);

        $response = $this->actingAs($user, 'api')->getJson('/api/packages/'.$package->id);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'data' => [
                'id' => $package->id,
                'name' => 'Buildcraft',
            ],
        ]);
    }

    /** @test */
    public function a_guest_cannot_get_details_about_a_package()
    {
        $package = factory(Package::class)->create(['name' => 'Buildcraft']);

        $response = $this->getJson('/api/packages/'.$package->id);

        $response->assertStatus(401);
    }

    /** @test */
    public function include_releases_in_package_details()
    {
        $user = factory(User::class)->create();
        $package = factory(Package::class)->create(['name' => 'Buildcraft']);
        $releaseA = \ReleaseFactory::createForPackage($package, ['version' => '1.0.0']);
        $releaseB = \ReleaseFactory::createForPackage($package, ['version' => '2.0.0']);
        $releaseC = \ReleaseFactory::createForPackage($package, ['version' => '3.0.0']);

        $response = $this->actingAs($user, 'api')->getJson('/api/packages/'.$package->id.'?include=releases');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'releases' => [
                'data' => [
                    ['id' => $releaseA->id, 'version' => '1.0.0'],
                    ['id' => $releaseB->id, 'version' => '2.0.0'],
                    ['id' => $releaseC->id, 'version' => '3.0.0'],
                ],
            ],
        ]);
    }
}
