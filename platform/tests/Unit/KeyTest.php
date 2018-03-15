<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Unit;

use Platform\Key;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KeyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function tokens_can_be_validated()
    {
        factory(Key::class)->create([
            'token' => 'APIKEY1234',
        ]);

        $this->assertTrue(Key::isValid('APIKEY1234'));
        $this->assertFalse(Key::isValid('INVALIDKEY'));
    }
}
