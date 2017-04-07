<?php

namespace Tests\Unit;

use App\Build;
use App\Modpack;
use Tests\TestCase;

class ModpackTest extends TestCase
{

    /** @test */
    public function has_a_promoted_build()
    {


    }

    /** @test */
    public function has_a_promoted_build_version_attribute()
    {


    }

    /** @test */
    public function latest_build_returns_first_build_sorted_by_version()
    {


    }

    /** @test */
    public function has_a_latest_build_version_attribute()
    {


    }

    /** @test */
    public function a_modpack_without_a_promoted_build_returns_empty_string_as_promoted_build_version()
    {
    }

    /** @test */
    public function a_modpack_without_a_latest_build_returns_empty_string_as_latest_build_version()
    {


    }

    /** @test */
    public function it_can_generate_a_slug_from_name_on_create()
    {


    }

    /** @test */
    public function it_does_not_overwrite_a_slug_on_name_updates()
    {
            'slug' => 'example-modpack',
            'name' => 'Example Modpack',
        ]);
    }
}
