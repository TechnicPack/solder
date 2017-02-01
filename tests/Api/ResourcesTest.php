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
use App\User;
use Tests\TestCase;

class ResourcesTest extends TestCase
{
    use ApiActions;

    /** @test */
    public function resources_can_be_browsed()
    {
        factory(Resource::class)->create();

        $response = $this->getApi('api/resources');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }

    /** @test */
    public function a_resource_can_be_read()
    {
        $resource = factory(Resource::class)->create();

        $response = $this->getApi('api/resources/'.$resource->id);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }

    /** @test */
    public function a_resources_versions_can_be_browsed()
    {
        $resource = factory(Resource::class)->create();

        $response = $this->getApi('api/resources/'.$resource->id.'/versions');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }

    /** @test */
    public function the_relationship_between_resources_and_versions_can_be_read()
    {
        $resource = factory(Resource::class)->create();

        $response = $this->getApi('api/resources/'.$resource->id.'/relationships/versions');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }

    /** @test */
    public function a_resource_can_be_edited()
    {
        $user = factory(User::class)->create();
        $resource = factory(Resource::class)->create([
            'name' => 'original',
        ]);

        $response = $this->actingAs($user, 'api')->patchApi('api/resources/'.$resource->id, [
            'data' => [
                'type' => 'resource',
                'id' => $resource->id,
                'attributes' => [
                    'name' => 'revised',
                ],
            ],
        ]);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $this->assertDatabaseHas('resources', [
            'id' => $resource->id,
            'name' => 'revised',
        ]);
    }

    /** @test */
    public function a_resource_can_be_added()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->postApi('api/resources', [
            'data' => [
                'type' => 'resource',
                'attributes' => [
                    'name' => 'Resource',
                ],
            ],
        ]);

        $response->assertStatus(201);
        $response->assertHeader('Location');
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $this->assertDatabaseHas('resources', [
            'name' => 'Resource',
        ]);
    }

    /** @test */
    public function a_version_can_be_added_to_a_resource()
    {
        $resource = factory(Resource::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->postApi('api/resources/'.$resource->id.'/versions', [
            'data' => [
                'type' => 'version',
                'attributes' => [
                    'version' => '1.0.0',
                ],
            ],
        ]);

        $response->assertStatus(201);
        $response->assertHeader('Location');
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $this->assertDatabaseHas('versions', [
            'version' => '1.0.0',
            'resource_id' => $resource->id,
        ]);
    }

    /** @test */
    public function a_resource_can_be_destroyed()
    {
        $resource = factory(Resource::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->deleteApi('api/resources/'.$resource->id);

        $response->assertStatus(204);
    }

    /** @test */
    public function name_is_required()
    {
        $user = factory(User::class)->create();

        // Create
        $response = $this->actingAs($user, 'api')->postApi('api/resources', [
            'data' => [
                'type' => 'resource',
                'attributes' => [
                    // no name attribute
                ],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }

    /** @test */
    public function name_cannot_be_blank()
    {
        $user = factory(User::class)->create();

        // Create
        $response = $this->actingAs($user, 'api')->postApi('api/resources', [
            'data' => [
                'type' => 'resource',
                'attributes' => [
                    'name' => '',
                ],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');

        // Edit
        $resource = factory(Resource::class)->create();
        $response = $this->actingAs($user, 'api')->patchApi('api/resources/'.$resource->id, [
            'data' => [
                'type' => 'resource',
                'id' => $resource->id,
                'attributes' => [
                    'name' => '',
                ],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }

    /** @test */
    public function slug_must_be_unique()
    {
        $user = factory(User::class)->create();
        factory(Resource::class)->create([
            'slug' => 'test-resource',
        ]);

        // Create
        $response = $this->actingAs($user, 'api')->postApi('api/resources', [
            'data' => [
                'type' => 'resource',
                'attributes' => [
                    'name' => 'Test Resource',
                    'slug' => 'test-resource',
                ],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');

        // Edit
        $resource = factory(Resource::class)->create();
        $response = $this->actingAs($user, 'api')->patchApi('api/resources/'.$resource->id, [
            'data' => [
                'type' => 'resource',
                'id' => $resource->id,
                'attributes' => [
                    'slug' => 'test-resource',
                ],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }

    /** @test */
    public function slug_cannot_be_blank()
    {
        $user = factory(User::class)->create();

        // Create
        $response = $this->actingAs($user, 'api')->postApi('api/resources', [
            'data' => [
                'type' => 'resource',
                'attributes' => [
                    'name' => 'Test Resource',
                    'slug' => '',
                ],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');

        // Edit
        $resource = factory(Resource::class)->create();
        $response = $this->actingAs($user, 'api')->patchApi('api/resources/'.$resource->id, [
            'data' => [
                'type' => 'resource',
                'id' => $resource->id,
                'attributes' => [
                    'slug' => '',
                ],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }

    /** @test */
    public function slug_must_be_alphanumeric()
    {
        $user = factory(User::class)->create();

        // Create
        $response = $this->actingAs($user, 'api')->postApi('api/resources', [
            'data' => [
                'type' => 'resource',
                'attributes' => [
                    'name' => 'Test Resource',
                    'slug' => 'invalid slug!',
                ],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');

        // Edit
        $resource = factory(Resource::class)->create();
        $response = $this->actingAs($user, 'api')->patchApi('api/resources/'.$resource->id, [
            'data' => [
                'type' => 'resource',
                'id' => $resource->id,
                'attributes' => [
                    'slug' => 'invalid slug!',
                ],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }
}
