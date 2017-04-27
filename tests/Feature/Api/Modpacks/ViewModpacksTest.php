<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Feature\Api\Modpacks;

use App\User;
use App\Modpack;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewModpacksTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function get_public_modpack_list()
    {
        $modpack1 = factory(Modpack::class)->states(['public'])->create([
            'slug' => 'test-1',
            'name' => 'Test Modpack 1',
        ]);
        $modpack2 = factory(Modpack::class)->states(['public'])->create([
            'slug' => 'test-2',
            'name' => 'Test Modpack 2',
        ]);

        $response = $this->json('GET', 'api/modpacks');

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                [
                    'type' => 'modpack',
                    'id' => $modpack1->id,
                    'attributes' => [
                        'name' => 'Test Modpack 1',
                        'slug' => 'test-1',
                        'status' => 'public',
                    ],
                    'links' => [
                        'self' => url("/api/modpacks/{$modpack1->id}"),
                    ],
                    'relationships' => [
                        'builds' => [
                            'links' => [
                                'related' => [
                                    'href' => url("/api/modpacks/{$modpack1->id}/builds"),
                                    'meta' => ['count' => 0],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'type' => 'modpack',
                    'id' => $modpack2->id,
                    'attributes' => [
                        'name' => 'Test Modpack 2',
                        'slug' => 'test-2',
                        'status' => 'public',
                    ],
                    'links' => [
                        'self' => url("/api/modpacks/{$modpack2->id}"),
                    ],
                    'relationships' => [
                        'builds' => [
                            'links' => [
                                'related' => [
                                    'href' => url("/api/modpacks/{$modpack2->id}/builds"),
                                    'meta' => ['count' => 0],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

    /** @test */
    public function unlisted_modpacks_are_not_in_list()
    {
        factory(Modpack::class)->states(['unlisted'])->create();

        $response = $this->json('GET', 'api/modpacks');

        $response->assertStatus(200);
        $response->assertJson(['data' => []]);
        $this->assertCount(0, $response->decodeResponseJson()['data']);
    }

    /** @test */
    public function get_any_status_with_authentication()
    {
        $this->withExceptionHandling();
        $this->actingAs(factory(User::class)->create(), 'api');
        factory(Modpack::class)->states(['public'])->create();
        factory(Modpack::class)->states(['private'])->create();
        factory(Modpack::class)->states(['unlisted'])->create();

        $response = $this->json('GET', 'api/modpacks');

        $response->assertStatus(200);
        $this->assertCount(3, $response->decodeResponseJson()['data']);
    }

    /** @test */
    public function get_public_modpack_details()
    {
        $modpack = factory(Modpack::class)->states(['public'])->create([
            'slug' => 'test-1',
            'name' => 'Test Modpack 1',
        ]);

        $response = $this->json('GET', 'api/modpacks/'.$modpack->id);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'type' => 'modpack',
                'id' => $modpack->id,
                'attributes' => [
                    'name' => 'Test Modpack 1',
                    'slug' => 'test-1',
                    'status' => 'public',
                ],
                'links' => [
                    'self' => url("/api/modpacks/{$modpack->id}"),
                ],
                'relationships' => [
                    'builds' => [
                        'links' => [
                            'related' => [
                                'href' => url("/api/modpacks/{$modpack->id}/builds"),
                                'meta' => ['count' => 0],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

    /** @test */
    public function private_modpack_details_requires_authentication()
    {
        $this->withExceptionHandling();
        $modpack = factory(Modpack::class)->states(['private'])->create();

        $response = $this->json('GET', 'api/modpacks/'.$modpack->id);

        $response->assertStatus(403);
    }
}
