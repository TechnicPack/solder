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

    private function validParams($overrides = [])
    {
        return array_merge([
            'version' => '1.3.4_beta-2',
            'minecraft' => '1.7.10',
            'status' => 'public',
        ], $overrides);
    }

    /** @test */
    public function a_user_can_create_a_build()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);

        $response = $this->actingAs($user)->post('/modpacks/brothers-klaus/builds', [
            'version' => '1.3.4_beta-2',
            'minecraft' => '1.7.10',
            'status' => 'public',
        ]);

        tap(Build::first(), function ($build) use ($modpack, $response) {
            $this->assertEquals('1.3.4_beta-2', $build->version);
            $this->assertEquals('1.7.10', $build->minecraft);
            $this->assertEquals('public', $build->status);
            $this->assertEquals($modpack->id, $build->modpack_id);

            $response->assertRedirect('/modpacks/brothers-klaus');
        });
    }

    /** @test */
    public function a_guest_cannot_create_a_build()
    {
        factory(Modpack::class)->create(['slug' => 'brothers-klaus']);

        $response = $this->post('/modpacks/brothers-klaus/builds', [
            'version' => '1.3.4b',
            'minecraft' => '1.7.10',
            'status' => 'public',
        ]);

        $response->assertRedirect('/login');
        $this->assertCount(0, Build::all());
    }

    /** @test */
    public function version_is_required()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus')->post('/modpacks/brothers-klaus/builds', $this->validParams([
                'version' => '',
        ]));

        $response->assertRedirect('/modpacks/brothers-klaus');
        $response->assertSessionHasErrors('version');
        $this->assertEquals(0, Build::count());
    }

    /** @test */
    public function version_must_be_unique_for_the_modpack()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);
        $build = factory(Build::class)->create(['modpack_id' => $modpack->id, 'version' => '1.2.3']);

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus')->post('/modpacks/brothers-klaus/builds', $this->validParams([
            'version' => '1.2.3',
        ]));

        $response->assertRedirect('/modpacks/brothers-klaus');
        $response->assertSessionHasErrors('version');
        $this->assertEquals(1, Build::count());
    }

    /** @test */
    public function version_may_not_contain_spaces()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus')->post('/modpacks/brothers-klaus/builds', $this->validParams([
            'version' => 'invalid version',
        ]));

        $response->assertRedirect('/modpacks/brothers-klaus');
        $response->assertSessionHasErrors('version');
        $this->assertEquals(0, Build::count());
    }

    /** @test */
    public function version_may_not_contain_symbols()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus')->post('/modpacks/brothers-klaus/builds', $this->validParams([
            'version' => '!@#$%^&*()',
        ]));

        $response->assertRedirect('/modpacks/brothers-klaus');
        $response->assertSessionHasErrors('version');
        $this->assertEquals(0, Build::count());
    }

    /** @test */
    public function version_cannot_start_with_a_dot()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus')->post('/modpacks/brothers-klaus/builds', $this->validParams([
            'version' => '.8.0',
        ]));

        $response->assertRedirect('/modpacks/brothers-klaus');
        $response->assertSessionHasErrors('version');
        $this->assertEquals(0, Build::count());
    }

    /** @test */
    public function status_is_required()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus')->post('/modpacks/brothers-klaus/builds', $this->validParams([
            'status' => '',
        ]));

        $response->assertRedirect('/modpacks/brothers-klaus');
        $response->assertSessionHasErrors('status');
        $this->assertEquals(0, Build::count());
    }

    /** @test */
    public function status_is_valid()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus')->post('/modpacks/brothers-klaus/builds', $this->validParams([
            'status' => 'invalid',
        ]));

        $response->assertRedirect('/modpacks/brothers-klaus');
        $response->assertSessionHasErrors('status');
        $this->assertEquals(0, Build::count());
    }

    /** @test */
    public function minecraft_version_is_required()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'brothers-klaus']);

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus')->post('/modpacks/brothers-klaus/builds', $this->validParams([
            'minecraft' => '',
        ]));

        $response->assertRedirect('/modpacks/brothers-klaus');
        $response->assertSessionHasErrors('minecraft');
        $this->assertEquals(0, Build::count());
    }
}
