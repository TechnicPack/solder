<?php

/*
 * This file is part of TechnicSolder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use App\User;
use App\Asset;

class ApiAssetsTest extends TestCase
{
    use MakesJsonApiRequests;

    /** @test */
    public function a_user_can_list_assets()
    {
        $asset = factory(Asset::class)->create();

        $this->listAssets();

        $this->assertResponseOk();
        $this->seeAssetCollection($asset);
    }

    /** @test */
    public function a_user_can_view_an_asset()
    {
        $asset = factory(Asset::class)->create();

        $this->showAsset($asset);

        $this->assertResponseOk();
        $this->seeAsset($asset);
    }

    /** @test */
    public function a_user_can_update_an_asset()
    {
        $asset = factory(Asset::class)->create();

        $this->updateAsset($asset, [
            'filename' => 'updated-filename',
        ]);

        $this->assertResponseStatus(200);
        $this->seeAsset($asset, [
            'filename' => 'updated-filename',
        ]);
    }

    /** @test */
    public function a_user_can_delete_an_asset()
    {
        $asset = factory(Asset::class)->create();

        $this->deleteAsset($asset);

        $this->assertResponseStatus(204);
        $this->assertEmpty($this->response->getContent());
    }

    /** @test */
    public function it_returns_a_401_if_a_guest_tries_to_list_versions()
    {
        $this->listAssets(true);

        $this->seeJsonApiError(401, 'Unauthorized');
    }

    /** @test */
    public function it_returns_a_401_if_a_guest_tries_to_get_a_version()
    {
        $asset = factory(Asset::class)->create();

        $this->showAsset($asset, true);

        $this->seeJsonApiError(401, 'Unauthorized');
    }

    /** @test */
    public function it_returns_a_401_if_a_guest_tries_to_patch_a_version()
    {
        $asset = factory(Asset::class)->create();

        $this->updateAsset($asset, [], true);

        $this->seeJsonApiError(401, 'Unauthorized');
    }

    /** @test */
    public function it_returns_a_401_if_a_guest_tries_to_delete_a_version()
    {
        $asset = factory(Asset::class)->create();

        $this->deleteAsset($asset, true);

        $this->seeJsonApiError(401, 'Unauthorized');
    }

    /** @test */
    public function it_returns_a_404_when_processing_a_get_request_if_the_version_does_not_exist()
    {
        $invalidAsset = new Asset();
        $invalidAsset->id = 'invalid-id';

        $this->showAsset($invalidAsset);

        $this->seeJsonApiError(404, 'Not Found');
    }

    /** @test */
    public function it_returns_a_404_when_processing_a_patch_request_if_the_version_does_not_exist()
    {
        $invalidAsset = new Asset();
        $invalidAsset->id = 'invalid-id';

        $this->updateAsset($invalidAsset, []);

        $this->seeJsonApiError(404, 'Not Found');
    }

    /** @test */
    public function it_returns_a_404_when_processing_a_delete_request_if_the_version_does_not_exist()
    {
        $invalidAsset = new Asset();
        $invalidAsset->id = 'invalid-id';

        $this->deleteAsset($invalidAsset);

        $this->seeJsonApiError(404, 'Not Found');
    }

    /** @test */
    public function it_returns_a_409_when_processing_a_patch_request_and_the_asset_id_does_not_match_the_servers_endpoint()
    {
        $asset = factory(Asset::class)->create();

        $this->updateAsset($asset, [
            'id' => 'wrong-id',
        ]);

        $this->seeJsonApiError(409);
    }

    /** @test */
    public function it_returns_a_409_when_processing_a_patch_request_and_the_asset_type_does_not_match_the_servers_endpoint()
    {
        $asset = factory(Asset::class)->create();

        $this->updateAsset($asset, [
            'type' => 'wrong-type',
        ]);

        $this->seeJsonApiError(409);
    }

    /** @test */
    public function it_returns_a_422_when_processing_a_patch_request_if_the_filename_is_blank()
    {
        $asset = factory(Asset::class)->create();

        $this->updateAsset($asset, [
            'filename' => '',
        ]);

        $this->seeJsonApiError(422, 'Unprocessable Entity', ['pointer' => 'data/attributes/filename']);
    }

    /* ================= Test Helpers ================= */

    /**
     * Send a GET request to the version index.
     *
     * @param bool $asGuest
     */
    protected function listAssets($asGuest = false)
    {
        if (! $asGuest) {
            $user = factory(User::class)->create();
            $this->actingAs($user, 'api');
        }

        $this->getJsonApi('/api/assets');
    }

    /**
     * Send a GET request to the modpack endpoint with the given modpack id.
     *
     * @param $asset
     * @param bool $asGuest
     */
    protected function showAsset($asset, $asGuest = false)
    {
        if (! $asGuest) {
            $user = factory(User::class)->create();
            $this->actingAs($user, 'api');
        }

        $this->getJsonApi('/api/assets/'.$asset->id);
    }

    protected function updateAsset($asset, $attributes, $asGuest = false)
    {
        if (! $asGuest) {
            $user = factory(User::class)->create();
            $this->actingAs($user, 'api');
        }

        $data = ['data' => [
            'type' => 'asset',
            'id' => $asset->id,
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

        $this->patchJsonApi('/api/assets/'.$asset->id, $data);
    }

    protected function deleteAsset($asset, $asGuest = false)
    {
        if (! $asGuest) {
            $user = factory(User::class)->create();
            $this->actingAs($user, 'api');
        }

        $this->deleteJsonApi('/api/assets/'.$asset->id);
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

        $this->seeJsonSubset(['data' => $data]);
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

        $this->seeJsonSubset(['data' => $data]);
    }
}
