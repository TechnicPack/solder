<?php
/*
 * This file is part of solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Api;

use App\User;
use App\Build;
use App\Modpack;
use Tests\TestCase;

class BuildsTest extends TestCase
{
    use ApiActions;

    /** @test */
    public function builds_can_be_browsed()
    {
        factory(Build::class)->create();

        $response = $this->getApi('api/builds');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }

    /** @test */
    public function a_build_can_be_read()
    {
        $build = factory(Build::class)->create();

        $response = $this->getApi('api/builds/'.$build->id);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }

    /** @test */
    public function a_build_can_be_edited()
    {
        $user = factory(User::class)->create();
        $build = factory(Build::class)->create([
            'version' => '1.0.0',
        ]);

        $response = $this->actingAs($user, 'api')->patchApi('api/builds/'.$build->id, [
            'data' => [
                'type' => 'build',
                'id' => $build->id,
                'attributes' => [
                    'version' => '1.0.1',
                ],
            ],
        ]);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $this->assertDatabaseHas('builds', [
            'id' => $build->id,
            'version' => '1.0.1',
        ]);
    }

    /** @test */
    public function a_build_can_be_added()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create();

        $response = $this->actingAs($user, 'api')->postApi('api/builds', [
            'data' => [
                'type' => 'build',
                'attributes' => [
                    'version' => '1.0.0',
                    'game_version' => '1.0.0',
                ],
            ],
            'relationships' => [
                'modpack' => [
                    'data' => [
                        'type' => 'modpack',
                        'id' => $modpack->id,
                    ],
                ],
            ],
        ]);

        $response->assertStatus(201);
        $response->assertHeader('Location');
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $this->assertDatabaseHas('builds', [
            'version' => '1.0.0',
            'game_version' => '1.0.0',
            'modpack_id' => $modpack->id,
        ]);
    }

    /** @test */
    public function a_build_can_be_promoted()
    {
        $user = factory(User::class)->create();
        $build = factory(Build::class)->create();

        $response = $this->actingAs($user, 'api')->postApi('api/builds/'.$build->id.'/promote');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $this->assertDatabaseHas('builds', [
            'id' => $build->id,
            'is_promoted' => true,
        ]);
    }

    /** @test */
    public function a_build_can_be_destroyed()
    {
        $build = factory(Build::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->deleteApi('api/builds/'.$build->id);


        $response->assertStatus(204);
    }

    /** @test */
    public function version_is_required()
    {
        $modpack = factory(Modpack::class)->create();
        $user = factory(User::class)->create();

        // Create
        $response = $this->actingAs($user, 'api')->postApi('api/builds', [
            'data' => [
                'type' => 'build',
                'attributes' => [
                    'game_version' => '1.0.0',
                ],
            ],
            'relationships' => [
                'modpack' => [
                    'data' => [
                        'type' => 'modpack',
                        'id' => $modpack->id,
                    ],
                ],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');

        // Edit
        $build = factory(Build::class)->create();
        $response = $this->actingAs($user, 'api')->patchApi('api/builds/'.$build->id, [
            'data' => [
                'type' => 'build',
                'id' => $build->id,
                'attributes' => [
                    'version' => '',
                ],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }

    /** @test */
    public function game_version_is_required()
    {
        $modpack = factory(Modpack::class)->create();
        $user = factory(User::class)->create();

        // Create
        $response = $this->actingAs($user, 'api')->postApi('api/builds', [
            'data' => [
                'type' => 'build',
                'attributes' => [
                    'version' => '1.0.0',
                ],
            ],
            'relationships' => [
                'modpack' => [
                    'data' => [
                        'type' => 'modpack',
                        'id' => $modpack->id,
                    ],
                ],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');

        // Edit
        $build = factory(Build::class)->create();
        $response = $this->actingAs($user, 'api')->patchApi('api/builds/'.$build->id, [
            'data' => [
                'type' => 'build',
                'id' => $build->id,
                'attributes' => [
                    'game_version' => '',
                ],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }

    /** @test */
    public function modpack_relationship_is_required()
    {
        $user = factory(User::class)->create();

        // Create
        $response = $this->actingAs($user, 'api')->postApi('api/builds', [
            'data' => [
                'type' => 'build',
                'attributes' => [
                    'version' => '1.0.0',
                    'game_version' => '1.0.0',
                ],
            ],
            'relationships' => [
                // No defined relationship
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }

    /** @test */
    public function arguments_must_be_an_array()
    {
        $modpack = factory(Modpack::class)->create();
        $user = factory(User::class)->create();

        // Create
        $response = $this->actingAs($user, 'api')->postApi('api/builds', [
            'data' => [
                'type' => 'build',
                'attributes' => [
                    'version' => '1.0.0',
                    'game_version' => '1.0.0',
                    'arguments' => 'sentence',
                ],
            ],
            'relationships' => [
                'modpack' => [
                    'data' => [
                        'type' => 'modpack',
                        'id' => $modpack->id,
                    ],
                ],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');

        // Edit
        $build = factory(Build::class)->create();
        $response = $this->actingAs($user, 'api')->patchApi('api/builds/'.$build->id, [
            'data' => [
                'type' => 'build',
                'id' => $build->id,
                'attributes' => [
                    'arguments' => 'sentence',
                ],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }

    /** @test */
    public function privacy_must_be_valid()
    {
        $modpack = factory(Modpack::class)->create();
        $user = factory(User::class)->create();

        // Create
        $response = $this->actingAs($user, 'api')->postApi('api/builds', [
            'data' => [
                'type' => 'build',
                'attributes' => [
                    'version' => '1.0.0',
                    'game_version' => '1.0.0',
                    'privacy' => 'fake',
                ],
            ],
            'relationships' => [
                'modpack' => [
                    'data' => [
                        'type' => 'modpack',
                        'id' => $modpack->id,
                    ],
                ],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');

        // Edit
        $build = factory(Build::class)->create();
        $response = $this->actingAs($user, 'api')->patchApi('api/builds/'.$build->id, [
            'data' => [
                'type' => 'build',
                'id' => $build->id,
                'attributes' => [
                    'privacy' => 'false',
                ],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }
}
