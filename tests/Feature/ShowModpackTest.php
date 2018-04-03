<?php

/*
 * This file is part of TechnicPack Solder.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Feature;

use App\User;
use App\Modpack;
use BuildFactory;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowModpackTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_view_a_modpack()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create([
            'slug' => 'example-modpack',
        ]);

        $response = $this->actingAs($user)->get('/modpacks/example-modpack');

        $response->assertStatus(200);
        $response->assertViewIs('modpacks.show');
        $response->assertViewHas('modpack', function ($viewModpack) use ($modpack) {
            return $viewModpack->id == $modpack->id;
        });
    }

    /** @test */
    public function guest_cannot_view_modpack()
    {
        factory(Modpack::class)->create([
            'slug' => 'example-modpack',
        ]);

        $response = $this->get('/modpacks/example-modpack');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function a_user_cannot_view_a_non_existent_modpack()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/modpacks/fake-modpack');

        $response->assertStatus(404);
    }

    /** @test */
    public function modpack_includes_builds_in_reverse_chronological_order()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create([
            'slug' => 'example-modpack',
        ]);
        $buildA = BuildFactory::createForModpack($modpack, ['created_at' => Carbon::parse('3 days ago')]);
        $buildB = BuildFactory::createForModpack($modpack, ['created_at' => Carbon::parse('2 days ago')]);
        $buildC = BuildFactory::createForModpack($modpack, ['created_at' => Carbon::parse('1 days ago')]);

        $response = $this->actingAs($user)->get('/modpacks/example-modpack');

        $response->data('modpack')->builds->assertEquals([
            $buildC,
            $buildB,
            $buildA,
        ]);
    }

    /** @test */
    public function modpack_includes_list_of_users()
    {
        $userA = factory(User::class)->create();
        $userB = factory(User::class)->create();
        $userC = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create([
            'slug' => 'example-modpack',
        ]);

        $response = $this->actingAs($userA)->get('/modpacks/example-modpack');

        $response->data('users')->assertEquals([
            $userA,
            $userB,
            $userC,
        ]);
    }
}
