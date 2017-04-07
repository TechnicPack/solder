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

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_have_multiple_tokens()
    {
        $user = factory(User::class)->create();

        $user->addToken('First Token', 'TESTTOKEN123');
        $user->addToken('Second Token', 'TESTTOKEN456');

        $this->assertTrue($user->tokens->contains('value', 'TESTTOKEN123'));
        $this->assertTrue($user->tokens->contains('value', 'TESTTOKEN456'));
    }

    /** @test */
    public function can_be_retrieved_by_a_token()
    {
        $user = factory(User::class)->create()->addToken('Test Token', 'TESTTOKEN123');

        $retrievedUser = User::findByToken('TESTTOKEN123');

        $this->assertEquals($user->id, $retrievedUser->id);
    }

    /** @test */
    public function an_invalid_token_returns_null()
    {
        $retrievedUser = User::findByToken('INVALIDTOKEN');

        $this->assertNull($retrievedUser);
    }
}
