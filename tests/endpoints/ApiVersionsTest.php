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
use App\Version;
use App\Resource;

class ApiVersionsTest extends TestCase
{
    use MakesJsonApiRequests;

    /** @test */
    public function a_user_can_list_versions()
    {
        $version = factory(Version::class)->create();

        $this->listVersions();

        $this->assertResponseOk();
        $this->seeVersionCollection($version);
    }

    /** @test */
    public function a_user_can_view_a_version()
    {
        $version = factory(Version::class)->create();

        $this->showVersion($version);

        $this->assertResponseOk();
        $this->seeVersion($version);
    }

    /** @test */
    public function a_user_can_update_a_version()
    {
        $version = factory(Version::class)->create([
            'version' => '1.0.0',
        ]);

        $this->updateVersion($version, [
            'version' => '1.0.1',
        ]);

        $this->assertStatus(200);
        $this->seeVersion($version, [
            'version' => '1.0.1',
        ]);
    }

    /** @test */
    public function a_user_can_delete_a_version()
    {
        $version = factory(Version::class)->create();

        $this->deleteVersion($version);

        $this->assertStatus(204);
        $this->assertEmpty($this->response->getContent());
    }


    /** @test */
    public function it_returns_a_401_if_a_guest_tries_to_list_versions()
    {
        $this->listVersions(true);

        $this->seeJsonApiError(401, 'Unauthorized');
    }

    /** @test */
    public function it_returns_a_401_if_a_guest_tries_to_get_a_version()
    {
        $version = factory(Version::class)->create();

        $this->showVersion($version, true);

        $this->seeJsonApiError(401, 'Unauthorized');
    }

    /** @test */
    public function it_returns_a_401_if_a_guest_tries_to_patch_a_version()
    {
        $resource = factory(Resource::class)->create();

        $this->updateVersion($resource, [], true);

        $this->seeJsonApiError(401, 'Unauthorized');
    }

    /** @test */
    public function it_returns_a_401_if_a_guest_tries_to_delete_a_version()
    {
        $resource = factory(Resource::class)->create();

        $this->deleteVersion($resource, true);

        $this->seeJsonApiError(401, 'Unauthorized');
    }

    /** @test */
    public function it_returns_a_404_when_processing_a_get_request_if_the_version_does_not_exist()
    {
        $invalidVersion = new Version();
        $invalidVersion->id = 'invalid-id';

        $this->showVersion($invalidVersion);

        $this->seeJsonApiError(404, 'Not Found');
    }

    /** @test */
    public function it_returns_a_404_when_processing_a_patch_request_if_the_version_does_not_exist()
    {
        $invalidVersion = new Version();
        $invalidVersion->id = 'invalid-id';

        $this->updateVersion($invalidVersion, []);

        $this->seeJsonApiError(404, 'Not Found');
    }

    /** @test */
    public function it_returns_a_404_when_processing_a_delete_request_if_the_version_does_not_exist()
    {
        $invalidVersion = new Version();
        $invalidVersion->id = 'invalid-id';

        $this->deleteVersion($invalidVersion);

        $this->seeJsonApiError(404, 'Not Found');
    }

    /** @test */
    public function it_returns_a_409_when_processing_a_patch_request_and_the_resource_id_does_not_match_the_servers_endpoint()
    {
        $version = factory(Version::class)->create();

        $this->updateVersion($version, [
            'id' => 'wrong-id',
        ]);

        $this->seeJsonApiError(409);
    }

    /** @test */
    public function it_returns_a_409_when_processing_a_patch_request_and_the_resource_type_does_not_match_the_servers_endpoint()
    {
        $version = factory(Version::class)->create();

        $this->updateVersion($version, [
            'type' => 'wrong-type',
        ]);

        $this->seeJsonApiError(409);
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_patch_request_if_the_version_is_blank()
    {
        $version = factory(Version::class)->create();

        $this->updateVersion($version, [
            'version' => '',
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/version']);
    }

    /* ================= Test Helpers ================= */

    /**
     * Send a GET request to the version index.
     *
     * @param bool $asGuest
     */
    protected function listVersions($asGuest = false)
    {
        if (! $asGuest) {
            $user = factory(User::class)->create();
            $this->actingAs($user, 'api');
        }

        $this->getJsonApi('/api/versions');
    }

    /**
     * Send a GET request to the modpack endpoint with the given modpack id.
     *
     * @param $version
     * @param bool $asGuest
     */
    protected function showVersion($version, $asGuest = false)
    {
        if (! $asGuest) {
            $user = factory(User::class)->create();
            $this->actingAs($user, 'api');
        }

        $this->getJsonApi('/api/versions/'.$version->id);
    }

    protected function updateVersion($version, $attributes, $asGuest = false)
    {
        if (! $asGuest) {
            $user = factory(User::class)->create();
            $this->actingAs($user, 'api');
        }

        $data = ['data' => [
            'type' => 'version',
            'id' => $version->id,
            'attributes' => $attributes,
        ]];

        if (isset($attributes['type'])) {
            $data['data']['type'] = $attributes['type'];
            unset($data['data']['attributes']['type']);
        }

        if (isset($attributes['id'])) {
            $data['data']['id'] = $attributes['id'];
            unset($data['data']['attributes']['id']);
        }

        $this->patchJsonApi('/api/versions/'.$version->id, $data);
    }

    protected function deleteVersion($version, $asGuest = false)
    {
        if (! $asGuest) {
            $user = factory(User::class)->create();
            $this->actingAs($user, 'api');
        }

        $this->deleteJsonApi('/api/versions/'.$version->id);
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
            throw new exception('At least one version must be provided.');
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
}
