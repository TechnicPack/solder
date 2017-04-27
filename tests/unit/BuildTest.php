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

class BuildTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function builds_with_a_public_status_can_be_retrieved()
    {
        $publicBuild = factory(Build::class)->states(['public'])->create();
        $privateBuild = factory(Build::class)->states(['private'])->create();
        $draftBuild = factory(Build::class)->states(['draft'])->create();

        $builds = Build::whereStatus('public')->get();

        $this->assertTrue($builds->contains($publicBuild));
        $this->assertFalse($builds->contains($privateBuild));
        $this->assertFalse($builds->contains($draftBuild));
    }

    /** @test */
    public function builds_with_a_private_status_can_be_retrieved()
    {
        $publicBuild = factory(Build::class)->states(['public'])->create();
        $privateBuild = factory(Build::class)->states(['private'])->create();
        $draftBuild = factory(Build::class)->states(['draft'])->create();

        $builds = Build::whereStatus('private')->get();

        $this->assertFalse($builds->contains($publicBuild));
        $this->assertTrue($builds->contains($privateBuild));
        $this->assertFalse($builds->contains($draftBuild));
    }

    /** @test */
    public function builds_with_a_draft_status_can_be_retrieved()
    {
        $publicBuild = factory(Build::class)->states(['public'])->create();
        $privateBuild = factory(Build::class)->states(['private'])->create();
        $draftBuild = factory(Build::class)->states(['draft'])->create();

        $builds = Build::whereStatus('draft')->get();

        $this->assertFalse($builds->contains($publicBuild));
        $this->assertFalse($builds->contains($privateBuild));
        $this->assertTrue($builds->contains($draftBuild));
    }

    /** @test */
    public function private_builds_under_a_modpack_authorized_for_a_user_can_be_retrieved()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create()->authorizeUser($user);
        $authorizedBuild1 = factory(Build::class)->states(['private'])->create(['modpack_id' => $modpack->id]);
        $authorizedBuild2 = factory(Build::class)->states(['public'])->create(['modpack_id' => $modpack->id]);
        $draftBuild = factory(Build::class)->states(['draft'])->create();

        $builds = Build::whereStatus('authorized', $user)->get();

        $this->assertTrue($builds->contains($authorizedBuild1));
        $this->assertFalse($builds->contains($authorizedBuild2));
        $this->assertFalse($builds->contains($draftBuild));
    }

    /** @test */
    public function can_get_status_as_string()
    {
        $publicBuild = factory(Build::class)->states(['public'])->create();
        $privateBuild = factory(Build::class)->states(['private'])->create();
        $draftBuild = factory(Build::class)->states(['draft'])->create();

        $this->assertEquals('public', $publicBuild->status_as_string);
        $this->assertEquals('private', $privateBuild->status_as_string);
        $this->assertEquals('draft', $draftBuild->status_as_string);
    }

    /** @test */
    public function can_set_status_as_string()
    {
        $publicBuild = new Build;
        $privateBuild = new Build;
        $draftBuild = new Build;

        $publicBuild->status_as_string = 'public';
        $privateBuild->status_as_string = 'private';
        $draftBuild->status_as_string = 'draft';

        $this->assertEquals(Build::STATE_PUBLIC, $publicBuild->status);
        $this->assertEquals(Build::STATE_PRIVATE, $privateBuild->status);
        $this->assertEquals(Build::STATE_DRAFT, $draftBuild->status);
    }

    /** @test */
    public function can_get_link_to_self()
    {
        $build = factory(Build::class)->create();

        $this->assertEquals(url("/api/builds/{$build->id}"), $build->link_self);
    }

    /** @test */
    public function converting_to_array()
    {
        $build = factory(Build::class)->create([
            'build_number' => '1.0.0',
            'minecraft_version' => '1.7.10',
            'arguments' => [
                'forge_version' => '10.1.12.345',
                'java_version' => '1.7',
                'java_memory' => 1024,
            ],
        ]);

        $result = $build->toArray();

        $this->assertEquals([
            'minecraft' => '1.7.10',
            'forge' => '10.1.12.345',
            'java' => '1.7',
            'memory' => 1024,
        ], $result);
    }
}
