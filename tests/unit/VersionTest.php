<?php

namespace Tests\unit;

/*
 * This file is part of TechnicSolder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use App\Asset;
use App\Build;
use App\Resource;
use App\Version;
use Tests\TestCase;

class VersionTest extends TestCase
{
    /** @test */
    public function it_auto_generates_v4_uuid_for_the_id_column()
    {
        $version = factory(Version::class)->create();

        $this->assertTrue(is_string($version->id));
        $this->assertRegExp('/[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}/i', $version->id);
    }

    /** @test */
    public function has_many_assets()
    {
        $version = factory(Version::class)->create();
        $asset = factory(Asset::class)->create([
            'version_id' => $version->id,
        ]);

        $assets = $version->assets;

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $assets);
        $this->assertTrue($assets->contains($asset));
    }

    /** @test */
    public function belongs_to_a_resource()
    {
        $expectedResource = factory(Resource::class)->create();
        $version = factory(Version::class)->create([
            'resource_id' => $expectedResource->id,
        ]);

        $actualResource = $version->resource;

        $this->assertInstanceOf(Resource::class, $actualResource);
        $this->assertEquals($expectedResource->id, $actualResource->id);
    }

    /** @test */
    public function belongs_to_many_builds()
    {
        $version = factory(Version::class)->create();
        $build = factory(Build::class)->create();

        $version->builds()->attach($build);
        $builds = $version->builds;

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $builds);
        $this->assertTrue($builds->contains($build));
    }
}
