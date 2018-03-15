<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Unit\Resources;

use Carbon\Carbon;
use Tests\TestCase;
use Platform\Client;
use Illuminate\Http\Request;
use Platform\Http\Resources\ClientResource;

class ClientResourceTest extends TestCase
{
    /** @test **/
    public function to_array()
    {
        $client = factory(Client::class)->make([
            'id' => 1,
            'title' => 'Test Client',
            'token' => 'test-client-1234',
            'created_at' => Carbon::parse('January 4, 2018 8:22 am'),
        ]);

        $resource = new ClientResource($client);

        tap($resource->toArray(new Request), function ($response) {
            $this->assertArraySubset([
                'id' => 1,
                'title' => 'Test Client',
                'token' => 'test-client-1234',
                'created_at' => '2018-01-04T08:22:00+00:00',
            ], $response);
        });
    }
}
