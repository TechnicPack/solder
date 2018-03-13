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

use App\User;
use Platform\Key;
use Tests\TestCase;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteKeyTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function delete_a_key()
    {
        $this->withoutAuthorization();
        $user = factory(User::class)->create();
        $key = factory(Key::class)->create();
        $this->assertCount(1, Key::all());

        $response = $this->actingAs($user)
            ->deleteJson("/settings/keys/tokens/{$key->id}");

        $response->assertStatus(204);
        $this->assertCount(0, Key::all());
    }

    /** @test **/
    public function unauthenticated_requests_are_dropped()
    {
        $this->withoutAuthorization();
        $key = factory(Key::class)->create();
        $this->assertCount(1, Key::all());

        $response = $this
            ->deleteJson("/settings/keys/tokens/{$key->id}");

        $response->assertStatus(401);
        $this->assertCount(1, Key::all());
    }

    /** @test **/
    public function invalid_requests_are_dropped()
    {
        $this->withoutAuthorization();
        $user = factory(User::class)->create();
        factory(Key::class)->create();
        $this->assertCount(1, Key::all());

        $response = $this->actingAs($user)
            ->deleteJson('/settings/keys/tokens/99');

        $response->assertStatus(404);
        $this->assertCount(1, Key::all());
    }

    /** @test **/
    public function unauthorized_requests_are_forbidden()
    {
        Gate::define('keys.delete',function () {
            return false;
        });
        $user = factory(User::class)->create();
        $key = factory(Key::class)->create();
        $this->assertCount(1, Key::all());

        $response = $this->actingAs($user)
            ->deleteJson("/settings/keys/tokens/{$key->id}");

        $response->assertStatus(403);
        $this->assertCount(1, Key::all());
    }

    /**
     * Authorize all actions, effectively disabling authorization checks.
     */
    protected function withoutAuthorization(): void
    {
        Gate::define('keys.delete',function () {
            return true;
        });
    }
}
