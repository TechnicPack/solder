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
use App\Version;

class AssetTest extends TestCase
{
    /** @test */
    public function it_auto_generates_v4_uuid_for_the_id_column()
    {
        $asset = factory(Asset::class)->create();

        $this->assertTrue(is_string($asset->id));
        $this->assertRegExp('/[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}/i', $asset->id);
    }

    /** @test */
    public function belongs_to_a_version()
    {
        $expectedVersion = factory(Version::class)->create();
        $asset = factory(Asset::class)->create([
            'version_id' => $expectedVersion->id,
        ]);

        $actualVersion = $asset->version;

        $this->assertInstanceOf(Version::class, $actualVersion);
        $this->assertEquals($expectedVersion->id, $actualVersion->id);
    }

    /** @test */
    function it_has_a_location_attribute()
    {
        /** @var Asset $asset */
        $asset = factory(Asset::class)->create();

        $asset->update([
            'location' => '/path/to/asset',
        ]);

        $this->assertEquals('/path/to/asset', $asset->fresh()->location);
    }

    /** @test */
    function it_has_a_filename_attribute()
    {
        /** @var Asset $asset */
        $asset = factory(Asset::class)->create();

        $asset->update([
            'filename' => 'test.txt',
        ]);

        $this->assertEquals('test.txt', $asset->fresh()->filename);
    }

    /** @test */
    function it_has_an_md5_attribute()
    {
        /** @var Asset $asset */
        $asset = factory(Asset::class)->create();

        $asset->update([
            'md5' => 'test-hash',
        ]);

        $this->assertEquals('test-hash', $asset->fresh()->md5);
    }

    /** @test */
    function it_has_a_filezie_attribute()
    {
        /** @var Asset $asset */
        $asset = factory(Asset::class)->create();

        $asset->update([
            'filesize' => 1000,
        ]);

        $this->assertEquals(1000, $asset->fresh()->filesize);
    }
}
