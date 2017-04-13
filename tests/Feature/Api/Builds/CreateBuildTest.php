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

use App\User;
use App\Build;
use App\Modpack;
use Tests\TestCase;
use Tests\Feature\Api\CreatesBuilds;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateBuildTest extends TestCase
{
    use DatabaseMigrations;
    use CreatesBuilds;

    /** @test */
    public function related_modpack_id_is_required()
    {
        $modpack = factory(Modpack::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->withExceptionHandling()
            ->postJson($this->validUri($modpack), [
            'data' => [
                'type' => 'build',
                'attributes' => [
                    'build_number' => '1.0.0',
                    'minecraft_version' => '1.7.10',
                    'state' => 'public',
                    'arguments' => [
                        'sample_argument' => 'some-data',
                    ],
                ],
            ],
        ]);

        $response->assertStatus(403);
        $this->assertEquals(0, Build::count());
    }

    /** @test */
    public function related_modpack_must_exist()
    {
        $modpack = factory(Modpack::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->withExceptionHandling()
            ->postJson($this->validUri($modpack), [
            'data' => [
                'type' => 'build',
                'attributes' => [
                    'build_number' => '1.0.0',
                    'minecraft_version' => '1.7.10',
                    'state' => 'public',
                    'arguments' => [
                        'sample_argument' => 'some-data',
                    ],
                ],
            ],
            'relationships' => [
                'modpack' => [
                    'data' => ['type' => 'modpack', 'id' => 'non-existent-modpack'],
                ],
            ],
        ]);

        $response->assertStatus(404);
        $this->assertEquals(0, Build::count());
    }

    /**
     * Get the uri for creating a build.
     *
     * @param Modpack $modpack
     *
     * @return string
     */
    public function validUri($modpack)
    {
        return 'api/builds';
    }

    /**
     * Get a valid creation payload.
     *
     * @param Modpack $modpack
     * @param array $attributes attributes overrides
     *
     * @return array
     */
    public function validPayload($modpack, $attributes = [])
    {
        return [
            'data' => [
                'type' => 'build',
                'attributes' => array_merge([
                    'build_number' => '1.0.0',
                    'minecraft_version' => '1.7.10',
                    'state' => 'public',
                    'arguments' => [
                        'sample_argument' => 'some-data',
                    ],
                ], $attributes),
            ],
            'relationships' => [
                'modpack' => [
                    'data' => ['type' => 'modpack', 'id' => $modpack->id],
                ],
            ],
        ];
    }
}
