<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Feature\Api\Modpacks;

use App\User;
use App\Modpack;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DeleteModpackTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function delete_modpack()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $modpack = factory(Modpack::class)->create();
        $this->assertEquals(1, Modpack::count());

        $response = $this->deleteJson("api/modpacks/{$modpack->id}");

        $response->assertStatus(204);
        $this->assertEquals(0, Modpack::count());
    }

    /** @test */
    public function requires_authentication()
    {
        $this->withExceptionHandling();
        $modpack = factory(Modpack::class)->create();
        $this->assertEquals(1, Modpack::count());

        $response = $this->deleteJson("api/modpacks/{$modpack->id}");

        $response->assertStatus(401);
        $this->assertEquals(1, Modpack::count());
    }
}
