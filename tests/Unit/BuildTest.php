<?php

namespace Tests\Unit;

use App\Build;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BuildTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function modpacks_with_a_public_state_are_public()
    {
        $publicBuild = factory(Build::class)->states('public')->create();
        $draftBuild = factory(Build::class)->states('draft')->create();
        $privateBuild = factory(Build::class)->states('private')->create();

        $publicBuilds = Build::public()->get();

        $this->assertTrue($publicBuilds->contains($publicBuild));
        $this->assertFalse($publicBuilds->contains($draftBuild));
        $this->assertFalse($publicBuilds->contains($privateBuild));
    }
}
