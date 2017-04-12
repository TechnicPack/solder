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
use App\Facades\Uuid;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateBuildWithModpackTest extends TestCase
{
    use DatabaseMigrations;

    protected $modpack;
    protected $user;

    public function setUp()
    {
        parent::setUp();

        \Config::set('app.url', 'http://example.com');
        $this->modpack = factory(Modpack::class)->create();
        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function create_a_build_on_modpack_endpoint()
    {
        Uuid::shouldReceive('generate')->andReturn('000000000-0000-4000-A000-000000000000');

        $response = $this->actingAs($this->user)->postJson($this->validEndpoint(), [
            'data' => [
                'type' => 'build',
                'attributes' => [
                    'build_number' => '1.0.0',
                    'minecraft_version' => '1.7.10',
                    'state' => 'public',
                    'arguments' => [
                        'sample_argument' => 'some-data',
                    ],
                ],
            ],
            'relationships' => [
                'modpack' => [
                    'data' => ['type' => 'modpack', 'id' => $this->modpack->id],
                ],
            ],
        ]);

        $response->assertStatus(201);
        $response->assertHeader('Location', 'http://example.com/api/builds/000000000-0000-4000-A000-000000000000');
        $response->assertJson([
            'data' => [
                'type' => 'build',
                'id' => '000000000-0000-4000-A000-000000000000',
                'attributes' => [
                    'build_number' => '1.0.0',
                    'minecraft_version' => '1.7.10',
                    'state' => 'public',
                    'arguments' => [
                        'sample_argument' => 'some-data',
                    ],
                ],
                'links' => [
                    'self' => 'http://example.com/api/builds/000000000-0000-4000-A000-000000000000',
                ],
            ],
        ]);
        $this->assertDatabaseHas('builds', [
            'build_number' => '1.0.0',
            'modpack_id' => $this->modpack->id,
        ]);
    }

    /** @test */
    public function modpack_endpoint_requires_authentication()
    {
        $this->withExceptionHandling();

        $response = $this->postJson($this->validEndpoint(), $this->validParams());

        $response->assertStatus(401);
        $this->assertEquals(0, Build::count());
    }

    /** @test */
    public function build_number_is_required()
    {
        $this->withExceptionHandling();

        $response = $this->actingAs($this->user)->postJson($this->validEndpoint(), $this->validParams([
            'build_number' => null,
        ]));

        $response->assertStatus(422);
        $this->assertEquals(0, Build::count());
    }

    /** @test */
    public function build_number_must_be_unique_at_endpoint()
    {
        $this->withExceptionHandling();
        factory(Build::class)->create([
            'modpack_id' => $this->modpack->id,
            'build_number' => '1.0.0',
        ]);

        $response = $this->actingAs($this->user)->postJson($this->validEndpoint(), $this->validParams([
            'build_number' => '1.0.0',
        ]));

        $response->assertStatus(422);
        $this->assertEquals(1, Build::count());
    }

    /** @test */
    public function minecraft_version_is_required()
    {
        $this->withExceptionHandling();

        $response = $this->actingAs($this->user)->postJson($this->validEndpoint(), $this->validParams([
            'minecraft_version' => null,
        ]));

        $response->assertStatus(422);
        $this->assertEquals(0, Build::count());
    }

    /** @test */
    public function related_modpack_is_required()
    {
        $this->withExceptionHandling();

        $response = $this->actingAs($this->user)->postJson($this->validEndpoint(), [
            'data' => [
                'type' => 'build',
                'attributes' => [
                    'build_number' => '1.0.0',
                    'minecraft_version' => '1.7.10',
                    'state' => 'public',
                    'arguments' => [
                        'sample_argument' => 'some-data',
                    ],
                ],
            ],
        ]);

        $response->assertStatus(422);
        $this->assertEquals(0, Build::count());
    }

    /** @test */
    public function related_modpack_must_exist()
    {
        $this->withExceptionHandling();

        $response = $this->actingAs($this->user)->postJson($this->validEndpoint(), [
            'data' => [
                'type' => 'build',
                'attributes' => [
                    'build_number' => '1.0.0',
                    'minecraft_version' => '1.7.10',
                    'state' => 'public',
                    'arguments' => [
                        'sample_argument' => 'some-data',
                    ],
                ],
            ],
            'relationships' => [
                'modpack' => [
                    'data' => ['type' => 'modpack', 'id' => 'non-existent-modpack'],
                ],
            ],
        ]);

        $response->assertStatus(404);
        $this->assertEquals(0, Build::count());
    }

    /**
     * @param array $overrides
     *
     * @return array
     */
    private function validParams($overrides = [])
    {
        $attributes = array_merge([
            'build_number' => '1.0.0',
            'minecraft_version' => '1.7.11',
            'state' => 'public',
            'arguments' => [
                'sample_argument' => 'some-data',
            ],
        ], $overrides);

        return [
            'data' => [
                'type' => 'build',
                'attributes' => $attributes,
            ],
            'relationships' => [
                'modpack' => [
                    'data' => ['type' => 'modpack', 'id' => $this->modpack->id],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    private function validEndpoint()
    {
        return 'api/builds';
    }
}
