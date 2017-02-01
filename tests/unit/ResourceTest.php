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

use App\Resource;
use App\Version;
use Tests\TestCase;

class ResourceTest extends TestCase
{
    /** @test */
    public function it_auto_generates_v4_uuid_for_the_id_column()
    {
        $resource = factory(Resource::class)->create();

        $this->assertTrue(is_string($resource->id));
        $this->assertRegExp('/[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}/i', $resource->id);
    }

    /** @test */
    public function has_many_versions()
    {
        $resource = factory(Resource::class)->create();
        $version = factory(Version::class)->create([
            'resource_id' => $resource->id,
        ]);

        $versions = $resource->versions;

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $versions);
        $this->assertTrue($versions->contains($version));
    }

    /** @test */
    public function it_can_generate_a_slug_from_name_on_create()
    {
        $resource = factory(Resource::class)->create([
            'name' => 'Example Resource',
        ]);

        $this->assertEquals('example-resource', $resource->fresh()->slug);
    }

    /** @test */
    public function it_does_not_overwrite_a_slug_on_name_updates()
    {
        $resource = factory(Resource::class)->create([
            'name' => 'Example Resource',
            'slug' => 'example-slug',
        ]);
        $this->assertEquals('example-slug', $resource->slug);

        $resource->update([
            'name' => 'New Example Resource',
        ]);

        $this->assertEquals('example-slug', $resource->fresh()->slug);
    }

    /** @test */
    public function it_accepts_a_provided_slug()
    {
        $resource = factory(Resource::class)->create([
            'name' => 'Example Resource',
            'slug' => 'non-standard-slug',
        ]);
        $this->assertEquals('non-standard-slug', $resource->slug);

        $resource->save();

        $this->assertEquals('non-standard-slug', $resource->fresh()->slug);
    }

    /** @test */
    public function it_has_an_author_attribute()
    {
        /** @var Build $resource */
        $resource = factory(Resource::class)->create();

        $resource->update([
            'author' => 'Resource Developer',
        ]);

        $this->assertEquals('Resource Developer', $resource->fresh()->author);
    }

    /** @test */
    public function it_has_a_description_attribute()
    {
        /** @var Build $resource */
        $resource = factory(Resource::class)->create();

        $resource->update([
            'description' => 'This resource is a test.',
        ]);

        $this->assertEquals('This resource is a test.', $resource->fresh()->description);
    }

    /** @test */
    public function it_has_a_website_attribute()
    {
        /** @var Build $resource */
        $resource = factory(Resource::class)->create();

        $resource->update([
            'website' => 'http://example.com',
        ]);

        $this->assertEquals('http://example.com', $resource->fresh()->website);
    }
}
