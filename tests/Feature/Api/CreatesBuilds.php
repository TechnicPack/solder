<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Feature\Api;

use App\User;
use App\Build;
use App\Modpack;
use App\Facades\Uuid;

trait CreatesBuilds
{
    /**
     * Get the uri for creating a build.
     *
     * @param Modpack $modpack
     *
     * @return string
     */
    abstract public function validUri($modpack);

    /**
     * Get a valid creation payload.
     *
     * @param Modpack $modpack
     * @param array $attributes attributes overrides
     *
     * @return array
     */
    abstract public function validPayload($modpack, $attributes = []);

    /** @test */
    public function create_a_build()
    {
        $modpack = factory(Modpack::class)->create();
        $user = factory(User::class)->create();

        Uuid::shouldReceive('generate')->andReturn('000000000-0000-4000-A000-000000000000');

        $response = $this->actingAs($user, 'api')
            ->postJson($this->validUri($modpack), $this->validPayload($modpack));

        $response->assertStatus(201);
        $response->assertHeader('Location', url('/api/builds/000000000-0000-4000-A000-000000000000'));
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
                    'self' => url('/api/builds/000000000-0000-4000-A000-000000000000'),
                ],
            ],
        ]);
        $this->assertDatabaseHas('builds', [
            'build_number' => '1.0.0',
            'modpack_id' => $modpack->id,
        ]);
    }

    /** @test */
    public function requires_authentication()
    {
        $modpack = factory(Modpack::class)->create();
        $this->withExceptionHandling();

        $response = $this->postJson($this->validUri($modpack), $this->validPayload($modpack));

        $response->assertStatus(401);
        $this->assertEquals(0, Build::count());
    }

    /** @test */
    public function build_number_is_required()
    {
        $modpack = factory(Modpack::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->withExceptionHandling()
            ->postJson($this->validUri($modpack), $this->validPayload($modpack, [
                'build_number' => null,
            ]));

        $response->assertStatus(422);
        $this->assertEquals(0, Build::count());
    }

    /** @test */
    public function minecraft_version_is_required()
    {
        $modpack = factory(Modpack::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->withExceptionHandling()
            ->postJson($this->validUri($modpack), $this->validPayload($modpack, [
                'minecraft_version' => null,
            ]));

        $response->assertStatus(422);
        $this->assertEquals(0, Build::count());
    }

    /** @test */
    public function payload_must_be_a_build_resource()
    {
        $modpack = factory(Modpack::class)->create();
        $user = factory(User::class)->create();
        $invalidPayload = $this->validPayload($modpack);
        array_set($invalidPayload, 'data.type', 'foopack');

        $response = $this->actingAs($user, 'api')
            ->withExceptionHandling()
            ->postJson($this->validUri($modpack), $invalidPayload);

        $response->assertStatus(409);
        $this->assertEquals(0, Build::count());
    }

    /** @test */
    public function builds_do_not_support_client_generated_ids()
    {
        $modpack = factory(Modpack::class)->create();
        $user = factory(User::class)->create();
        $invalidPayload = $this->validPayload($modpack);
        array_set($invalidPayload, 'data.id', 'my-fancy-id');

        $response = $this->actingAs($user, 'api')
            ->withExceptionHandling()
            ->postJson($this->validUri($modpack), $invalidPayload);

        $response->assertStatus(403);
        $this->assertEquals(0, Build::count());
    }
}
