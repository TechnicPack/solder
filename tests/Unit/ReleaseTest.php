<?php

namespace Tests\Unit;

use App\Package;
use App\Release;
use Carbon\Carbon;
use Tests\TestCase;
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
}
