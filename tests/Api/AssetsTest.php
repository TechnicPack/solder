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

use App\Asset;
use App\User;
use App\Version;
use Tests\stubs\TestFile;
use Tests\TestCase;

class AssetsTest extends TestCase
{
    use ApiActions;

    /** @test */
    public function assets_can_be_browsed()
    {
        factory(Asset::class)->create();

        $response = $this->getApi('api/assets');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }

    /** @test */
    public function an_asset_can_be_read()
    {
        $asset = factory(Asset::class)->create();

        $response = $this->getApi('api/assets/'.$asset->id);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }

    /** @test */
    public function an_asset_can_be_edited()
    {
        $user = factory(User::class)->create();
        $asset = factory(Asset::class)->create([
            'filename' => 'original',
        ]);

        $response = $this->actingAs($user, 'api')->patchApi('api/assets/'.$asset->id, [
            'data' => [
                'type' => 'asset',
                'id' => $asset->id,
                'attributes' => [
                    'filename' => 'revised',
                ],
            ],
        ]);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $this->assertDatabaseHas('assets', [
            'id' => $asset->id,
            'filename' => 'revised',
        ]);
    }

    /** @test */
    public function an_asset_can_be_added()
    {
        $version = factory(Version::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->postApi('api/assets', [
            'data' => [
                'type' => 'asset',
                'attributes' => [
                    'filename' => 'testfile.txt',
                ],
            ],
            'relationships' => [
                'version' => [
                    'data' => [
                        'type' => 'version',
                        'id' => $version->id,
                    ],
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
    public function an_asset_file_can_be_uploaded()
    {
        $asset = factory(Asset::class)->create();
        $user = factory(User::class)->create();
        $file = TestFile::textGenerator(200);

        $response = $this->actingAs($user, 'api')->put('api/assets/'.$asset->id, $file->toArray(), [
            'Content-Type' => $file->getMimeType(),
            'Content-Length' => $file->getSize(),
        ]);

        $response->assertStatus(200);
        $response->assertHeader('Location');
        $this->assertFileExists(storage_path('app/public/assets/'.$asset->id.'/'.$asset->filename));
    }

    /** @test */
    public function an_asset_can_be_destroyed()
    {
        $asset = factory(Asset::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->deleteApi('api/assets/'.$asset->id);

        $response->assertStatus(204);
    }

    /** @test */
    public function filename_is_required()
    {
        $version = factory(Version::class)->create();
        $user = factory(User::class)->create();

        // Create
        $response = $this->actingAs($user, 'api')->postApi('api/assets', [
            'data' => [
                'type' => 'asset',
                'attributes' => [
                    // missing filename
                ],
            ],
            'relationships' => [
                'version' => [
                    'data' => [
                        'type' => 'version',
                        'id' => $version->id,
                    ],
                ],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');

        // Edit
        $asset = factory(Asset::class)->create();
        $response = $this->actingAs($user, 'api')->patchApi('api/assets/'.$asset->id, [
            'data' => [
                'type' => 'asset',
                'id' => $asset->id,
                'attributes' => [
                    'filename' => '',
                ],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }

    /** @test */
    public function version_relationship_required()
    {
        $user = factory(User::class)->create();

        // Create
        $response = $this->actingAs($user, 'api')->postApi('api/assets', [
            'data' => [
                'type' => 'asset',
                'attributes' => [
                    'filename' => 'testfile.txt',
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
