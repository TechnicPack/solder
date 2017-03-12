<?php

namespace Tests\Browser;

use App\User;
use App\Build;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BuildsTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_lists_modpack_builds()
    {
        $build = factory(Build::class)->create([
            'version' => '1.2.3',
        ]);

        $this->browse(function ($browser) use ($build) {
            $browser->loginAs(factory(User::class)->create())
                    ->visit('/modpacks/'.$build->modpack->id.'/builds')
                    ->waitFor('.table')
                    ->assertSee('1.2.3');
        });
    }
}
