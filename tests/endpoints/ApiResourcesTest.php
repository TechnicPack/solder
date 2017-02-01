<?php

namespace Tests\endpoints;

/*
 * This file is part of TechnicSolder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use App\Resource;
use App\User;

class ApiResourcesTest extends TestCase
{
    use MakesJsonApiRequests;

    /** @test */
    public function a_user_can_list_resources()
    {
        $resource = factory(Resource::class)->create();

        $this->listResources();

        $this->assertResponseOk();
        $this->seeResourceCollection($resource);
    }

    /** @test */
    public function a_user_can_view_a_resource()
    {
        $resource = factory(Resource::class)->create();

        $this->showResource($resource);

        $this->assertResponseOk();
        $this->seeResource($resource);
    }

    /** @test */
    public function a_user_can_post_a_new_resource()
    {
        $this->createResource([
            'name' => 'Example Mod',
        ]);

        $this->assertStatus(201);
        $this->seeResource(null, [
            'name' => 'Example Mod',
        ]);
    }

    /** @test */
    public function a_user_can_update_a_resource()
    {
        $resource = factory(Resource::class)->create([
            'name' => 'Example Mod',
        ]);

        $this->updateResource($resource, [
            'name' => 'Updated Mod',
        ]);

        $this->assertStatus(200);
        $this->seeResource($resource, [
            'name' => 'Updated Mod',
        ]);
    }

    /** @test */
    public function a_user_can_delete_a_resource()
    {
        $resource = factory(Resource::class)->create();

        $this->deleteResource($resource);

        $this->assertStatus(204);
        $this->assertEmpty($this->response->getContent());
    }

    /** @test */
    public function it_allows_the_existing_slug_to_be_reused()
    {
        $resource = factory(Resource::class)->create([
            'slug' => 'test-slug',
        ]);

        $this->updateResource($resource, [
            'slug' => 'test-slug',
        ]);

        $this->assertStatus(200);
        $this->seeResource($resource, [
            'slug' => 'test-slug',
        ]);
    }

    /** @test */
    public function it_returns_a_401_if_a_guest_tries_to_list_resources()
    {
        $this->listResources(true);

        $this->seeJsonApiError(401, 'Unauthorized');
    }

    /** @test */
    public function it_returns_a_401_if_a_guest_tries_to_get_a_resource()
    {
        $resource = factory(Resource::class)->create();

        $this->showResource($resource, true);

        $this->seeJsonApiError(401, 'Unauthorized');
    }

    /** @test */
    public function it_returns_a_401_if_a_guest_tries_to_post_a_resource()
    {
        $this->createResource([], true);

        $this->seeJsonApiError(401, 'Unauthorized');
    }

    /** @test */
    public function it_returns_a_401_if_a_guest_tries_to_patch_a_resource()
    {
        $resource = factory(Resource::class)->create();

        $this->updateResource($resource, [], true);

        $this->seeJsonApiError(401, 'Unauthorized');
    }

    /** @test */
    public function it_returns_a_401_if_a_guest_tries_to_delete_a_resource()
    {
        $resource = factory(Resource::class)->create();

        $this->deleteResource($resource, true);

        $this->seeJsonApiError(401, 'Unauthorized');
    }

    /** @test */
    public function it_returns_a_404_when_processing_a_get_request_if_the_resource_does_not_exist()
    {
        $invalidResource = new Resource();
        $invalidResource->id = 'invalid-id';

        $this->showResource($invalidResource);

        $this->seeJsonApiError(404, 'Not Found');
    }

    /** @test */
    public function it_returns_a_404_when_processing_a_patch_request_if_the_modpack_does_not_exist()
    {
        $invalidResource = new Resource();
        $invalidResource->id = 'invalid-id';

        $this->updateResource($invalidResource, []);

        $this->seeJsonApiError(404, 'Not Found');
    }

    /** @test */
    public function it_returns_a_404_when_processing_a_delete_request_if_the_modpack_does_not_exist()
    {
        $invalidResource = new Resource();
        $invalidResource->id = 'invalid-id';

        $this->deleteResource($invalidResource);

        $this->seeJsonApiError(404, 'Not Found');
    }

    /** @test */
    public function it_returns_a_409_when_processing_a_patch_request_and_the_resource_id_does_not_match_the_servers_endpoint()
    {
        $resource = factory(Resource::class)->create();

        $this->updateResource($resource, [
            'id' => 'wrong-id',
        ]);

        $this->seeJsonApiError(409);
    }

    /** @test */
    public function it_returns_a_409_when_processing_a_patch_request_and_the_resource_type_does_not_match_the_servers_endpoint()
    {
        $resource = factory(Resource::class)->create();

        $this->updateResource($resource, [
            'type' => 'wrong-type',
        ]);

        $this->seeJsonApiError(409);
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_post_request_if_the_name_is_blank()
    {
        $this->createResource([
            'name' => '',
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/name']);
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_patch_request_if_the_name_is_blank()
    {
        $resource = factory(Resource::class)->create();

        $this->updateResource($resource, [
            'name' => '',
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/name']);
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_post_request_if_the_slug_is_blank()
    {
        $this->createResource([
            'name' => 'Test Mod',
            'slug' => '',
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/slug']);
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_patch_request_if_the_slug_is_blank()
    {
        $resource = factory(Resource::class)->create();

        $this->updateResource($resource, [
            'slug' => '',
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/slug']);
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_post_request_if_the_slug_is_not_url_safe()
    {
        $this->createResource([
            'name' => 'Test Mod',
            'slug' => 'slug with space and punctuation!',
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/slug']);
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_patch_request_if_the_slug_is_not_url_safe()
    {
        $resource = factory(Resource::class)->create();

        $this->updateResource($resource, [
            'slug' => 'slug with space and punctuation!',
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/slug']);
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_post_request_if_the_slug_is_not_unique()
    {
        factory(Resource::class)->create(['slug' => 'existing-slug']);

        $this->createResource([
            'name' => 'Test Mod',
            'slug' => 'existing-slug',
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/slug']);
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_patch_request_if_the_slug_is_not_unique()
    {
        factory(Resource::class)->create(['slug' => 'existing-slug']);
        $resource = factory(Resource::class)->create();

        $this->updateResource($resource, [
            'slug' => 'existing-slug',
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/slug']);
    }

    /* ================= Test Helpers ================= */

    /**
     * Send a GET request to the resource index.
     *
     * @param bool $asGuest
     */
    protected function listResources($asGuest = false)
    {
        if (! $asGuest) {
            $user = factory(User::class)->create();
            $this->actingAs($user, 'api');
        }

        $this->getJsonApi('/api/resources');
    }

    /**
     * Send a GET request to the modpack endpoint with the given modpack id.
     *
     * @param $resource
     * @param bool $asGuest
     */
    protected function showResource($resource, $asGuest = false)
    {
        if (! $asGuest) {
            $user = factory(User::class)->create();
            $this->actingAs($user, 'api');
        }

        $this->getJsonApi('/api/resources/'.$resource->id);
    }

    protected function createResource($attributes, $asGuest = false)
    {
        if (! $asGuest) {
            $user = factory(User::class)->create();
            $this->actingAs($user, 'api');
        }

        $data = ['data' => [
            'type' => 'resource',
            'attributes' => $attributes,
        ]];

        // Handle 'type' as a special attribute if present
        if (isset($attributes['type'])) {
            $data['data']['type'] = $attributes['type'];
            unset($data['data']['attributes']['type']);
        }

        // Handle 'id' as a special attribute if present
        if (isset($attributes['id'])) {
            $data['data']['id'] = $attributes['id'];
            unset($data['data']['attributes']['id']);
        }

        $this->postJsonApi('/api/resources', $data);
    }

    protected function updateResource($resource, $attributes, $asGuest = false)
    {
        if (! $asGuest) {
            $user = factory(User::class)->create();
            $this->actingAs($user, 'api');
        }

        $data = ['data' => [
            'type' => 'resource',
            'id' => $resource->id,
            'attributes' => $attributes,
        ]];

        if (isset($data['data']['attributes']['type'])) {
            $data['data']['type'] = $data['data']['attributes']['type'];
            unset($data['data']['attributes']['type']);
        }

        if (isset($data['data']['attributes']['id'])) {
            $data['data']['id'] = $data['data']['attributes']['id'];
            unset($data['data']['attributes']['id']);
        }

        $this->patchJsonApi('/api/resources/'.$resource->id, $data);
    }

    protected function deleteResource($resource, $asGuest = false)
    {
        if (! $asGuest) {
            $user = factory(User::class)->create();
            $this->actingAs($user, 'api');
        }

        $this->deleteJsonApi('/api/resources/'.$resource->id);
    }

    protected function seeResource($resource, $attributes = null)
    {
        $data = [
            'type' => 'resource',
        ];

        if ($resource !== null) {
            $data['id'] = $resource->id;
        }

        if ($attributes !== null) {
            $data['attributes'] = $attributes;
        }

        $this->assertJson(['data' => $data]);
    }

    protected function seeResourceCollection()
    {
        if (count(func_get_args()) == 0) {
            throw new exception('At least one resource must be provided.');
        }

        $data = [];

        foreach (func_get_args() as $resource) {
            $data[] = [
                'type' => 'resource',
                'id' => $resource->id,
            ];
        }

        $this->assertJson(['data' => $data]);
    }
}
