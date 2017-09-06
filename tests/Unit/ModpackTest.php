<?php

namespace Tests\Unit;

use App\Client;
use App\Modpack;
use Illuminate\Support\Optional;
use Tests\TestCase;
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
    public function modpacks_with_a_public_state_are_public()
    {
        $publicModpack = factory(Modpack::class)->states('public')->create();
        $draftModpack = factory(Modpack::class)->states('draft')->create();
        $privateModpack = factory(Modpack::class)->states('private')->create();

        $publishedModpacks = Modpack::public()->get();

        $this->assertTrue($publishedModpacks->contains($publicModpack));
        $this->assertFalse($publishedModpacks->contains($draftModpack));
        $this->assertFalse($publishedModpacks->contains($privateModpack));
    }

    /** @test */
    public function modpacks_with_a_draft_state_are_draft()
    {
        $publicModpack = factory(Modpack::class)->states('public')->create();
        $draftModpack = factory(Modpack::class)->states('draft')->create();
        $privateModpack = factory(Modpack::class)->states('private')->create();

        $publishedModpacks = Modpack::draft()->get();

        $this->assertFalse($publishedModpacks->contains($publicModpack));
        $this->assertTrue($publishedModpacks->contains($draftModpack));
        $this->assertFalse($publishedModpacks->contains($privateModpack));
    }

    /** @test */
    public function modpacks_with_a_private_state_are_private()
    {
        $publicModpack = factory(Modpack::class)->states('public')->create();
        $draftModpack = factory(Modpack::class)->states('draft')->create();
        $privateModpack = factory(Modpack::class)->states('private')->create();

        $publishedModpacks = Modpack::private()->get();

        $this->assertFalse($publishedModpacks->contains($publicModpack));
        $this->assertFalse($publishedModpacks->contains($draftModpack));
        $this->assertTrue($publishedModpacks->contains($privateModpack));
    }
}
