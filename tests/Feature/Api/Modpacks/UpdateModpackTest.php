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

    protected $modpack;
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->modpack = factory(Modpack::class)->create([
            'name' => 'My First Modpack',
            'slug' => 'my-first-modpack',
            'status' => Modpack::STATUS_PUBLIC,
        ]);
        $this->user = factory(User::class)->create();
        \Config::set('app.url', 'http://example.com');
    }

    /** @test */
    public function updating_a_new_modpack()
    {
        $response = $this->actingAs($this->user)->patchJson("api/modpacks/{$this->modpack->id}", $this->emptyResource([
            'name' => 'My Revised Modpack',
            'slug' => 'my-revised-modpack',
            'status' => 'private',
        ]));

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'type' => 'modpack',
                'id' => $this->modpack->id,
                'attributes' => [
                    'name' => 'My Revised Modpack',
                    'slug' => 'my-revised-modpack',
                    'status' => 'private',
                ],
                'links' => [
                    'self' => "http://example.com/api/modpacks/{$this->modpack->id}",
                ],
            ],
        ]);
        $this->assertEquals('My Revised Modpack', $this->modpack->fresh()->name);
        $this->assertEquals('my-revised-modpack', $this->modpack->fresh()->slug);
        $this->assertEquals(Modpack::STATUS_PRIVATE, $this->modpack->fresh()->status);
    }

    /** @test */
    public function requires_authentication()
    {
        $this->withExceptionHandling();

        $response = $this->patchJson("api/modpacks/{$this->modpack->id}", $this->emptyResource([
            'name' => 'My Revised Modpack',
        ]));

        $response->assertStatus(401);
        $this->assertEquals('My First Modpack', $this->modpack->fresh()->name);
    }

    /** @test */
    public function name_must_be_filled()
    {
        $this->withExceptionHandling();

        $response = $this->actingAs($this->user)->patchJson("api/modpacks/{$this->modpack->id}", $this->emptyResource([
            'name' => null,
        ]));

        $response->assertStatus(422);
        $this->assertEquals('My First Modpack', $this->modpack->fresh()->name);
    }

    /** @test */
    public function slug_must_be_unique()
    {
        $this->withExceptionHandling();
        factory(Modpack::class)->create(['slug' => 'existing-slug']);

        $response = $this->actingAs($this->user)->patchJson("api/modpacks/{$this->modpack->id}", $this->emptyResource([
            'slug' => 'existing-slug',
        ]));

        $response->assertStatus(422);
        $this->assertEquals('my-first-modpack', $this->modpack->fresh()->slug);
    }

    /** @test */
    public function can_submit_same_slug_back()
    {
        $this->withExceptionHandling();

        $response = $this->actingAs($this->user)->patchJson("api/modpacks/{$this->modpack->id}", $this->emptyResource([
            'slug' => 'my-first-modpack',
        ]));

        $response->assertStatus(200);
        $this->assertEquals('my-first-modpack', $this->modpack->fresh()->slug);
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    private function emptyResource($attributes = []): array
    {
        return [
            'data' => [
                'type' => 'modpack',
                'id' => $this->modpack->id,
                'attributes' => $attributes,
            ],
        ];
    }
}
