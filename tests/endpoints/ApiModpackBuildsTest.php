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
use App\Modpack;
use App\User;

class ApiModpackBuildsTest extends TestCase
{
    use MakesJsonApiRequests;

    /** @test */
    public function a_guest_can_list_public_builds_that_are_part_of_a_public_modpack()
    {
        $modpack = factory(Modpack::class)->states(['public'])->create();
        $build = factory(Build::class)->states(['public'])->create(['modpack_id' => $modpack->id]);

        $this->listModpackBuilds($modpack, true);

        $this->assertResponseOk();
        $this->seeBuildCollection($build);
    }

    /** @test */
    public function a_guest_can_list_public_builds_that_are_part_of_an_unlisted_modpack()
    {
        $modpack = factory(Modpack::class)->states(['unlisted'])->create();
        $build = factory(Build::class)->states(['public'])->create(['modpack_id' => $modpack->id]);

        $this->listModpackBuilds($modpack, true);

        $this->assertResponseOk();
        $this->seeBuildCollection($build);
    }

    /** @test */
    public function a_guests_cannot_list_non_public_builds()
    {
        $modpack = factory(Modpack::class)->states(['public'])->create();
        $unlistedBuild = factory(Build::class)->states(['unlisted'])->create(['modpack_id' => $modpack->id]);
        $privateBuild = factory(Build::class)->states(['private'])->create(['modpack_id' => $modpack->id]);

        $this->listModpackBuilds($modpack, true);

        $this->assertResponseOk();
        $this->doNotSeeBuild($unlistedBuild);
        $this->doNotSeeBuild($privateBuild);
    }

    /** @test */
    public function a_user_can_list_all_builds()
    {
        $modpack = factory(Modpack::class)->create();
        $privateBuild = factory(Build::class)->states(['private'])->create(['modpack_id' => $modpack->id]);
        $publicBuild = factory(Build::class)->states(['public'])->create(['modpack_id' => $modpack->id]);
        $unlistedBuild = factory(Build::class)->states(['unlisted'])->create(['modpack_id' => $modpack->id]);

        $this->listModpackBuilds($modpack);

        $this->assertResponseOk();
        $this->seeBuildCollection($privateBuild, $publicBuild, $unlistedBuild);
    }

    /** @test */
    public function a_user_can_post_a_new_build()
    {
        $modpack = factory(Modpack::class)->create();

        $this->createBuild($modpack, [
            'version' => '1.0.0',
            'game_version' => '1.0.0',
        ]);

        $this->assertStatus(201);
        $this->seeBuild(null, [
            'version' => '1.0.0',
            'game_version' => '1.0.0',
        ]);
    }

    /** @test */
    public function it_lists_relationships_with_public_builds_for_guests()
    {
        $modpack = factory(Modpack::class)->states(['public'])->create();
        $build = factory(Build::class)->states(['public'])->create(['modpack_id' => $modpack->id]);

        $this->listModpackBuildRelationships($modpack, true);

        $this->assertResponseOk();
        $this->seeBuildCollection($build);
        $this->assertJson([
            'links' => [
                'self' => trim(config('app.url'), '/').'/api/modpacks/'.$modpack->id.'/relationships/builds',
                'related' => trim(config('app.url'), '/').'/api/modpacks/'.$modpack->id.'/builds',
            ]
        ]);
    }

    /** @test */
    public function it_returns_a_401_when_processing_a_get_request_for_relationships_on_a_private_modpack_without_authentication()
    {
        $modpack = factory(Modpack::class)->states(['private'])->create();

        $this->listModpackBuildRelationships($modpack, true);

        $this->seeJsonApiError(401, 'Unauthorized');
    }


    /** @test */
    public function it_returns_a_401_when_processing_a_get_request_for_builds_on_a_private_modpack_without_authentication()
    {
        $modpack = factory(Modpack::class)->states(['private'])->create();

        $this->listModpackBuilds($modpack, true);

        $this->seeJsonApiError(401, 'Unauthorized');
    }

    /** @test */
    public function it_returns_a_401_when_processing_a_post_request_without_authentication()
    {
        $modpack = factory(Modpack::class)->states(['private'])->create();

        $this->createBuild($modpack, [], true);

        $this->seeJsonApiError(401, 'Unauthorized');
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_post_request_if_the_version_is_blank()
    {
        $modpack = factory(Modpack::class)->create();

        $this->createBuild($modpack, [
            'version' => null,
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/version']);
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_post_request_if_the_version_is_not_unique_for_the_modpack()
    {
        $modpack = factory(Modpack::class)->create();
        factory(Build::class)->create(['modpack_id' => $modpack->id, 'version' => '1.0.0']);

        $this->createBuild($modpack, [
            'version' => '1.0.0',
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/version']);
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_post_request_if_the_privacy_is_not_valid()
    {
        $modpack = factory(Modpack::class)->create();

        $this->createBuild($modpack, [
            'version' => '1.0.0',
            'privacy' => 'omnipresent',
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/privacy']);
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_post_request_if_the_arguments_is_not_an_array()
    {
        $modpack = factory(Modpack::class)->create();

        $this->createBuild($modpack, [
            'version' => '1.0.0',
            'arguments' => 'not-an-array',
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/arguments']);
    }

    /* ================= Test Helpers ================= */

    /**
     * Send a GET request to the modpack index.
     *
     * @param $modpack
     * @param bool $asGuest
     */
    protected function listModpackBuilds($modpack, $asGuest = false)
    {
        if (! $asGuest) {
            $user = factory(User::class)->create();
            $this->actingAs($user, 'api');
        }

        $this->getJsonApi('/api/modpacks/'.$modpack->id.'/builds');
    }

    protected function createBuild($modpack, $attributes, $asGuest = false)
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

        $this->postJsonApi('/api/modpacks/'.$modpack->id.'/builds', $data);
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

    protected function seeBuildCollection()
    {
        if (count(func_get_args()) == 0) {
            throw new exception('At least one resource must be provided.');
        }

        $data = [];

        foreach (func_get_args() as $build) {
            $data[] = [
                'type' => 'build',
                'id' => $build->id,
            ];
        }

        $this->assertJson(['data' => $data]);
    }

    private function doNotSeeBuild($model)
    {
        $this->dontSeeJson(['id' => $model->id]);
    }

    /**
     * Send a GET request to the modpack relationships build endpoint.
     *
     * @param $modpack
     * @param bool $asGuest
     */
    protected function listModpackBuildRelationships($modpack, $asGuest = false)
    {
        if (! $asGuest) {
            $user = factory(User::class)->create();
            $this->actingAs($user, 'api');
        }

        $this->getJsonApi('api/modpacks/'.$modpack->id.'/relationships/builds');
    }
}
