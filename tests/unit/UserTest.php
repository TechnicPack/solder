<?php

namespace Tests\unit;


/*
 * This file is part of Solder Framework.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use App\Token;
use App\User;
use Illuminate\Database\Eloquent\Collection;

class UserTest extends TestCase
{
    /** @test */
    public function has_many_legacy_tokens()
    {
        $user = factory(User::class)->create();
        $client = factory(Token::class)->create([
            'user_id' => $user->id,
        ]);

        $clients = $user->legacyTokens;

        $this->assertInstanceOf(Collection::class, $clients);
        $this->assertTrue($clients->contains($client));
    }
}
