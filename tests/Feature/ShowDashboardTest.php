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
use App\Package;
use BuildFactory;
use Carbon\Carbon;
use ReleaseFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowDashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_can_view_the_dashboard()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.show');
    }

    /** @test */
    public function dashboard_contains_5_most_recent_modpacks()
    {
        $user = factory(User::class)->create();

        $modpack = factory(Modpack::class)->create();
        $oldBuild = BuildFactory::createForModpack($modpack, ['created_at' => Carbon::parse('6 days ago')]);
        $recentBuild1 = BuildFactory::createForModpack($modpack, ['created_at' => Carbon::parse('5 days ago')]);
        $recentBuild2 = BuildFactory::createForModpack($modpack, ['created_at' => Carbon::parse('4 days ago')]);
        $recentBuild3 = BuildFactory::createForModpack($modpack, ['created_at' => Carbon::parse('3 days ago')]);
        $recentBuild4 = BuildFactory::createForModpack($modpack, ['created_at' => Carbon::parse('2 days ago')]);
        $recentBuild5 = BuildFactory::createForModpack($modpack, ['created_at' => Carbon::parse('1 days ago')]);

        $response = $this->actingAs($user)->get('/');

        $response->data('builds')->assertNotContains($oldBuild);
        $response->data('builds')->assertEquals([
            $recentBuild5,
            $recentBuild4,
            $recentBuild3,
            $recentBuild2,
            $recentBuild1,
        ]);
    }

    /** @test */
    public function dashboard_contains_5_most_recent_packages()
    {
        $user = factory(User::class)->create();

        $package = factory(Package::class)->create();
        $oldRelease = ReleaseFactory::createForPackage($package, ['created_at' => Carbon::parse('6 days ago')]);
        $recentRelease1 = ReleaseFactory::createForPackage($package, ['created_at' => Carbon::parse('5 days ago')]);
        $recentRelease2 = ReleaseFactory::createForPackage($package, ['created_at' => Carbon::parse('4 days ago')]);
        $recentRelease3 = ReleaseFactory::createForPackage($package, ['created_at' => Carbon::parse('3 days ago')]);
        $recentRelease4 = ReleaseFactory::createForPackage($package, ['created_at' => Carbon::parse('2 days ago')]);
        $recentRelease5 = ReleaseFactory::createForPackage($package, ['created_at' => Carbon::parse('1 days ago')]);

        $response = $this->actingAs($user)->get('/');

        $response->data('releases')->assertNotContains($oldRelease);
        $response->data('releases')->assertEquals([
            $recentRelease5,
            $recentRelease4,
            $recentRelease3,
            $recentRelease2,
            $recentRelease1,
        ]);
    }

    /** @test */
    public function guests_cannot_view_the_dashboard()
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }
}
