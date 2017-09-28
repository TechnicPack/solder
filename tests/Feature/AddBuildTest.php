<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Feature;

use App\User;
use App\Build;
use App\Modpack;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddBuildTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_view_the_add_build_form()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);

        $response = $this->actingAs($user)->get('/modpacks/brothers-klaus/builds/new');

        $response->assertStatus(200);
        $response->assertViewIs('builds.create');
        $response->assertViewHas('modpack', function ($viewModpack) use ($modpack) {
            return $viewModpack->id == $modpack->id;
        });
    }

    /** @test */
    public function a_guest_cannot_view_the_add_build_form()
    {
        factory(Modpack::class)->create(['slug' => 'brothers-klaus']);

        $response = $this->get('/modpacks/brothers-klaus/builds/new');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function a_user_can_create_a_build()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);

        $response = $this->actingAs($user)->post('/modpacks/brothers-klaus/builds', [
            'version' => '1.3.4',
            'minecraft' => '1.7.10',
            'status' => 'public',
        ]);

        tap(Build::first(), function ($build) use ($modpack, $response) {
            $this->assertEquals('1.3.4', $build->version);
            $this->assertEquals('1.7.10', $build->minecraft);
            $this->assertEquals('public', $build->status);
            $this->assertEquals($modpack->id, $build->modpack_id);

            $response->assertRedirect('/modpacks/brothers-klaus/1.3.4');
        });
    }

    /** @test */
    public function a_guest_cannot_create_a_build()
    {
        factory(Modpack::class)->create(['slug' => 'brothers-klaus']);

        $response = $this->post('/modpacks/brothers-klaus/builds', [
            'version' => '1.3.4',
            'minecraft' => '1.7.10',
            'status' => 'public',
        ]);

        $response->assertRedirect('/login');
        $this->assertCount(0, Build::all());
    }
}
