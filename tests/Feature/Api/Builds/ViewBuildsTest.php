<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Feature\Api\Builds;

use App\Build;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewBuildsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function get_public_build_details()
    {
        $build = factory(Build::class)->states(['public'])->create([
            'build_number' => '1.0.0',
            'minecraft_version' => '1.7.10',
            'arguments' => [
                'forge_version' => '10.2.4.1234',
                'java_version' => '1.7',
                'java_memory' => 1024,
            ],
        ]);

        $response = $this->json('GET', 'api/builds/'.$build->id);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'type' => 'build',
                'id' => $build->id,
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
                    'self' => url("/api/builds/{$build->id}"),
                ],
            ],
        ]);
    }

    /** @test */
    public function private_build_details_requires_authentication()
    {
        $this->withExceptionHandling();
        $build = factory(Build::class)->states(['private'])->create();

        $response = $this->json('GET', 'api/builds/'.$build->id);

        $response->assertStatus(403);
    }

    /** @test */
    public function draft_build_details_requires_authentication()
    {
        $this->withExceptionHandling();
        $build = factory(Build::class)->states(['draft'])->create();

        $response = $this->json('GET', 'api/builds/'.$build->id);

        $response->assertStatus(403);
    }
}
