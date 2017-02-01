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

class ApiModpacksTest extends TestCase
{
    use MakesJsonApiRequests;

    /** @test */
    public function a_guest_can_list_public_modpacks()
    {
        $modpack = factory(Modpack::class)->states(['public'])->create();

        $this->listModpacks(true);

        $this->assertResponseOk();
        $this->seeModpackCollection($modpack);
    }

    /** @test */
    public function a_guests_cannot_list_non_public_modpacks()
    {
        $unlistedModpack = factory(Modpack::class)->states(['unlisted'])->create();
        $privateModpack = factory(Modpack::class)->states(['private'])->create();

        $this->listModpacks(true);

        $this->assertResponseOk();
        $this->doNotSeeModpack($unlistedModpack);
        $this->doNotSeeModpack($privateModpack);
    }

    /** @test */
    public function a_user_can_list_all_modpacks()
    {
        $privateModpack = factory(Modpack::class)->states(['private'])->create();
        $publicModpack = factory(Modpack::class)->states(['public'])->create();
        $unlistedModpack = factory(Modpack::class)->states(['unlisted'])->create();

        $this->listModpacks();

        $this->assertResponseOk();
        $this->seeModpackCollection($privateModpack, $publicModpack, $unlistedModpack);
    }

    /** @test */
    public function a_guest_can_view_a_public_modpack()
    {
        $modpack = factory(Modpack::class)->states(['public'])->create();

        $this->showModpack($modpack, true);

        $this->assertResponseOk();
        $this->seeModpack($modpack);
    }

    /** @test */
    public function a_guest_can_view_an_unlisted_modpack()
    {
        $modpack = factory(Modpack::class)->states(['unlisted'])->create();
        $this->assertEquals('unlisted', $modpack->privacy);

        $this->showModpack($modpack);

        $this->assertResponseOk();
        $this->seeModpack($modpack);
    }

    /** @test */
    public function a_user_can_view_a_public_modpack()
    {
        $modpack = factory(Modpack::class)->states(['public'])->create();
        $this->assertEquals('public', $modpack->privacy);

        $this->showModpack($modpack);

        $this->assertResponseOk();
        $this->seeModpack($modpack);
    }

    /** @test */
    public function a_user_can_view_a_unlisted_modpack()
    {
        $modpack = factory(Modpack::class)->states(['unlisted'])->create();
        $this->assertEquals('unlisted', $modpack->privacy);

        $this->showModpack($modpack);

        $this->assertResponseOk();
        $this->seeModpack($modpack);
    }

    /** @test */
    public function a_user_can_view_a_private_modpack()
    {
        $modpack = factory(Modpack::class)->states(['private'])->create();
        $this->assertEquals('private', $modpack->privacy);

        $this->showModpack($modpack);

        $this->assertResponseOk();
        $this->seeModpack($modpack);
    }

    /** @test */
    public function a_user_can_post_a_new_modpack()
    {
        $this->createModpack([
            'name' => 'Example Modpack',
        ]);

        $this->assertStatus(201);
        $this->seeModpack(null, [
            'name' => 'Example Modpack',
        ]);
    }

    /** @test */
    public function a_user_can_update_a_modpack()
    {
        $modpack = factory(Modpack::class)->create([
            'name' => 'Example Modpack',
        ]);

        $this->updateModpack($modpack, [
            'name' => 'Updated Modpack',
        ]);

        $this->assertStatus(200);
        $this->seeModpack($modpack, [
            'name' => 'Updated Modpack',
        ]);
    }

    /** @test */
    public function it_allows_the_existing_slug_to_be_reused()
    {
        $modpack = factory(Modpack::class)->create([
            'slug' => 'test-slug',
        ]);

        $this->updateModpack($modpack, [
            'slug' => 'test-slug',
        ]);

        $this->assertStatus(200);
        $this->seeModpack($modpack, [
            'slug' => 'test-slug',
        ]);
    }

    /** @test */
    public function a_user_can_binary_upload_an_asset()
    {
        $modpack = factory(Modpack::class)->create();
        $file = TestFile::imageGenerator(50, 50, 'png');

        $this->putModpackAsset($modpack, $file, 'icon');

        $this->assertStatus(200);
        $this->seeModpack($modpack, [
            'icon' => config('app.url').'/storage/modpacks/icon_'.$modpack->id.'.png',
        ]);
    }

    /** @test */
    public function a_user_can_delete_a_modpack()
    {
        $modpack = factory(Modpack::class)->create();

        $this->deleteModpack($modpack);

        $this->assertStatus(204);
        $this->assertEmpty($this->response->getContent());
    }

    /** @test */
    public function response_can_include_build_data()
    {
        $build = factory(Build::class)->create();

        $this->showModpack($build->modpack, false, ['include' => 'builds']);

        $this->assertStatus(200);
        $this->seeModpack($build->modpack);
        $this->includesBuildCollection($build);
    }

    /** @test */
    public function it_returns_a_401_if_a_guest_tries_to_get_a_private_modpack()
    {
        $modpack = factory(Modpack::class)->states(['private'])->create();
        $this->assertEquals('private', $modpack->privacy);

        $this->showModpack($modpack, true);

        $this->seeJsonApiError(401, 'Unauthorized');
    }

    /** @test */
    public function it_returns_a_401_if_a_guest_tries_to_post_a_modpack()
    {
        $this->createModpack([], true);

        $this->seeJsonApiError(401, 'Unauthorized');
    }

    /** @test */
    public function it_returns_a_401_if_a_guest_tries_to_patch_a_modpack()
    {
        $modpack = factory(Modpack::class)->create();

        $this->updateModpack($modpack, [], true);

        $this->seeJsonApiError(401, 'Unauthorized');
    }

    /** @test */
    public function it_returns_a_401_if_a_guest_tries_to_put_an_asset()
    {
        $modpack = factory(Modpack::class)->create();
        $file = TestFile::imageGenerator(50, 50, 'png');

        $this->putModpackAsset($modpack, $file, 'icon', true);

        $this->seeJsonApiError(401, 'Unauthorized');
    }

    /** @test */
    public function it_returns_a_401_if_a_guest_tries_to_delete_a_modpack()
    {
        $modpack = factory(Modpack::class)->create();

        $this->deleteModpack($modpack, true);

        $this->seeJsonApiError(401, 'Unauthorized');
    }

    /** @test */
    public function it_returns_a_403_if_a_put_request_is_made_to_an_unsupported_asset_endpoint()
    {
        $modpack = factory(Modpack::class)->create();
        $file = TestFile::imageGenerator(50, 50, 'png');

        $this->putModpackAsset($modpack, $file, 'invalid-asset');

        $this->seeJsonApiError(403);
    }

    /** @test */
    public function it_returns_a_404_when_processing_a_get_request_if_the_modpack_does_not_exist()
    {
        $invalidModpack = new Modpack();
        $invalidModpack->id = 'invalid-id';

        $this->showModpack($invalidModpack);

        $this->seeJsonApiError(404, 'Not Found');
    }

    /** @test */
    public function it_returns_a_404_when_processing_a_patch_request_if_the_modpack_does_not_exist()
    {
        $invalidModpack = new Modpack();
        $invalidModpack->id = 'invalid-id';

        $this->updateModpack($invalidModpack, []);

        $this->seeJsonApiError(404, 'Not Found');
    }

    /** @test */
    public function it_returns_a_404_when_processing_a_put_request_if_the_modpack_does_not_exist()
    {
        $invalidModpack = new Modpack();
        $invalidModpack->id = 'invalid-id';
        $file = TestFile::imageGenerator(50, 50, 'png');

        $this->putModpackAsset($invalidModpack, $file, 'icon');

        $this->seeJsonApiError(404, 'Not Found');
    }

    /** @test */
    public function it_returns_a_404_when_processing_a_delete_request_if_the_modpack_does_not_exist()
    {
        $invalidModpack = new Modpack();
        $invalidModpack->id = 'invalid-id';

        $this->deleteModpack($invalidModpack);

        $this->seeJsonApiError(404, 'Not Found');
    }

    /** @test */
    public function it_returns_a_409_when_processing_a_patch_request_and_the_resource_id_does_not_match_the_servers_endpoint()
    {
        $modpack = factory(Modpack::class)->create();

        $this->updateModpack($modpack, [
            'id' => 'wrong-id',
        ]);

        $this->seeJsonApiError(409);
    }

    /** @test */
    public function it_returns_a_409_when_processing_a_patch_request_and_the_resource_type_does_not_match_the_servers_endpoint()
    {
        $modpack = factory(Modpack::class)->create();

        $this->updateModpack($modpack, [
            'type' => 'wrong-type',
        ]);

        $this->seeJsonApiError(409);
    }

    /** @test */
    public function it_returns_a_411_when_processing_a_put_request_and_the_content_type_is_image_and_the_content_length_is_not_provided()
    {
        $modpack = factory(Modpack::class)->create();
        $file = TestFile::imageGenerator(50, 50, 'png')->overrideSize(0);

        $this->putModpackAsset($modpack, $file, 'icon');

        $this->seeJsonApiError(411);
    }

    /** @test */
    public function it_returns_a_413_when_processing_a_put_request_and_the_content_type_is_image_and_the_payload_is_too_large()
    {
        $modpack = factory(Modpack::class)->create();
        $file = TestFile::imageGenerator(50, 50, 'png')->overrideSize(config('solder.modpacks.icon.max_filesize') + 1);

        $this->putModpackAsset($modpack, $file, 'icon');

        $this->seeJsonApiError(413);
    }

    /** @test */
    public function it_returns_a_415_when_processing_a_put_request_if_the_payload_is_not_a_supported_media_type()
    {
        $modpack = factory(Modpack::class)->create();
        $file = TestFile::imageGenerator(50, 50, 'png')->overrideMimeType('image/svg+xml');

        $this->putModpackAsset($modpack, $file, 'icon');

        $this->seeJsonApiError(415);
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_post_request_if_the_name_is_blank()
    {
        $this->createModpack([
            'name' => '',
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/name']);
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_patch_request_if_the_name_is_blank()
    {
        $modpack = factory(Modpack::class)->create();

        $this->updateModpack($modpack, [
            'name' => '',
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/name']);
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_post_request_if_the_slug_is_blank()
    {
        $this->createModpack([
            'name' => 'Test Modpack',
            'slug' => '',
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/slug']);
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_patch_request_if_the_slug_is_blank()
    {
        $modpack = factory(Modpack::class)->create();

        $this->updateModpack($modpack, [
            'slug' => '',
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/slug']);
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_post_request_if_the_slug_is_not_url_safe()
    {
        $this->createModpack([
            'name' => 'Test Modpack',
            'slug' => 'slug with space and punctuation!',
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/slug']);
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_patch_request_if_the_slug_is_not_url_safe()
    {
        $modpack = factory(Modpack::class)->create();

        $this->updateModpack($modpack, [
            'slug' => 'slug with space and punctuation!',
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/slug']);
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_post_request_if_the_slug_is_not_unique()
    {
        factory(Modpack::class)->create(['slug' => 'existing-slug']);

        $this->createModpack([
            'name' => 'Test Modpack',
            'slug' => 'existing-slug',
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/slug']);
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_patch_request_if_the_slug_is_not_unique()
    {
        factory(Modpack::class)->create(['slug' => 'existing-slug']);
        $modpack = factory(Modpack::class)->create();

        $this->updateModpack($modpack, [
            'slug' => 'existing-slug',
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/slug']);
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_post_request_if_the_privacy_is_not_valid()
    {
        $this->createModpack([
            'name' => 'Test Modpack',
            'privacy' => 'omnipresent',
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/privacy']);
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_patch_request_if_the_privacy_is_not_valid()
    {
        $modpack = factory(Modpack::class)->create();

        $this->updateModpack($modpack, [
            'privacy' => 'omnipresent',
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/privacy']);
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_post_request_if_the_website_is_not_valid()
    {
        $this->createModpack([
            'name' => 'Test Modpack',
            'website' => 'invalid-url',
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/website']);
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_patch_request_if_the_website_is_not_valid()
    {
        $modpack = factory(Modpack::class)->create();

        $this->updateModpack($modpack, [
            'website' => 'not-a-url',
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/website']);
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_post_request_if_the_tags_is_not_an_array()
    {
        $this->createModpack([
            'name' => 'Test Modpack',
            'tags' => 'invalid-tags',
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/tags']);
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_patch_request_if_the_tags_is_not_an_array()
    {
        $modpack = factory(Modpack::class)->create();

        $this->updateModpack($modpack, [
            'tags' => 'a string is not an array',
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/tags']);
    }

    /* ================= Test Helpers ================= */

    /**
     * Send a GET request to the modpack index.
     *
     * @param bool $asGuest
     */
    protected function listModpacks($asGuest = false)
    {
        if (! $asGuest) {
            $user = factory(User::class)->create();
            $this->actingAs($user, 'api');
        }

        $this->getJsonApi('/api/modpacks');
    }

    /**
     * Send a GET request to the modpack endpoint with the given modpack id.
     *
     * @param $modpack
     * @param bool $asGuest
     */
    protected function showModpack($modpack, $asGuest = false, $queryData = [])
    {
        if (! $asGuest) {
            $user = factory(User::class)->create();
            $this->actingAs($user, 'api');
        }

        $this->getJsonApi('/api/modpacks/'.$modpack->id.'?'.http_build_query($queryData));
    }

    protected function createModpack($attributes, $asGuest = false)
    {
        if (! $asGuest) {
            $user = factory(User::class)->create();
            $this->actingAs($user, 'api');
        }

        $data = ['data' => [
            'type' => 'modpack',
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

        $this->postJsonApi('/api/modpacks', $data);
    }

    protected function putModpackAsset($modpack, TestFile $file, $asset, $asGuest = false)
    {
        if (! $asGuest) {
            $user = factory(User::class)->create();
            $this->actingAs($user, 'api');
        }

        $this->put('/api/modpacks/'.$modpack->id.'/'.$asset, [$file->getContents()], [
            'Content-Type' => $file->getMimeType(),
            'Content-Length' => $file->getSize(),
            'Accept' => 'application/vnd.api+json',
        ]);
    }

    protected function updateModpack($modpack, $attributes, $asGuest = false)
    {
        if (! $asGuest) {
            $user = factory(User::class)->create();
            $this->actingAs($user, 'api');
        }

        $data = ['data' => [
            'type' => 'modpack',
            'id' => $modpack->id,
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

        $this->patchJsonApi('/api/modpacks/'.$modpack->id, $data);
    }

    protected function deleteModpack($modpack, $asGuest = false)
    {
        if (! $asGuest) {
            $user = factory(User::class)->create();
            $this->actingAs($user, 'api');
        }

        $this->deleteJsonApi('/api/modpacks/'.$modpack->id);
    }

    protected function seeModpack($modpack, $attributes = null)
    {
        $data = [
            'type' => 'modpack',
        ];

        if ($modpack !== null) {
            $data['id'] = $modpack->id;
        }

        if ($attributes !== null) {
            $data['attributes'] = $attributes;
        }

        $this->assertJson(['data' => $data]);
    }

    protected function seeModpackCollection()
    {
        if (count(func_get_args()) == 0) {
            throw new exception('At least one modpack must be provided.');
        }

        $data = [];

        foreach (func_get_args() as $modpack) {
            $data[] = [
                'type' => 'modpack',
                'id' => $modpack->id,
            ];
        }

        $this->assertJson(['data' => $data]);
    }

    private function doNotSeeModpack($model)
    {
        $this->dontSeeJson(['id' => $model->id]);
    }

    private function includesBuildCollection()
    {
        if (count(func_get_args()) == 0) {
            throw new exception('At least one build must be provided.');
        }

        $data = [];

        foreach (func_get_args() as $build) {
            $data[] = [
                'type' => 'build',
                'id' => $build->id,
            ];
        }

        $this->assertJson(['included' => $data]);
    }
}
