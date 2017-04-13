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

use App\Modpack;
use Tests\TestCase;
use Tests\Feature\Api\CreatesBuilds;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateRelatedBuildTest extends TestCase
{
    use DatabaseMigrations;
    use CreatesBuilds;

    /**
     * Get the uri for creating a build.
     *
     * @param Modpack $modpack
     *
     * @return string
     */
    public function validUri($modpack)
    {
        return "api/modpacks/{$modpack->id}/builds";
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
        ];
    }
}
