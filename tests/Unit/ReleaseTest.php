<?php

/*
 * This file is part of TechnicPack Solder.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Unit;

use App\Package;
use App\Release;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReleaseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function belongs_to_package()
    {
        $package = factory(Package::class)->create();
        $release = factory(Release::class)->create([
            'package_id' => $package->id,
        ]);

        $this->assertTrue($release->package->is($package));
    }

    /** @test */
    public function can_get_formatted_created_date()
    {
        $release = factory(Release::class)->create([
            'created_at' => Carbon::now()->subDays(3),
        ]);

        $this->assertEquals('3 days ago', $release->created);
    }

    /** @test */
    public function can_get_download_url()
    {
        Storage::shouldReceive('url')
            ->once()
            ->with('path/to-file.zip')
            ->andReturn('http://example.com/path/to-file.zip');

        $release = factory(Release::class)->create([
            'path' => 'path/to-file.zip',
        ]);

        $this->assertEquals('http://example.com/path/to-file.zip', $release->url);
    }

    /** @test */
    public function can_get_filename()
    {
        $release = factory(Release::class)->create(['path' => 'iron-tanks/iron-tanks-1.2.3.zip']);

        $this->assertEquals('iron-tanks-1.2.3.zip', $release->filename);
    }

    /** @test */
    public function deleting_a_release_removes_the_associated_file()
    {
        $release = factory(Release::class)->create(['path' => 'iron-tanks/iron-tanks-1.2.3.zip']);
        Storage::shouldReceive('delete')->with('iron-tanks/iron-tanks-1.2.3.zip')->once()->andReturn(true);
        $this->assertCount(1, Release::all());

        $release->delete();

        $this->assertCount(0, Release::all());
    }
}
