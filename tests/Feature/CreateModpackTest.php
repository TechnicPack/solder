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
use App\Modpack;
use Tests\TestCase;
use App\Facades\Uuid;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateModpackTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function adding_a_new_modpack()
    {
        \Config::set('app.url', 'http://example.com');
        Uuid::shouldReceive('generate')->andReturn('000000000-0000-4000-A000-000000000000');
        $this->actingAs(factory(User::class)->create());

        $response = $this->json('POST', 'api/modpacks', $this->validParams());

        $response->assertStatus(201);
        $response->assertHeader('Location', 'http://example.com/api/modpacks/000000000-0000-4000-A000-000000000000');
        $response->assertJson([
            'data' => [
                'type' => 'modpack',
                'id' => '000000000-0000-4000-A000-000000000000',
                'attributes' => [
                    'name' => 'My First Modpack',
                    'slug' => 'my-first-modpack',
                    'status' => 'public',
                ],
                'links' => [
                    'self' => 'http://example.com/api/modpacks/000000000-0000-4000-A000-000000000000',
                ],
            ],
        ]);
        $this->assertDatabaseHas('modpacks', [
            'name' => 'My First Modpack',
            'slug' => 'my-first-modpack',
        ]);
    }

    /** @test */
    public function requires_authentication()
    {
        $this->withExceptionHandling();

        $response = $this->json('POST', 'api/modpacks', $this->validParams());

        $response->assertStatus(401);
        $this->assertEquals(0, Modpack::count());
    }

    /**
     * @param array $overrides
     *
     * @return array
     */
    private function validParams($overrides = []): array
    {
        return array_merge([
            'data' => [
                'type' => 'modpack',
                'attributes' => [
                    'name' => 'My First Modpack',
                    'slug' => 'my-first-modpack',
                    'status' => 'public',
                ],
            ],
        ], $overrides);
    }
}
