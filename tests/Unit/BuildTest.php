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

use App\Build;
use App\Modpack;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BuildTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function belongs_to_modpack()
    {
        $modpack = factory(Modpack::class)->create();
        $build = factory(Build::class)->create([
            'modpack_id' => $modpack->id,
        ]);

        $this->assertTrue($build->modpack->is($modpack));
    }

    /** @test */
    public function can_get_formatted_created_date()
    {
        $build = factory(Build::class)->create([
            'created_at' => Carbon::now()->subDays(3),
        ]);

        $this->assertEquals('3 days ago', $build->created);
    }
}
