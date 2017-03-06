<?php

/*
 * This file is part of solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Feature;

use App\Build;
use App\User;
use App\Version;
use Carbon\Carbon;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    /** @test */
    public function dashboard_lists_five_most_recent_modpack_builds()
    {
        $user = factory(User::class)->create();

        factory(Build::class)->create(['version' => 'modpack-build-1', 'updated_at' => Carbon::now()->subDays(2)]);
        factory(Build::class, 4)->create(['updated_at' => Carbon::now()->subDays(1)]);
        factory(Build::class)->create(['version' => 'modpack-build-5']);

        $response = $this->actingAs($user)->get('/dashboard', [
            'email' => $user->email,
        ]);

        $response->assertSee('modpack-build-5');
        $response->assertDontSee('modpack-build-1');
    }

    /** @test */
    public function dashboard_lists_five_most_recent_resource_versions()
    {
        $user = factory(User::class)->create();

        factory(Version::class)->create(['version' => 'resource-version-1', 'created_at' => Carbon::now()->subDays(2)]);
        factory(Version::class, 4)->create(['created_at' => Carbon::now()->subDays(1)]);
        factory(Version::class)->create(['version' => 'resource-version-5']);

        $response = $this->actingAs($user)->get('/dashboard', [
            'email' => $user->email,
        ]);

        $response->assertSee('resource-version-5');
        $response->assertDontSee('resource-version-1');
    }
}
