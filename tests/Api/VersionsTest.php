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

use App\Resource;
use App\Version;
use App\User;
use Tests\TestCase;

class VersionsTest extends TestCase
{
    use ApiActions;

    /** @test */
    public function versions_can_be_browsed()
    {
        factory(Version::class)->create();

        $response = $this->getApi('api/versions');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }

    /** @test */
    public function a_version_can_be_read()
    {
        $version = factory(Version::class)->create();

        $response = $this->getApi('api/versions/'.$version->id);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }

    /** @test */
    public function a_versions_assets_can_be_browsed()
    {
        $version = factory(Version::class)->create();

        $response = $this->getApi('api/versions/'.$version->id.'/assets');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }

    /** @test */
    public function the_relationship_between_versions_and_assets_can_be_read()
    {
        $version = factory(Version::class)->create();

        $response = $this->getApi('api/versions/'.$version->id.'/relationships/assets');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }

    /** @test */
    public function a_version_can_be_edited()
    {
        $user = factory(User::class)->create();
        $version = factory(Version::class)->create([
            'version' => '1.0.0',
        ]);

        $response = $this->actingAs($user, 'api')->patchApi('api/versions/'.$version->id, [
            'data' => [
                'type' => 'version',
                'id' => $version->id,
                'attributes' => [
                    'version' => '1.0.1',
                ],
            ],
        ]);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');

        $this->assertDatabaseHas('versions', [
            'id' => $version->id,
            'version' => '1.0.1',
        ]);
    }

    /** @test */
    public function a_version_can_be_added()
    {
        $resource = factory(Resource::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->postApi('api/versions', [
            'data' => [
                'type' => 'version',
                'attributes' => [
                    'version' => '1.0.0',
                ],
            ],
            'relationships' => [
                'resource' => [
                    'data' => [
                        'type' => 'resource',
                        'id' => $resource->id,
                    ],
                ],
            ],
        ]);

        $response->assertStatus(201);
        $response->assertHeader('Location');
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $this->assertDatabaseHas('versions', [
            'version' => '1.0.0',
        ]);
    }

    /** @test */
    public function an_asset_can_be_added_to_a_version()
    {
        $version = factory(Version::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->json('POST', 'api/versions/'.$version->id.'/assets', [
            'data' => [
                'type' => 'asset',
                'attributes' => [
                    'filename' => 'testfile.txt',
                ],
            ],
        ]);

        $response->assertStatus(201);
        $response->assertHeader('Location');
        $response->assertHeader('Content-Type', 'application/vnd.api+json');

        $this->assertDatabaseHas('assets', [
            'filename' => 'testfile.txt',
            'version_id' => $version->id,
        ]);
    }

    /** @test */
    public function a_version_can_be_destroyed()
    {
        $version = factory(Version::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->deleteApi('api/versions/'.$version->id);


        $response->assertStatus(204);
    }

    /** @test */
    public function version_is_required()
    {
        $resource = factory(Resource::class)->create();
        $user = factory(User::class)->create();

        // Create
        $response = $this->actingAs($user, 'api')->postApi('api/versions', [
            'data' => [
                'type' => 'version',
                'attributes' => [
                    // Missing version
                ],
            ],
            'relationships' => [
                'resource' => [
                    'data' => [
                        'type' => 'resource',
                        'id' => $resource->id,
                    ],
                ],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');

        // Edit
        $version = factory(Version::class)->create();
        $response = $this->actingAs($user, 'api')->patchApi('api/versions/'.$version->id, [
            'data' => [
                'type' => 'version',
                'id' => $version->id,
                'attributes' => [
                    'version' => '',
                ],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }

    /** @test */
    public function resource_relationship_is_required()
    {
        $user = factory(User::class)->create();

        // Create
        $response = $this->actingAs($user, 'api')->postApi('api/versions', [
            'data' => [
                'type' => 'version',
                'attributes' => [
                    'version' => '1.0.0',
                ],
            ],
            'relationships' => [
                // Missing relationship
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }
}
