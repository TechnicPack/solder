<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Feature\Api\Modpacks;

use App\User;
use App\Modpack;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UpdateModpackTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function updating_a_new_modpack()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create([
            'name' => 'My First Modpack',
            'slug' => 'my-first-modpack',
            'status' => Modpack::STATUS_PUBLIC,
        ]);

        $response = $this->actingAs($user, 'api')
            ->patchJson($this->validUri($modpack), $this->validPayload($modpack, [
                'data.attributes.name' => 'My Revised Modpack',
                'data.attributes.slug' => 'my-revised-modpack',
                'data.attributes.status' => 'private',
            ]));

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'type' => 'modpack',
                'id' => $modpack->id,
                'attributes' => [
                    'name' => 'My Revised Modpack',
                    'slug' => 'my-revised-modpack',
                    'status' => 'private',
                ],
                'links' => [
                    'self' => url("/api/modpacks/{$modpack->id}"),
                ],
            ],
        ]);
//        $this->assertEquals('My Revised Modpack', $this->modpack->fresh()->name);
//        $this->assertEquals('my-revised-modpack', $this->modpack->fresh()->slug);
//        $this->assertEquals(Modpack::STATUS_PRIVATE, $this->modpack->fresh()->status);
    }

    /** @test */
    public function requires_authentication()
    {
        $modpack = factory(Modpack::class)->create();

        $response = $this->withExceptionHandling()
            ->patchJson($this->validUri($modpack), $this->validPayload($modpack));

        $response->assertStatus(401);
    }

    /** @test */
    public function name_must_be_filled()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['name' => 'My First Modpack']);

        $response = $this->actingAs($user, 'api')
            ->withExceptionHandling()
            ->patchJson($this->validUri($modpack), $this->validPayload($modpack, [
                'data.attributes.name' => null,
            ]));

        $response->assertStatus(422);
        $this->assertEquals('My First Modpack', $modpack->fresh()->name);
    }

    /** @test */
    public function slug_must_be_unique()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'my-first-modpack']);
        factory(Modpack::class)->create(['slug' => 'existing-slug']);

        $response = $this->actingAs($user, 'api')
            ->withExceptionHandling()
            ->patchJson($this->validUri($modpack), $this->validPayload($modpack, [
                'data.attributes.slug' => 'existing-slug',
            ]));

        $response->assertStatus(422);
        $this->assertEquals('my-first-modpack', $modpack->fresh()->slug);
    }

    /** @test */
    public function can_submit_same_slug_back()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'my-first-modpack']);

        $response = $this->actingAs($user, 'api')
            ->withExceptionHandling()
            ->patchJson($this->validUri($modpack), $this->validPayload($modpack, [
                'data.attributes.slug' => 'my-first-modpack',
            ]));

        $response->assertStatus(200);
        $this->assertEquals('my-first-modpack', $modpack->fresh()->slug);
    }

    /** @test */
    public function modpack_must_exist()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->withExceptionHandling()
            ->patchJson('api/modpacks/invalid-id', []);

        $response->assertStatus(404);
    }

    /** @test */
    public function payload_must_be_a_modpack_resource()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create();

        $response = $this->actingAs($user, 'api')
            ->withExceptionHandling()
            ->patchJson($this->validUri($modpack), $this->validPayload($modpack, [
                'data.type' => 'foobar',
            ]));

        $response->assertStatus(409);
    }

    /** @test */
    public function payload_id_must_match_endpoint()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create();

        $response = $this->actingAs($user, 'api')
            ->withExceptionHandling()
            ->patchJson($this->validUri($modpack), $this->validPayload($modpack, [
                'data.id' => 'wrong-id',
            ]));

        $response->assertStatus(409);
    }

    /**
     * @param $modpack
     * @param array $overrides
     *
     * @return array
     */
    private function validPayload($modpack, $overrides = []): array
    {
        $payload = [
            'data' => [
                'type' => 'modpack',
                'id' => $modpack->id,
                'attributes' => [
                ],
            ],
        ];

        collect($overrides)->each(function ($item, $key) use (&$payload) {
            array_set($payload, $key, $item);
        })->toArray();

        return $payload;
    }

    /**
     * @return string
     */
    private function validUri($modpack): string
    {
        return "api/modpacks/{$modpack->id}";
    }
}
