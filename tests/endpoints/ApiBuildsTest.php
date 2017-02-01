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

use App\Build;
use App\User;

class ApiBuildsTest extends TestCase
{
    use MakesJsonApiRequests;

    /** @test */
    public function a_guest_can_view_a_public_build()
    {
        $build = factory(Build::class)->states(['public'])->create();

        $this->showBuild($build, true);

        $this->assertResponseOk();
        $this->seeBuild($build);
    }

    /** @test */
    public function a_guest_can_view_an_unlisted_build()
    {
        $build = factory(Build::class)->states(['unlisted'])->create();
        $this->assertEquals('unlisted', $build->privacy);

        $this->showBuild($build);

        $this->assertResponseOk();
        $this->seeBuild($build);
    }

    /** @test */
    public function a_user_can_view_a_public_build()
    {
        $build = factory(Build::class)->states(['public'])->create();
        $this->assertEquals('public', $build->privacy);

        $this->showBUild($build);

        $this->assertResponseOk();
        $this->seeBuild($build);
    }

    /** @test */
    public function a_user_can_view_an_unlisted_build()
    {
        $build = factory(Build::class)->states(['unlisted'])->create();
        $this->assertEquals('unlisted', $build->privacy);

        $this->showBuild($build);

        $this->assertResponseOk();
        $this->seeBuild($build);
    }

    /** @test */
    public function a_user_can_view_a_private_modpack()
    {
        $build = factory(Build::class)->states(['private'])->create();
        $this->assertEquals('private', $build->privacy);

        $this->showBuild($build);

        $this->assertResponseOk();
        $this->seeBuild($build);
    }

    /** @test */
    public function a_user_can_update_a_build()
    {
        $build = factory(Build::class)->create([
            'version' => '1.0.0',
        ]);

        $this->updateBuild($build, [
            'version' => '1.0.1',
        ]);

        $this->assertStatus(200);
        $this->seeBuild($build, [
            'version' => '1.0.1',
        ]);
    }

    /** @test */
    public function it_allows_the_existing_version_to_be_reused()
    {
        $build = factory(Build::class)->create([
            'version' => '1.0.0',
        ]);

        $this->updateBuild($build, [
            'version' => '1.0.0',
        ]);

        $this->assertStatus(200);
        $this->seeBuild($build, [
            'version' => '1.0.0',
        ]);
    }

    /** @test */
    public function a_user_can_delete_a_build()
    {
        $build = factory(Build::class)->create();

        $this->deleteBuild($build);

        $this->assertStatus(204);
        $this->assertEmpty($this->response->getContent());
    }

    /** @test */
    public function it_returns_a_401_if_a_guest_tries_to_get_a_private_build()
    {
        $build = factory(Build::class)->states(['private'])->create();
        $this->assertEquals('private', $build->privacy);

        $this->showBuild($build, true);

        $this->seeJsonApiError(401, 'Unauthorized');
    }

    /** @test */
    public function it_returns_a_401_if_a_guest_tries_to_patch_a_build()
    {
        $build = factory(Build::class)->create();

        $this->updateBuild($build, [], true);

        $this->seeJsonApiError(401, 'Unauthorized');
    }

    /** @test */
    public function it_returns_a_401_if_a_guest_tries_to_delete_a_build()
    {
        $build = factory(Build::class)->create();

        $this->deleteBuild($build, true);

        $this->seeJsonApiError(401, 'Unauthorized');
    }

    /** @test */
    public function it_returns_a_404_when_processing_a_get_request_if_the_build_does_not_exist()
    {
        $invalidBuild = new Build();
        $invalidBuild->id = 'invalid-id';

        $this->showBuild($invalidBuild);

        $this->seeJsonApiError(404, 'Not Found');
    }

    /** @test */
    public function it_returns_a_404_when_processing_a_patch_request_if_the_build_does_not_exist()
    {
        $invalidBuild = new Build();
        $invalidBuild->id = 'invalid-id';

        $this->updateBuild($invalidBuild, []);

        $this->seeJsonApiError(404, 'Not Found');
    }

    /** @test */
    public function it_returns_a_404_when_processing_a_delete_request_if_the_build_does_not_exist()
    {
        $invalidBuild = new Build();
        $invalidBuild->id = 'invalid-id';

        $this->deleteBuild($invalidBuild);

        $this->seeJsonApiError(404, 'Not Found');
    }

    /** @test */
    public function it_returns_a_409_when_processing_a_patch_request_and_the_resource_id_does_not_match_the_servers_endpoint()
    {
        $build = factory(Build::class)->create();

        $this->updateBuild($build, [
            'id' => 'wrong-id',
        ]);

        $this->seeJsonApiError(409);
    }

    /** @test */
    public function it_returns_a_409_when_processing_a_patch_request_and_the_resource_type_does_not_match_the_servers_endpoint()
    {
        $build = factory(Build::class)->create();

        $this->updateBuild($build, [
            'type' => 'wrong-type',
        ]);

        $this->seeJsonApiError(409);
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_patch_request_if_the_version_is_blank()
    {
        $build = factory(Build::class)->create();

        $this->updateBuild($build, [
            'version' => '',
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/version']);
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_patch_request_if_the_privacy_is_not_valid()
    {
        $build = factory(Build::class)->create();

        $this->updateBuild($build, [
            'privacy' => 'omnipresent',
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/privacy']);
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_patch_request_if_the_arguments_is_not_an_array()
    {
        $build = factory(Build::class)->create();

        $this->updateBuild($build, [
            'arguments' => 'a string is not an array',
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/arguments']);
    }

    /* ================= Test Helpers ================= */

    /**
     * Send a GET request to the modpack endpoint with the given modpack id.
     *
     * @param $build
     * @param bool $asGuest
     */
    protected function showBuild($build, $asGuest = false)
    {
        if (! $asGuest) {
            $user = factory(User::class)->create();
            $this->actingAs($user, 'api');
        }

        $this->getJsonApi('/api/builds/'.$build->id);
    }

    protected function updateBuild($build, $attributes, $asGuest = false)
    {
        if (! $asGuest) {
            $user = factory(User::class)->create();
            $this->actingAs($user, 'api');
        }

        $data = ['data' => [
            'type' => 'build',
            'id' => $build->id,
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

        $this->patchJsonApi('/api/builds/'.$build->id, $data);
    }

    protected function deleteBuild($build, $asGuest = false)
    {
        if (! $asGuest) {
            $user = factory(User::class)->create();
            $this->actingAs($user, 'api');
        }

        $this->deleteJsonApi('/api/builds/'.$build->id);
    }

    protected function seeBuild($build, $attributes = null)
    {
        $data = [
            'type' => 'build',
        ];

        if ($build !== null) {
            $data['id'] = $build->id;
        }

        if ($attributes !== null) {
            $data['attributes'] = $attributes;
        }

        $this->assertJson(['data' => $data]);
    }
}
