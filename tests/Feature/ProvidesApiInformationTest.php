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

use Tests\TestCase;

class ProvidesApiInformationTest extends TestCase
{
    /** @test */
    public function a_guest_can_get_api_version_information()
    {
        $response = $this->json('GET', 'api');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'api',
            'version',
            'stream',
        ]);
    }
}
