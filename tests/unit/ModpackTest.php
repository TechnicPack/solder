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

use App\User;
use App\Build;
use App\Modpack;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ModpackTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_be_authorized_to_see_a_modpack()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->states(['private'])->create();
        $this->assertFalse($modpack->users->contains($user));

        $modpack->authorizeUser($user);

        $this->assertTrue($modpack->fresh()->users->contains($user));
    }

    /** @test */
    public function modpacks_with_a_public_status_can_be_retrieved()
    {
        $publicModpack = factory(Modpack::class)->states(['public'])->create();
        $privateModpack = factory(Modpack::class)->states(['private'])->create();
        $unlistedModpack = factory(Modpack::class)->states(['unlisted'])->create();

        $modpacks = Modpack::whereStatus('public')->get();

        $this->assertTrue($modpacks->contains($publicModpack));
        $this->assertFalse($modpacks->contains($privateModpack));
        $this->assertFalse($modpacks->contains($unlistedModpack));
    }

    /** @test */
    public function modpacks_with_an_unlisted_status_can_be_retrieved()
    {
        $publicModpack = factory(Modpack::class)->states(['public'])->create();
        $privateModpack = factory(Modpack::class)->states(['private'])->create();
        $unlistedModpack = factory(Modpack::class)->states(['unlisted'])->create();

        $modpacks = Modpack::whereStatus('unlisted')->get();

        $this->assertFalse($modpacks->contains($publicModpack));
        $this->assertFalse($modpacks->contains($privateModpack));
        $this->assertTrue($modpacks->contains($unlistedModpack));
    }

    /** @test */
    public function modpacks_with_a_private_status_can_be_retrieved()
    {
        $publicModpack = factory(Modpack::class)->states(['public'])->create();
        $privateModpack = factory(Modpack::class)->states(['private'])->create();
        $unlistedModpack = factory(Modpack::class)->states(['unlisted'])->create();

        $modpacks = Modpack::whereStatus('private')->get();

        $this->assertFalse($modpacks->contains($publicModpack));
        $this->assertTrue($modpacks->contains($privateModpack));
        $this->assertFalse($modpacks->contains($unlistedModpack));
    }

    /** @test */
    public function modpacks_authorized_for_a_user_can_be_retrieved()
    {
        $user = factory(User::class)->create();
        $publicModpack = factory(Modpack::class)->states(['public'])->create();
        $privateModpack = factory(Modpack::class)->states(['private'])->create();
        $unlistedModpack = factory(Modpack::class)->states(['unlisted'])->create();
        $authorizedModpack = factory(Modpack::class)->states(['private'])->create()->authorizeUser($user);

        $modpacks = Modpack::whereStatus('authorized', $user)->get();

        $this->assertFalse($modpacks->contains($publicModpack));
        $this->assertFalse($modpacks->contains($privateModpack));
        $this->assertFalse($modpacks->contains($unlistedModpack));
        $this->assertTrue($modpacks->contains($authorizedModpack));
    }

    /** @test */
    public function requesting_authorized_modpacks_without_a_user_returns_no_results()
    {
        $user = factory(User::class)->create();
        factory(Modpack::class)->states(['public'])->create();
        factory(Modpack::class)->states(['private'])->create();
        factory(Modpack::class)->states(['unlisted'])->create();
        factory(Modpack::class)->states(['private'])->create()->authorizeUser($user);

        $modpacks = Modpack::whereStatus('authorized', null)->get();

        $this->assertEmpty($modpacks);
    }

    /** @test */
    public function can_retrieve_modpacks_with_an_array_of_statuses()
    {
        $publicModpack = factory(Modpack::class)->states(['public'])->create();
        $privateModpack = factory(Modpack::class)->states(['private'])->create();
        $unlistedModpack = factory(Modpack::class)->states(['unlisted'])->create();

        $modpacks = Modpack::whereStatus(['public', 'private'])->get();

        $this->assertTrue($modpacks->contains($publicModpack));
        $this->assertTrue($modpacks->contains($privateModpack));
        $this->assertFalse($modpacks->contains($unlistedModpack));
    }

    /** @test */
    public function converting_to_array()
    {
        $modpack = factory(Modpack::class)->create([
            'slug' => 'example-modpack',
            'name' => 'Example Modpack',
            'recommended' => '1.2.3',
            'latest' => '4.5.6',
        ]);
        $modpack->builds()->save(factory(Build::class)->create(['build_number' => '1.2.3']));
        $modpack->builds()->save(factory(Build::class)->create(['build_number' => '4.5.6']));
        $modpack->builds()->save(factory(Build::class)->create(['build_number' => '7.8.9']));

        $result = $modpack->toArray();

        $this->assertEquals([
            'name' => 'example-modpack',
            'display_name' => 'Example Modpack',
            'recommended' => '1.2.3',
            'latest' => '4.5.6',
            'builds' => [
                '1.2.3',
                '4.5.6',
                '7.8.9',
            ],
        ], $result);
    }
}
