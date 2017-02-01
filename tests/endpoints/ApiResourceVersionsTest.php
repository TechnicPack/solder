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

use App\User;
use App\Resource;
use App\Version;

class ApiResourceVersionsTest extends TestCase
{
    use MakesJsonApiRequests;

    /** @test */
    public function a_user_can_list_all_versions_of_a_resource()
    {
        $resource = factory(Resource::class)->create();
        $version = factory(Version::class)->create(['resource_id' => $resource->id]);

        $this->listResourceVersions($resource);

        $this->assertResponseOk();
        $this->seeVersionCollection($version);
    }

    /** @test */
    public function a_user_can_post_a_new_version()
    {
        $resource = factory(Resource::class)->create();

        $this->createVersion($resource, [
            'version' => '1.0.0',
        ]);

        $this->assertStatus(201);
        $this->seeVersion(null, [
            'version' => '1.0.0',
        ]);
    }

    /** @test */
    public function it_lists_relationships_data()
    {
        $resource = factory(Resource::class)->create();
        $version = factory(Version::class)->create(['resource_id' => $resource->id]);

        $this->listResourceVersionsRelationship($resource);

        $this->assertResponseOk();
        $this->seeVersionCollection($version);
        $this->assertJson([
            'links' => [
                'self' => trim(config('app.url'), '/').'/api/resources/'.$resource->id.'/relationships/versions',
                'related' => trim(config('app.url'), '/').'/api/resources/'.$resource->id.'/versions',
            ],
        ]);
    }

    /** @test */
    public function it_returns_a_401_when_processing_a_get_request_for_relationships_without_authentication()
    {
        $resource = factory(Resource::class)->create();

        $this->listResourceVersionsRelationship($resource, true);

        $this->seeJsonApiError(401, 'Unauthorized');
    }

    /** @test */
    public function it_returns_a_401_when_processing_a_get_request_for_versions_without_authentication()
    {
        $resource = factory(Resource::class)->create();

        $this->listResourceVersions($resource, true);

        $this->seeJsonApiError(401, 'Unauthorized');
    }

    /** @test */
    public function it_returns_a_401_when_processing_a_post_request_without_authentication()
    {
        $resource = factory(Resource::class)->create();

        $this->createVersion($resource, [], true);

        $this->seeJsonApiError(401, 'Unauthorized');
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_post_request_if_the_version_is_blank()
    {
        $resource = factory(Resource::class)->create();

        $this->createVersion($resource, [
            'version' => null,
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/version']);
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_post_request_if_the_version_is_not_unique_for_the_modpack()
    {
        $resource = factory(Resource::class)->create();
        factory(Version::class)->create(['resource_id' => $resource->id, 'version' => '1.0.0']);

        $this->createVersion($resource, [
            'version' => '1.0.0',
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/version']);
    }

    /** @test */
    public function posting_a_version_with_the_same_version_attribute_as_a_different_resource_version_is_ok()
    {
        $resource = factory(Resource::class)->create();
        factory(Version::class)->create(['version' => '1.0.0']);

        $this->createVersion($resource, [
            'version' => '1.0.0',
        ]);

        $this->assertStatus(201);
        $this->seeVersion(null, [
            'version' => '1.0.0',
        ]);
    }

    /* ================= Test Helpers ================= */

    /**
     * Send a GET request to the modpack index.
     *
     * @param $modpack
     * @param bool $asGuest
     */
    protected function listResourceVersions($modpack, $asGuest = false)
    {
        if (! $asGuest) {
            $user = factory(User::class)->create();
            $this->actingAs($user, 'api');
        }

        $this->getJsonApi('/api/resources/'.$modpack->id.'/versions');
    }

    protected function createVersion($resource, $attributes, $asGuest = false)
    {
        if (! $asGuest) {
            $user = factory(User::class)->create();
            $this->actingAs($user, 'api');
        }

        $data = ['data' => [
            'type' => 'build',
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

        $this->postJsonApi('/api/resources/'.$resource->id.'/versions', $data);
    }

    protected function seeVersion($version, $attributes = null)
    {
        $data = [
            'type' => 'version',
        ];

        if ($version !== null) {
            $data['id'] = $version->id;
        }

        if ($attributes !== null) {
            $data['attributes'] = $attributes;
        }

        $this->assertJson(['data' => $data]);
    }

    protected function seeVersionCollection()
    {
        if (count(func_get_args()) == 0) {
            throw new exception('At least one resource must be provided.');
        }

        $data = [];

        foreach (func_get_args() as $version) {
            $data[] = [
                'type' => 'version',
                'id' => $version->id,
            ];
        }

        $this->assertJson(['data' => $data]);
    }

    /**
     * Send a GET request to the modpack relationships build endpoint.
     *
     * @param $resource
     * @param bool $asGuest
     */
    protected function listResourceVersionsRelationship($resource, $asGuest = false)
    {
        if (! $asGuest) {
            $user = factory(User::class)->create();
            $this->actingAs($user, 'api');
        }

        $this->getJsonApi('api/resources/'.$resource->id.'/relationships/versions');
    }
}
