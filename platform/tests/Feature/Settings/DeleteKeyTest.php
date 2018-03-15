<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Feature\Settings;

use Platform\Key;
use Tests\TestCase;
use Tests\TestUser;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteKeyTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function delete_a_key()
    {
        $this->actingAs(new TestUser);
        $this->authorizeAbility('keys.delete');
        $key = factory(Key::class)->create();
        $this->assertCount(1, Key::all());

        $response = $this->deleteJson("/settings/keys/tokens/{$key->id}");

        $response->assertStatus(204);
        $this->assertCount(0, Key::all());
    }

    /** @test **/
    public function unauthenticated_requests_are_dropped()
    {
        $this->authorizeAbility('keys.delete');
        $key = factory(Key::class)->create();
        $this->assertCount(1, Key::all());

        $response = $this->deleteJson("/settings/keys/tokens/{$key->id}");

        $response->assertStatus(401);
        $this->assertCount(1, Key::all());
    }

    /** @test **/
    public function invalid_requests_are_dropped()
    {
        $this->actingAs(new TestUser);
        $this->authorizeAbility('keys.delete');
        factory(Key::class)->create();
        $this->assertCount(1, Key::all());

        $response = $this->deleteJson('/settings/keys/tokens/99');

        $response->assertStatus(404);
        $this->assertCount(1, Key::all());
    }

    /** @test **/
    public function unauthorized_requests_are_forbidden()
    {
        $this->actingAs(new TestUser);
        $this->denyAbility('keys.delete');
        $key = factory(Key::class)->create();
        $this->assertCount(1, Key::all());

        $response = $this->deleteJson("/settings/keys/tokens/{$key->id}");

        $response->assertStatus(403);
        $this->assertCount(1, Key::all());
    }
}
