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

use App\Asset;
use App\User;
use App\Version;

class ApiVersionAssetsTest extends TestCase
{
    use MakesJsonApiRequests;

    /** @test */
    public function a_user_can_list_all_assets_of_a_version()
    {
        $version = factory(Version::class)->create();
        $asset = factory(Asset::class)->create(['version_id' => $version->id]);

        $this->listVersionAssets($version);

        $this->assertResponseOk();
        $this->seeAssetCollection($asset);
    }

    /** @test */
    public function a_user_can_post_a_new_asset()
    {
        $version = factory(Version::class)->create();

        $this->createAsset($version, [
            'filename' => 'test-mod.jar',
        ]);

        $this->assertStatus(201);
        $this->seeAsset(null, [
            'filename' => 'test-mod.jar',
        ]);
    }

    /** @test */
    public function it_lists_relationships_data()
    {
        $version = factory(Version::class)->create();
        $asset = factory(Asset::class)->create(['version_id' => $version->id]);

        $this->listVersionAssetsRelationship($version);

        $this->assertResponseOk();
        $this->seeAssetCollection($asset);
        $this->assertJson([
            'links' => [
                'self' => trim(config('app.url'), '/').'/api/versions/'.$version->id.'/relationships/assets',
                'related' => trim(config('app.url'), '/').'/api/versions/'.$version->id.'/assets',
            ],
        ]);
    }

    /** @test */
    public function it_returns_a_401_when_processing_a_get_request_for_relationships_without_authentication()
    {
        $version = factory(Version::class)->create();

        $this->listVersionAssetsRelationship($version, true);

        $this->seeJsonApiError(401, 'Unauthorized');
    }

    /** @test */
    public function it_returns_a_401_when_processing_a_get_request_for_assets_without_authentication()
    {
        $version = factory(Version::class)->create();

        $this->listVersionAssets($version, true);

        $this->seeJsonApiError(401, 'Unauthorized');
    }

    /** @test */
    public function it_returns_a_401_when_processing_a_post_request_without_authentication()
    {
        $version = factory(Version::class)->create();

        $this->createAsset($version, [], true);

        $this->seeJsonApiError(401, 'Unauthorized');
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_post_request_if_the_filename_is_blank()
    {
        $version = factory(Version::class)->create();

        $this->createAsset($version, [
            'filename' => null,
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/filename']);
    }

    /* ================= Test Helpers ================= */

    /**
     * Send a GET request to the modpack index.
     *
     * @param $version
     * @param bool $asGuest
     */
    protected function listVersionAssets($version, $asGuest = false)
    {
        if (! $asGuest) {
            $user = factory(User::class)->create();
            $this->actingAs($user, 'api');
        }

        $this->getJsonApi('/api/versions/'.$version->id.'/assets');
    }

    protected function createAsset($version, $attributes, $asGuest = false)
    {
        if (! $asGuest) {
            $user = factory(User::class)->create();
            $this->actingAs($user, 'api');
        }

        $data = ['data' => [
            'type' => 'asset',
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

        $this->postJsonApi('/api/versions/'.$version->id.'/assets', $data);
    }

    protected function seeAsset($asset, $attributes = null)
    {
        $data = [
            'type' => 'asset',
        ];

        if ($asset !== null) {
            $data['id'] = $asset->id;
        }

        if ($attributes !== null) {
            $data['attributes'] = $attributes;
        }

        $this->assertJson(['data' => $data]);
    }

    protected function seeAssetCollection()
    {
        if (count(func_get_args()) == 0) {
            throw new exception('At least one asset must be provided.');
        }

        $data = [];

        foreach (func_get_args() as $asset) {
            $data[] = [
                'type' => 'asset',
                'id' => $asset->id,
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
    protected function listVersionAssetsRelationship($resource, $asGuest = false)
    {
        if (! $asGuest) {
            $user = factory(User::class)->create();
            $this->actingAs($user, 'api');
        }

        $this->getJsonApi('api/versions/'.$resource->id.'/relationships/assets');
    }
}
