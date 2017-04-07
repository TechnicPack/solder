<?php

namespace Tests\Unit;

use App\Build;
use App\Modpack;
use App\Version;
use Tests\TestCase;

class BuildTest extends TestCase
{

    /** @test */
    public function it_belongs_to_a_modpack()
    {


    }

    /** @test */
    public function belongs_to_many_versions()
    {


    }

    /** @test */
    public function it_has_a_game_version_attribute()
    {


        $this->assertTrue($build->fresh()->is_promoted);
    }

    /** @test */
    public function the_build_with_the_highest_version_should_be_marked_latest()
    {


    }

    /** @test */
    public function only_one_latest_build_can_exist_for_a_modpack()
    {
        ]);
    }

}
