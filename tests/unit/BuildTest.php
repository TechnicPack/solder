<?php

use App\Build;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BuildTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function builds_with_a_published_at_date_in_the_past_are_published()
    {
        $publishedBuildA = factory(Build::class)->create(['published_at' => Carbon::parse('-1 week')]);
        $publishedBuildB = factory(Build::class)->create(['published_at' => Carbon::parse('+1 week')]);
        $unpublishedBuild = factory(Build::class)->create(['published_at' => null]);

        $publishedBuilds = Build::published()->get();

        $this->assertTrue($publishedBuilds->contains($publishedBuildA));
        $this->assertFalse($publishedBuilds->contains($publishedBuildB));
        $this->assertFalse($publishedBuilds->contains($unpublishedBuild));
    }
}
