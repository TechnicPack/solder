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

use App\Build;
use App\Modpack;
use Tests\TestCase;
use Illuminate\Support\Optional;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModpackTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function promoted_build_attribute_is_optional()
    {
        $modpack = factory(Modpack::class)->make([
            'promoted_build_id' => null,
        ]);

        $this->assertInstanceOf(Optional::class, $modpack->promoted_build);
    }

    /** @test */
    public function latest_build_attribute_is_optional()
    {
        $modpack = factory(Modpack::class)->make([
            'latest_build_id' => null,
        ]);

        $this->assertInstanceOf(Optional::class, $modpack->latest_build);
    }

    /** @test */
    public function can_get_monogram_attribute()
    {
        $modpack = factory(Modpack::class)->create(['name' => 'Example Modpack']);

        $this->assertEquals('Ex', $modpack->monogram);
    }

    /** @test */
    public function can_get_icon_url_attribute()
    {
        Storage::shouldReceive('url')->with('/modpack_icons/iconfile.png')->once()->andReturn('http://example.com/modpack_icons/iconfile.png');
        $modpack = factory(Modpack::class)->create(['icon_path' => '/modpack_icons/iconfile.png']);

        $this->assertEquals('http://example.com/modpack_icons/iconfile.png', $modpack->icon_url);
    }

    /** @test */
    public function deleting_a_modpack_removes_modpack_icon_file()
    {
        $modpack = factory(Modpack::class)->create(['icon_path' => 'modpack_icons/iconfile.png']);
        Storage::shouldReceive('delete')->with('modpack_icons/iconfile.png')->once()->andReturn(true);
        $this->assertCount(1, Modpack::all());

        $modpack->delete();

        $this->assertCount(0, Modpack::all());
    }

    /** @test */
    public function dont_attempt_to_delete_icon_if_path_is_null()
    {
        $modpack = factory(Modpack::class)->create(['icon_path' => null]);
        Storage::shouldReceive('delete')->never();
        $this->assertCount(1, Modpack::all());

        $modpack->delete();

        $this->assertCount(0, Modpack::all());
    }

    /** @test */
    public function deleting_a_modpack_removes_related_builds()
    {
        $modpack = factory(Modpack::class)->create();
        $relatedBuildA = factory(Build::class)->create(['modpack_id' => $modpack->id]);
        $relatedBuildB = factory(Build::class)->create(['modpack_id' => $modpack->id]);
        $unrelatedBuild = factory(Build::class)->create(['modpack_id' => '99']);
        $this->assertCount(1, Modpack::all());

        $modpack->delete();

        $this->assertDatabaseMissing('builds', ['id' => $relatedBuildA->id]);
        $this->assertDatabaseMissing('builds', ['id' => $relatedBuildB->id]);
        $this->assertDatabaseHas('builds', ['id' => $unrelatedBuild->id]);
    }
}
