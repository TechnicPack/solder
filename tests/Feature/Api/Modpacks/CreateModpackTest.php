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
use App\Facades\Uuid;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateModpackTest extends TestCase
{
    use DatabaseMigrations;

    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        \Config::set('app.url', 'http://example.com');
    }

    /** @test */
    public function adding_a_new_modpack()
    {
        Uuid::shouldReceive('generate')->andReturn('000000000-0000-4000-A000-000000000000');

        $response = $this->actingAs($this->user, 'api')->postJson('api/modpacks', $this->validParams());

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

        $response = $this->postJson('api/modpacks', $this->validParams());

        $response->assertStatus(401);
        $this->assertEquals(0, Modpack::count());
    }

    /** @test */
    public function name_is_required()
    {
        $this->withExceptionHandling();

        $response = $this->actingAs($this->user, 'api')->postJson('api/modpacks', $this->validParams([
            'name' => null,
        ]));

        $response->assertStatus(422);
        $this->assertEquals(0, Modpack::count());
    }

    /** @test */
    public function slug_must_be_unique()
    {
        $this->withExceptionHandling();
        factory(Modpack::class)->create(['slug' => 'existing-slug']);
        $this->assertEquals(1, Modpack::count());

        $response = $this->actingAs($this->user, 'api')->postJson('api/modpacks', $this->validParams([
            'slug' => 'existing-slug',
        ]));

        $response->assertStatus(422);
        $this->assertEquals(1, Modpack::count());
    }

    /**
     * @param array $overrides
     *
     * @return array
     */
    private function validParams($overrides = []): array
    {
        $attributes = array_merge([
            'name' => 'My First Modpack',
            'slug' => 'my-first-modpack',
            'status' => 'public',
        ], $overrides);

        return [
            'data' => [
                'type' => 'modpack',
                'attributes' => $attributes,
            ],
        ];
    }
}
