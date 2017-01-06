<?php

/*
 * This file is part of solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use App\Token;
use App\User;

class Test extends TestCase
{
    /** @test */
    public function belongs_to_a_user()
    {
        $user = factory(User::class)->create();
        $client = factory(Token::class)->create([
            'user_id' => $user->id,
        ]);

        $returnedClient = $client->user;

        $this->assertInstanceOf(User::class, $returnedClient);
        $this->assertEquals($user->id, $returnedClient->id);
    }
}

