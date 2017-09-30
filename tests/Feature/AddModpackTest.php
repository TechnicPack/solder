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

use App\User;
use App\Modpack;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddModpackTest extends TestCase
{
    use RefreshDatabase;

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'Iron Tanks',
            'slug' => 'iron-tanks',
            'status' => 'public',
        ], $overrides);
    }

    /** @test */
    public function a_user_can_create_a_modpack()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post('/modpacks', [
            'name' => 'Iron Tanks',
            'slug' => 'iron-tanks',
            'status' => 'public',
        ]);

        tap(Modpack::first(), function ($modpack) use ($response) {
            $response->assertRedirect('/modpacks/iron-tanks');

            $this->assertEquals('Iron Tanks', $modpack->name);
            $this->assertEquals('iron-tanks', $modpack->slug);
            $this->assertEquals('public', $modpack->status);
        });
    }

    /** @test */
    public function a_guest_cannot_create_a_modpack()
    {
        $response = $this->post('/modpacks', [
            'name' => 'Iron Tanks',
            'slug' => 'iron-tanks',
            'status' => 'public',
        ]);

        $response->assertRedirect('/login');
        $this->assertEquals(0, Modpack::count());
    }

    /** @test */
    public function name_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/dashboard')->post('/modpacks', $this->validParams([
            'name' => '',
        ]));

        $response->assertRedirect('/dashboard');
        $response->assertSessionHasErrors('name');
        $this->assertEquals(0, Modpack::count());
    }

    /** @test */
    public function slug_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/dashboard')->post('/modpacks', $this->validParams([
            'slug' => '',
        ]));

        $response->assertRedirect('/dashboard');
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(0, Modpack::count());
    }

    /** @test */
    public function slug_is_unique()
    {
        $user = factory(User::class)->create();
        factory(Modpack::class)->create(['slug' => 'existing-slug']);

        $response = $this->actingAs($user)->from('/dashboard')->post('/modpacks', $this->validParams([
            'slug' => 'existing-slug',
        ]));

        $response->assertRedirect('/dashboard');
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(1, Modpack::count());
    }

    /** @test */
    public function slug_is_url_safe()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/dashboard')->post('/modpacks', $this->validParams([
            'slug' => 'non url $safe slug',
        ]));

        $response->assertRedirect('/dashboard');
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(0, Modpack::count());
    }

    /** @test */
    public function status_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/dashboard')->post('/modpacks', $this->validParams([
            'status' => '',
        ]));

        $response->assertRedirect('/dashboard');
        $response->assertSessionHasErrors('status');
        $this->assertEquals(0, Modpack::count());
    }

    /** @test */
    public function status_is_valid()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/dashboard')->post('/modpacks', $this->validParams([
            'status' => 'invalid',
        ]));

        $response->assertRedirect('/dashboard');
        $response->assertSessionHasErrors('status');
        $this->assertEquals(0, Modpack::count());
    }
}
