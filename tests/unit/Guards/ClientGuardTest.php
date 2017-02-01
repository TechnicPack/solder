<?php

namespace Tests\unit\Guards;

/*
 * This file is part of solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use App\User;
use App\Token;
use App\Guards\LegacyTokenGuard;
use Illuminate\Http\Request;
use Illuminate\Auth\CreatesUserProviders;
use Tests\TestCase;

class ClientGuardTest extends TestCase
{
    use CreatesUserProviders;

    /** @test */
    public function user_can_be_retrieved_by_query_string_parameter()
    {
        $client = factory(Token::class)->create(['token' => 'test-token']);
        $provider = $this->createUserProvider('users');

        // Test using CID parameter (Technic Launcher)
        $request = Request::create('/', 'GET', ['cid' => 'test-token']);
        $guard = new LegacyTokenGuard($provider, $request);

        $user = $guard->user();

        $this->assertEquals($client->user->id, $user->id);
        $this->assertTrue($guard->check());
        $this->assertFalse($guard->guest());
        $this->assertEquals($client->user->id, $guard->id());

        // Test using K parameter (Technic Platform)
        $request = Request::create('/', 'GET', ['k' => 'test-token']);
        $guard = new LegacyTokenGuard($provider, $request);

        $user = $guard->user();

        $this->assertEquals($client->user->id, $user->id);
        $this->assertTrue($guard->check());
        $this->assertFalse($guard->guest());
        $this->assertEquals($client->user->id, $guard->id());
    }

    /** @test */
    public function validate_can_determine_if_credentials_are_valid()
    {
        factory(Token::class)->create(['token' => 'test-token']);
        $provider = $this->createUserProvider('users');
        $request = Request::create('/', 'GET');
        $guard = new LegacyTokenGuard($provider, $request);

        // Test using CID parameter (Technic Launcher)
        $this->assertTrue($guard->validate(['cid' => 'test-token']));
        $this->assertFalse($guard->validate(['cid' => 'wrong-token']));
        $this->assertFalse($guard->validate(['cid' => null]));
        $this->assertFalse($guard->validate([]));

        // Test using K parameter (Technic Platform)
        $this->assertTrue($guard->validate(['k' => 'test-token']));
        $this->assertFalse($guard->validate(['k' => 'wrong-token']));
        $this->assertFalse($guard->validate(['k' => null]));
        $this->assertFalse($guard->validate([]));
    }

    /** @test */
    public function validate_if_token_is_empty()
    {
        factory(Token::class)->create(['token' => 'test-token']);
        $provider = $this->createUserProvider('users');

        // Test using CID parameter (Technic Launcher)
        $request = Request::create('/', 'GET', ['cid' => '']);
        $guard = new LegacyTokenGuard($provider, $request);

        $this->assertNull($guard->user());
        $this->assertFalse($guard->check());
        $this->assertTrue($guard->guest());
        $this->assertNull($guard->id());

        // Test using K parameter (Technic Platform)
        $request = Request::create('/', 'GET', ['k' => '']);
        $guard = new LegacyTokenGuard($provider, $request);

        $this->assertNull($guard->user());
        $this->assertFalse($guard->check());
        $this->assertTrue($guard->guest());
        $this->assertNull($guard->id());
    }
}

class ClientGuardTestUser
{
    public $id;

    public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function getAuthIdentifier()
    {
        return $this->id;
    }
}
