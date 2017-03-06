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
use App\Modpack;
use Tests\TestCase;

class ViewModpackTest extends TestCase
{
    /** @test */
    public function it_lists_modpacks()
    {
        $user = factory(User::class)->create();
        factory(Modpack::class)->create([
            'name' => 'Example Modpack',
        ]);

        $response = $this->actingAs($user)->get('/modpacks');

        $response->assertStatus(200)
                 ->assertSee('Example Modpack');
    }

    /** @test */
    public function it_shows_a_modpack()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create([
            'name' => 'Example Modpack',
        ]);

        $response = $this->actingAs($user)->get('/modpacks/'.$modpack->id);

        $response->assertStatus(200);
        $response->assertViewHas('modpack', function ($viewModpack) use ($modpack) {
            return $viewModpack->id === $modpack->id;
        });
    }

    /** @test */
    public function modpack_can_be_created()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post('/modpacks/', [
            'name' => 'Test Modpack',
        ]);

        $modpack = Modpack::latest()->first();
        $response->assertRedirect('/modpacks/'.$modpack->id);
        $this->assertDatabaseHas('modpacks', ['name' => 'Test Modpack']);
    }

    /** @test */
    public function modpack_can_be_updated()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create();

        $response = $this->actingAs($user)->patch('/modpacks/'.$modpack->id, [
            'name' => 'Revised Modpack',
        ]);

        $response->assertRedirect('/modpacks/'.$modpack->id);
        $this->assertDatabaseHas('modpacks', ['name' => 'Revised Modpack']);
    }

    /** @test */
    public function modpack_can_be_deleted()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create();

        $response = $this->actingAs($user)->delete('/modpacks/'.$modpack->id);

        $response->assertRedirect('/modpacks');
        $this->assertDatabaseMissing('modpacks', ['id' => $modpack->id]);
    }
}
