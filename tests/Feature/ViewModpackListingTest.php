<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Feature;

use App\User;
use App\Modpack;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewModpackListingTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function get_public_modpack_list()
    {
        \Config::set('app.url', 'http://example.com');
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
                        'self' => 'http://example.com/api/modpacks/'.$modpack1->id,
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
                        'self' => 'http://example.com/api/modpacks/'.$modpack2->id,
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
        $this->actingAs(factory(User::class)->create());
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
        \Config::set('app.url', 'http://example.com');
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
                    'self' => 'http://example.com/api/modpacks/'.$modpack->id,
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
