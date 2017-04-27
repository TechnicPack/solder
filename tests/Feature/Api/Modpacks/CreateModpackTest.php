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
use App\Facades\Uuid;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateModpackTest extends TestCase
{
    use DatabaseMigrations;

    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        \Config::set('app.url', 'http://example.com');
    }

    /** @test */
    public function adding_a_new_modpack()
    {
        Uuid::shouldReceive('generate')->andReturn('000000000-0000-4000-A000-000000000000');

        $response = $this->actingAs($this->user, 'api')->postJson('api/modpacks', $this->validPayload([
            'name' => 'My First Modpack',
            'slug' => 'my-first-modpack',
            'status' => 'public',
        ]));

        $response->assertStatus(201);
        $response->assertHeader('Location', 'http://example.com/api/modpacks/000000000-0000-4000-A000-000000000000');
        $response->assertJson([
            'data' => [
                'type' => 'modpack',
                'id' => '000000000-0000-4000-A000-000000000000',
                'attributes' => [
                    'name' => 'My First Modpack',
                    'slug' => 'my-first-modpack',
                    'status' => 'public',
                ],
                'links' => [
                    'self' => 'http://example.com/api/modpacks/000000000-0000-4000-A000-000000000000',
                ],
            ],
        ]);
        $this->assertDatabaseHas('modpacks', [
            'id' => '000000000-0000-4000-A000-000000000000',
            'name' => 'My First Modpack',
            'slug' => 'my-first-modpack',
            'status' => Modpack::STATUS_PUBLIC,
        ]);
    }

    /** @test */
    public function requires_authentication()
    {
        $this->withExceptionHandling();

        $response = $this->postJson('api/modpacks', $this->validPayload());

        $response->assertStatus(401);
        $this->assertEquals(0, Modpack::count());
    }

    /** @test */
    public function name_is_required()
    {
        $this->withExceptionHandling();

        $response = $this->actingAs($this->user, 'api')->postJson('api/modpacks', $this->validPayload([
            'data.attributes.name' => null,
        ]));

        $response->assertStatus(422);
        $this->assertEquals(0, Modpack::count());
    }

    /** @test */
    public function slug_must_be_unique()
    {
        $this->withExceptionHandling();
        factory(Modpack::class)->create(['slug' => 'existing-slug']);
        $this->assertEquals(1, Modpack::count());

        $response = $this->actingAs($this->user, 'api')->postJson('api/modpacks', $this->validPayload([
            'data.attributes.slug' => 'existing-slug',
        ]));

        $response->assertStatus(422);
        $this->assertEquals(1, Modpack::count());
    }

    /** @test */
    public function resource_type_must_be_valid()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->withExceptionHandling()
            ->postJson('api/modpacks', $this->validPayload([
                'data.type' => 'foobar',
            ]));

        $response->assertStatus(409);
        $this->assertEquals(0, Modpack::count());
    }

    /** @test */
    public function builds_do_not_support_client_generated_ids()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->withExceptionHandling()
            ->postJson('api/modpacks', $this->validPayload([
                'data.id' => 'client-generated-id',
            ]));

        $response->assertStatus(403);
        $this->assertEquals(0, Modpack::count());
    }

    /**
     * @param array $overrides
     *
     * @return array
     */
    private function validPayload($overrides = []): array
    {
        $payload = [
            'data' => [
                'type' => 'modpack',
                'attributes' => [
                    'name' => 'My First Modpack',
                    'slug' => 'my-first-modpack',
                    'status' => 'public',
                ],
            ],
        ];

        collect($overrides)->each(function ($item, $key) use (&$payload) {
            array_set($payload, $key, $item);
        })->toArray();

        return $payload;
    }
}
