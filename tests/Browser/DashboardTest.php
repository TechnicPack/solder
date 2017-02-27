<?php

namespace Tests\Browser;

use App\Modpack;
use App\User;
use App\Build;
use App\Version;
use Carbon\Carbon;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DashboardTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function dashboard_lists_five_most_recent_modpack_builds()
    {
        $user = factory(User::class)->create();

        factory(Build::class)->create(['version' => 'modpack-build-1', 'updated_at' => Carbon::now()->subDays(2)]);
        factory(Build::class, 4)->create(['updated_at' => Carbon::now()->subDays(1)]);
        factory(Build::class)->create(['version' => 'modpack-build-5']);

        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/dashboard')
                    ->assertSee('modpack-build-5')
                    ->assertDontSee('modpack-build-1');
        });
    }

    /** @test */
    public function dashboard_lists_five_most_recent_resource_versions()
    {
        $user = factory(User::class)->create();

        factory(Version::class)->create(['version' => 'resource-version-1', 'created_at' => Carbon::now()->subDays(2)]);
        factory(Version::class, 4)->create(['created_at' => Carbon::now()->subDays(1)]);
        factory(Version::class)->create(['version' => 'resource-version-5']);

        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/dashboard')
                ->assertSee('resource-version-5')
                ->assertDontSee('resource-version-1');
        });
    }
}
