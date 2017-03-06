<?php

/*
 * This file is part of solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Feature;

use App\User;
use App\Build;
use Tests\TestCase;

class ViewModpackBuildsTest extends TestCase
{
    /** @test */
    public function it_lists_modpack_builds()
    {
        $user = factory(User::class)->create();
        $build = factory(Build::class)->create([
            'version' => '1.2.3',
        ]);

        $response = $this->actingAs($user)->get('/modpacks/'.$build->modpack->id.'/builds');

        $response->assertStatus(200)
                 ->assertSee('1.2.3');
    }
}
