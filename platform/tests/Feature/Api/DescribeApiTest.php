<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Feature\Api;

use Tests\TestCase;

class DescribeApiTest extends TestCase
{
    /** @test **/
    public function describe_the_api()
    {
        config(['platform.provider' => 'Custom Provider']);
        config(['platform.version' => '1.2.3']);
        config(['platform.stream' => 'test']);

        $response = $this->getJson('/api');

        $response->assertStatus(200);
        $response->assertExactJson([
            'api' => 'Custom Provider',
            'version' => '1.2.3',
            'stream' => 'test',
        ]);
    }
}
