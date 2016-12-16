<?php

use App\Modpack;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ModpackTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function modpacks_with_a_published_at_date_in_the_past_are_published()
    {
        $publishedBuildA = factory(Modpack::class)->create(['published_at' => Carbon::parse('-1 week')]);
        $publishedBuildB = factory(Modpack::class)->create(['published_at' => Carbon::parse('+1 week')]);
        $unpublishedBuild = factory(Modpack::class)->create(['published_at' => null]);

        $publishedBuilds = Modpack::published()->get();

        $this->assertTrue($publishedBuilds->contains($publishedBuildA));
        $this->assertFalse($publishedBuilds->contains($publishedBuildB));
        $this->assertFalse($publishedBuilds->contains($unpublishedBuild));
    }


}
