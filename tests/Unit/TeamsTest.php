<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Unit;

use App\Team;
use App\User;
use App\Modpack;
use App\Package;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeamsTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_team_has_users()
    {
        $team = factory(Team::class)->create();
        $userA = factory(User::class)->create();
        $userB = factory(User::class)->create();
        $team->users()->attach($userA);

        $users = $team->users;

        $users->assertContains($userA);
        $users->assertNotContains($userB);
    }

    /** @test **/
    public function a_team_has_many_modpacks()
    {
        $team = factory(Team::class)->create();
        $modpackA = factory(Modpack::class)->create(['team_id' => $team->id]);
        $modpackB = factory(Modpack::class)->create();

        $modpacks = $team->modpacks;

        $modpacks->assertContains($modpackA);
        $modpacks->assertNotContains($modpackB);
    }

    /** @test **/
    public function a_team_has_many_packages()
    {
        $team = factory(Team::class)->create();
        $packageA = factory(Package::class)->create(['team_id' => $team->id]);
        $packageB = factory(Package::class)->create();

        $packages = $team->packages;

        $packages->assertContains($packageA);
        $packages->assertNotContains($packageB);
    }
}
