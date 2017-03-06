<?php

namespace Tests\unit;

/*
 * This file is part of Solder Framework.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use App\Build;
use App\Modpack;
use App\Version;
use Tests\TestCase;

class BuildTest extends TestCase
{
    /** @test */
    public function it_auto_generates_v4_uuid_for_the_id_column()
    {
        $build = factory(Build::class)->create();

        $this->assertTrue(is_string($build->id));
        $this->assertRegExp('/[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}/i', $build->id);
    }

    /** @test */
    public function it_belongs_to_a_modpack()
    {
        $generatedModpack = factory(Modpack::class)->create();
        $build = factory(Build::class)->create([
            'modpack_id' => $generatedModpack->id,
        ]);

        $retrievedModpack = $build->modpack;

        $this->assertInstanceOf(Modpack::class, $retrievedModpack);
        $this->assertEquals($generatedModpack->id, $retrievedModpack->id);
    }

    /** @test */
    public function belongs_to_many_versions()
    {
        $version = factory(Version::class)->create();
        $build = factory(Build::class)->create();

        $build->versions()->attach($version);
        $versions = $build->versions;

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $versions);
        $this->assertTrue($versions->contains($version));
    }

    /** @test */
    public function it_has_a_game_version_attribute()
    {
        /** @var Build $build */
        $build = factory(Build::class)->create();

        $build->update([
            'game_version' => '1.0.0',
        ]);

        $this->assertEquals('1.0.0', $build->fresh()->game_version);
    }

    /** @test */
    public function a_build_can_be_promoted()
    {
        /** @var Build $build */
        $build = factory(Build::class)->create();
        $this->assertFalse($build->is_promoted);

        $build->promote();

        $this->assertTrue($build->fresh()->is_promoted);
    }

    /** @test */
    public function the_build_with_the_highest_version_should_be_marked_latest()
    {
        $modpack = factory(Modpack::class)->create();
        $build1 = factory(Build::class)->create(['version' => '1.8.9', 'modpack_id' => $modpack->id]);
        $build2 = factory(Build::class)->create(['version' => '1.8.8', 'modpack_id' => $modpack->id]);

        $this->assertTrue($build1->is_latest);
        $this->assertFalse($build2->is_latest);
    }

    /** @test */
    public function only_one_promoted_build_can_exist_for_a_modpack()
    {
        // Create multiple builds, each for the same modpack
        $modpack = factory(Modpack::class)->create();
        $builds = factory(Build::class, 2)->create([
            'modpack_id' => $modpack->id,
        ]);

        // Attempt to promote every build
        $builds->each(function ($build) {
            $build->promote();
        });

        // Assert that only one build was tagged promoted
        $promotedBuilds = Build::where('is_promoted', true)->get();
        $this->assertEquals(1, count($promotedBuilds));
    }

    /** @test */
    public function only_one_latest_build_can_exist_for_a_modpack()
    {
        // Create multiple builds, each for the same modpack
        $builds = factory(Build::class, 5)->create([
            'modpack_id' => factory(Modpack::class)->create()->id,
        ]);

        // Get all the "latest" builds
        $latestBuilds = $builds->filter(function ($build) {
            return $build->is_latest;
        });

        // Assert that only one build says its latest
        $this->assertEquals(1, count($latestBuilds));
    }

    /** @test */
    public function all_modpacks_can_have_a_promoted_build()
    {
        // Create multiple builds, each with a different modpack
        $builds = factory(Build::class, 3)->create();

        // Attempt to promote every build
        $builds->each(function ($build) {
            $build->promote();
        });

        // Assert that every build was tagged promoted
        $promotedBuilds = Build::where('is_promoted', true)->get();
        $this->assertEquals(3, count($promotedBuilds));
    }

    /** @test */
    public function has_count_of_resources()
    {
        $build = factory(Build::class)->create();
        $build->versions()->saveMany(factory(Version::class, 3)->make());

        $resourceCount = $build->resource_count;

        $this->assertEquals(3, $resourceCount);
    }
}
