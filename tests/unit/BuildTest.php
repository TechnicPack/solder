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
}
