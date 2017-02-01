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

use App\Modpack;
use App\User;
use Tests\stubs\TestFile;
use Tests\TestCase;

class ModpacksTest extends TestCase
{
    use ApiActions;

    /** @test */
    public function modpacks_can_be_browsed()
    {
        factory(Modpack::class)->create();

        $response = $this->getApi('api/modpacks');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }

    /** @test */
    public function a_modpack_can_be_read()
    {
        $modpack = factory(Modpack::class)->create();

        $response = $this->getApi('api/modpacks/'.$modpack->id);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }

    /** @test */
    public function a_modpacks_builds_can_be_browsed()
    {
        $modpack = factory(Modpack::class)->create();

        $response = $this->getApi('api/modpacks/'.$modpack->id.'/builds');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }

    /** @test */
    public function the_relationship_between_modpacks_and_builds_can_be_read()
    {
        $modpack = factory(Modpack::class)->create();

        $response = $this->getApi('api/modpacks/'.$modpack->id.'/relationships/builds');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }

    /** @test */
    public function a_modpack_can_be_edited()
    {
        $modpack = factory(Modpack::class)->create([
            'name' => 'original',
        ]);
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->patchApi('api/modpacks/'.$modpack->id, [
            'data' => [
                'type' => 'modpack',
                'id' => $modpack->id,
                'attributes' => [
                    'name' => 'revised',
                ],
            ],
        ]);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $this->assertDatabaseHas('modpacks', [
            'id' => $modpack->id,
            'name' => 'revised',
        ]);
    }

    /** @test */
    public function a_modpack_can_be_added()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->postApi('api/modpacks', [
            'data' => [
                'type' => 'modpack',
                'attributes' => [
                    'name' => 'Test Modpack',
                ],
            ],
        ]);

        $response->assertStatus(201);
        $response->assertHeader('Location');
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $this->assertDatabaseHas('modpacks', [
            'name' => 'Test Modpack',
        ]);
    }

    /** @test */
    public function a_modpack_icon_can_be_uploaded()
    {
        $modpack = factory(Modpack::class)->create();
        $user = factory(User::class)->create();
        $icon = TestFile::imageGenerator(50, 50, 'png');

        $response = $this->actingAs($user, 'api')->put('api/modpacks/'.$modpack->id.'/icon', $icon->toArray(), [
            'Content-Type' => $icon->getMimeType(),
            'Content-Length' => $icon->getSize(),
        ]);

        $response->assertStatus(200);
        $this->assertFileExists(storage_path('app/public/modpacks/icon_'.$modpack->id.'.png'));
    }

    /** @test */
    public function a_modpack_logo_can_be_uploaded()
    {
        $modpack = factory(Modpack::class)->create();
        $user = factory(User::class)->create();
        $logo = TestFile::imageGenerator(370, 220, 'png');

        $response = $this->actingAs($user, 'api')->put('api/modpacks/'.$modpack->id.'/logo', $logo->toArray(), [
            'Content-Type' => $logo->getMimeType(),
            'Content-Length' => $logo->getSize(),
        ]);

        $response->assertStatus(200);
        $this->assertFileExists(storage_path('app/public/modpacks/logo_'.$modpack->id.'.png'));
    }

    /** @test */
    public function a_modpack_background_can_be_uploaded()
    {
        $modpack = factory(Modpack::class)->create();
        $user = factory(User::class)->create();
        $background = TestFile::imageGenerator(900, 600, 'jpeg');

        $response = $this->actingAs($user, 'api')->put('api/modpacks/'.$modpack->id.'/background', $background->toArray(), [
            'Content-Type' => $background->getMimeType(),
            'Content-Length' => $background->getSize(),
        ]);

        $response->assertStatus(200);
        $this->assertFileExists(storage_path('app/public/modpacks/background_'.$modpack->id.'.jpeg'));
    }

    /** @test */
    public function a_build_can_be_added_to_a_modpack()
    {
        $modpack = factory(Modpack::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->postApi('api/modpacks/'.$modpack->id.'/builds', [
            'data' => [
                'type' => 'build',
                'attributes' => [
                    'version' => '1.0.0',
                    'game_version' => '1.0.0',
                ],
            ],
        ]);

        $response->assertStatus(201);
        $response->assertHeader('Location');
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $this->assertDatabaseHas('builds', [
            'version' => '1.0.0',
            'game_version' => '1.0.0',
            'modpack_id' => $modpack->id,
        ]);
    }

    /** @test */
    public function a_modpack_can_be_destroyed()
    {
        $modpack = factory(Modpack::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->deleteApi('api/modpacks/'.$modpack->id);


        $response->assertStatus(204);
    }

    /** @test */
    public function name_is_required()
    {
        $user = factory(User::class)->create();

        // Create
        $response = $this->actingAs($user, 'api')->postApi('api/modpacks', [
        'data' => [
            'type' => 'modpack',
            'attributes' => [
                // Missing name attribute
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
        $response = $this->actingAs($user, 'api')->postApi('api/modpacks', [
            'data' => [
                'type' => 'modpack',
                'attributes' => [
                    'name' => '',
                ],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');

        // Edit
        $modpack = factory(Modpack::class)->create();
        $response = $this->actingAs($user, 'api')->patchApi('api/modpacks/'.$modpack->id, [
            'data' => [
                'type' => 'modpack',
                'id' => $modpack->id,
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
        factory(Modpack::class)->create([
            'slug' => 'test-modpack',
        ]);

        // Create
        $response = $this->actingAs($user, 'api')->postApi('api/modpacks', [
            'data' => [
                'type' => 'modpack',
                'attributes' => [
                    'name' => 'Test Modpack',
                    'slug' => 'test-modpack',
                ],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');

        // Edit
        $modpack = factory(Modpack::class)->create();
        $response = $this->actingAs($user, 'api')->patchApi('api/modpacks/'.$modpack->id, [
            'data' => [
                'type' => 'modpack',
                'id' => $modpack->id,
                'attributes' => [
                    'slug' => 'test-modpack',
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
        $response = $this->actingAs($user, 'api')->postApi('api/modpacks', [
            'data' => [
                'type' => 'modpack',
                'attributes' => [
                    'name' => 'Test Modpack',
                    'slug' => '',
                ],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');

        // Edit
        $modpack = factory(Modpack::class)->create();
        $response = $this->actingAs($user, 'api')->patchApi('api/modpacks/'.$modpack->id, [
            'data' => [
                'type' => 'modpack',
                'id' => $modpack->id,
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
        $response = $this->actingAs($user, 'api')->postApi('api/modpacks', [
            'data' => [
                'type' => 'modpack',
                'attributes' => [
                    'name' => 'Test Modpack',
                    'slug' => 'invalid slug!',
                ],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');

        // Edit
        $modpack = factory(Modpack::class)->create();
        $response = $this->actingAs($user, 'api')->patchApi('api/modpacks/'.$modpack->id, [
            'data' => [
                'type' => 'modpack',
                'id' => $modpack->id,
                'attributes' => [
                    'slug' => 'invalid slug!',
                ],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }

    /** @test */
    public function privacy_must_be_valid()
    {
        $user = factory(User::class)->create();

        // Create
        $response = $this->actingAs($user, 'api')->postApi('api/modpacks', [
            'data' => [
                'type' => 'modpack',
                'attributes' => [
                    'name' => 'Test Modpack',
                    'privacy' => 'fake',
                ],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');

        // Edit
        $modpack = factory(Modpack::class)->create();
        $response = $this->actingAs($user, 'api')->patchApi('api/modpacks/'.$modpack->id, [
            'data' => [
                'type' => 'modpack',
                'id' => $modpack->id,
                'attributes' => [
                    'privacy' => 'fake',
                ],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }

    /** @test */
    public function tags_must_be_an_array()
    {
        $user = factory(User::class)->create();

        // Create
        $response = $this->actingAs($user, 'api')->postApi('api/modpacks', [
            'data' => [
                'type' => 'modpack',
                'attributes' => [
                    'name' => 'Test Modpack',
                    'tags' => 'sentence',
                ],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');

        // Edit
        $modpack = factory(Modpack::class)->create();
        $response = $this->actingAs($user, 'api')->patchApi('api/modpacks/'.$modpack->id, [
            'data' => [
                'type' => 'modpack',
                'id' => $modpack->id,
                'attributes' => [
                    'tags' => 'sentence',
                ],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }

    /** @test */
    public function website_must_be_a_url()
    {
        $user = factory(User::class)->create();

        // Create
        $response = $this->actingAs($user, 'api')->postApi('api/modpacks', [
            'data' => [
                'type' => 'modpack',
                'attributes' => [
                    'name' => 'Test Modpack',
                    'website' => 'not-a-url',
                ],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');

        // Edit
        $modpack = factory(Modpack::class)->create();
        $response = $this->actingAs($user, 'api')->patchApi('api/modpacks/'.$modpack->id, [
            'data' => [
                'type' => 'modpack',
                'id' => $modpack->id,
                'attributes' => [
                    'website' => 'not-a-url',
                ],
            ],
        ]);
        $response->assertStatus(422);
        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }
}
