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

use App\Version;
use App\Resource;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class VersionTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_get_self_link()
    {
        \Config::set('app.url', 'http://example.com');
        $version = factory(Version::class)->create();

        $this->assertEquals("http://example.com/api/versions/{$version->id}", $version->link_self);
    }

    /** @test */
    public function can_get_zip_url()
    {
        $version = factory(Version::class)->create([
            'zip_path' => 'path/to/mod-version-1.zip',
        ]);

        \Storage::shouldReceive('url')
            ->with('path/to/mod-version-1.zip')
            ->andReturn('http://example.com/mod-version-1.zip');

        $zipUrl = $version->zip_url;

        $this->assertEquals('http://example.com/mod-version-1.zip', $zipUrl);
    }

    /** @test */
    public function converting_to_array()
    {
        // Arrange
        $resource = factory(Resource::class)->create([
            'slug' => 'resource-1',
        ]);
        $version = $resource->versions()->save(factory(Version::class)->make([
            'version_number' => '1',
            'zip_md5' => 'file-hash-1',
            'zip_path' => 'path/to/mod-version-1.zip',
        ]));

        // Expect
        \Storage::shouldReceive('url')
            ->with('path/to/mod-version-1.zip')
            ->andReturn('http://example.com/mod-version-1.zip');

        // Act
        $result = $version->toArray();

        // Assert
        $this->assertEquals([
            'name' => 'resource-1',
            'version' => '1',
            'url' => 'http://example.com/mod-version-1.zip',
            'md5' => 'file-hash-1',
        ], $result);
    }
}
