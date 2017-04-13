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
use App\Build;
use App\Modpack;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewRelatedBuildsTest extends TestCase
{
    use DatabaseMigrations;

    protected $modpack;

    protected function setUp()
    {
        parent::setUp();

        \Config::set('app.url', 'http://example.com');
        $this->modpack = factory(Modpack::class)->states(['public'])->create();
    }

    /** @test */
    public function get_public_builds_for_a_modpack()
    {
        $build1 = factory(Build::class)->states(['public'])->create([
            'build_number' => '1.0.0',
            'minecraft_version' => '1.7.10',
            'arguments' => [
                'forge_version' => '10.2.4.1234',
                'java_version' => '1.7',
                'java_memory' => 1024,
            ],
            'modpack_id' => $this->modpack->id,
        ]);
        $build2 = factory(Build::class)->states(['public'])->create([
            'build_number' => '2.0.0',
            'minecraft_version' => '1.7.10',
            'modpack_id' => $this->modpack->id,
        ]);

        $response = $this->json('GET', "api/modpacks/{$this->modpack->id}/builds");

        $response->assertStatus(200);
        $response->assertExactJson([
            'data' => [
                [
                    'type' => 'build',
                    'id' => $build1->id,
                    'attributes' => [
                        'build_number' => '1.0.0',
                        'minecraft_version' => '1.7.10',
                        'state' => 'public',
                        'arguments' => [
                            'forge_version' => '10.2.4.1234',
                            'java_version' => '1.7',
                            'java_memory' => 1024,
                        ],
                    ],
                    'links' => [
                        'self' => "http://example.com/api/builds/{$build1->id}",
                    ],
                ],
                [
                    'type' => 'build',
                    'id' => $build2->id,
                    'attributes' => [
                        'build_number' => '2.0.0',
                        'minecraft_version' => '1.7.10',
                        'state' => 'public',
                        'arguments' => null,
                    ],
                    'links' => [
                        'self' => "http://example.com/api/builds/{$build2->id}",
                    ],
                ],
            ],
        ]);
    }

    /** @test */
    public function draft_and_private_builds_are_not_in_list()
    {
        factory(Build::class)->states(['draft'])->create(['modpack_id' => $this->modpack->id]);
        factory(Build::class)->states(['private'])->create(['modpack_id' => $this->modpack->id]);

        $response = $this->json('GET', "api/modpacks/{$this->modpack->id}/builds");

        $response->assertStatus(200);
        $response->assertJson(['data' => []]);
        $this->assertCount(0, $response->decodeResponseJson()['data']);
    }

    /** @test */
    public function private_modpack_requires_authentication()
    {
        $this->withExceptionHandling();
        $privateModpack = factory(Modpack::class)->states(['private'])->create();

        $response = $this->json('GET', "api/modpacks/{$privateModpack->id}/builds");

        $response->assertStatus(403);
    }

    /** @test */
    public function get_any_status_with_authentication()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        factory(Build::class)->states(['draft'])->create(['modpack_id' => $this->modpack->id]);
        factory(Build::class)->states(['private'])->create(['modpack_id' => $this->modpack->id]);
        factory(Build::class)->states(['public'])->create(['modpack_id' => $this->modpack->id]);

        $response = $this->json('GET', "api/modpacks/{$this->modpack->id}/builds");

        $response->assertStatus(200);
        $this->assertCount(3, $response->decodeResponseJson()['data']);
    }
}
