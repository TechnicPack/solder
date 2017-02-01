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

class ModpackTest extends TestCase
{
    /** @test */
    public function it_auto_generates_v4_uuid_for_the_id_column()
    {
        $modpack = factory(Modpack::class)->create();

        $this->assertTrue(is_string($modpack->id));
        $this->assertRegExp('/[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}/i', $modpack->id);
    }

    /** @test */
    public function has_many_builds()
    {
        $modpack = factory(Modpack::class)->create();
        $build = factory(Build::class)->create([
            'modpack_id' => $modpack->id,
        ]);

        $builds = $modpack->builds;

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $builds);
        $this->assertTrue($builds->contains($build));
    }

    /** @test */
    public function it_can_generate_a_slug_from_name_on_create()
    {
        $modpack = new Modpack([
            'name' => 'Example Modpack',
        ]);
        $this->assertNull($modpack->slug);

        $modpack->save();

        $this->assertEquals('example-modpack', $modpack->fresh()->slug);
    }

    /** @test */
    public function it_does_not_overwrite_a_slug_on_name_updates()
    {
        $modpack = Modpack::create([
            'name' => 'Example Modpack',
            'slug' => 'example-modpack',
        ]);
        $this->assertEquals('example-modpack', $modpack->slug);

        $modpack->update([
            'name' => 'New Example Modpack',
        ]);

        $this->assertEquals('example-modpack', $modpack->fresh()->slug);
    }

    /** @test */
    public function it_accepts_a_provided_slug()
    {
        $modpack = new Modpack([
            'name' => 'Example Modpack',
            'slug' => 'non-standard-slug',
        ]);
        $this->assertEquals('non-standard-slug', $modpack->slug);

        $modpack->save();

        $this->assertEquals('non-standard-slug', $modpack->fresh()->slug);
    }

    /** @test */
    public function can_get_tags_as_string()
    {
        $taggedModpack = factory(Modpack::class)->make([
            'tags' => ['tag-1', 'tag-2'],
        ]);
        $untaggedModpack = factory(Modpack::class)->make();

        $this->assertEquals('tag-1, tag-2', $taggedModpack->tags_as_string);
        $this->assertEquals('', $untaggedModpack->tags_as_string);
    }
}
