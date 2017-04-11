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

use Tests\TestCase;
use App\WebpatserUuidGenerator;

class WebpatserUuidGeneratorTest extends TestCase
{
    /** @test */
    public function ids_are_36_characters_long()
    {
        $generator = new WebpatserUuidGenerator();

        $id = $generator->generate();

        $this->assertTrue(strlen($id) == 36);
    }

    /** @test */
    public function ids_are_returned_as_a_string()
    {
        $generator = new WebpatserUuidGenerator();

        $id = $generator->generate();

        $this->assertTrue(is_string($id));
    }

    /** @test */
    public function ids_are_valid_uuid_4_format()
    {
        $generator = new WebpatserUuidGenerator();

        $id = $generator->generate();

        $this->assertRegExp('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $id);
    }
}
