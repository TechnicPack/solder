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

use App\Package;
use App\Release;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PackageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function deleting_a_package_removes_related_releases()
    {
        $package = factory(Package::class)->create();
        $relatedReleaseA = factory(Release::class)->create(['package_id' => $package->id]);
        $relatedReleaseB = factory(Release::class)->create(['package_id' => $package->id]);
        $unrelatedRelease = factory(Release::class)->create(['package_id' => '99']);
        $this->assertCount(1, Package::all());

        $package->delete();

        $this->assertCount(0, Package::all());
        $this->assertDatabaseMissing('releases', ['id' => $relatedReleaseA->id]);
        $this->assertDatabaseMissing('releases', ['id' => $relatedReleaseB->id]);
        $this->assertDatabaseHas('releases', ['id' => $unrelatedRelease->id]);
    }
}
