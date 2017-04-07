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

use App\Token;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SupportsLegacyTokensTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function tokens_can_be_verified_through_the_api()
    {
        factory(Token::class)->create(['name' => 'Test Token', 'value' => 'TOKEN123']);

        $response = $this->get('api/verify/TOKEN123');

        $response->assertStatus(200);
        $response->assertExactJson([
            'name' => 'Test Token',
            'valid' => 'Key validated.',
        ]);
    }

    /** @test */
    public function invalid_tokens_return_an_error()
    {
        $this->disableExceptionHandling();
        $response = $this->get('api/verify/TOKEN123');

        $response->assertStatus(404);
        $response->assertExactJson([
            'status' => 404,
            'error' => 'Invalid key provided.',
        ]);
    }
}
