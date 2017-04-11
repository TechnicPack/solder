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
use App\Resource;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewResourceListingTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function get_resource_list()
    {
        \Config::set('app.url', 'http://example.com');
        $this->actingAs(factory(User::class)->create());
        $resource1 = factory(Resource::class)->create([
            'name' => 'Iron Tanks',
            'slug' => 'iron-tanks',
            'author' => 'Indemnity83',
            'description' => 'Upgradable Buildcraft tanks.',
            'link' => 'https://mods.curse.com/mc-mods/minecraft/236226-iron-tanks',
            'donate' => 'http://some-donate-link.com',
        ]);
        $resource2 = factory(Resource::class)->create([
            'name' => 'Iron Chests',
            'slug' => 'iron-chests',
            'author' => 'progwml6',
            'description' => 'Iron Chests mod has chests for the various metals in vanilla minecraft with varying sizes!',
            'link' => 'https://mods.curse.com/mc-mods/minecraft/228756-iron-chests',
            'donate' => 'http://donate.somewhere.com',
        ]);

        $response = $this->json('GET', 'api/resources');

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                [
                    'type' => 'resource',
                    'id' => $resource1->id,
                    'attributes' => [
                        'name' => 'Iron Tanks',
                        'slug' => 'iron-tanks',
                        'author' => 'Indemnity83',
                        'description' => 'Upgradable Buildcraft tanks.',
                        'link' => 'https://mods.curse.com/mc-mods/minecraft/236226-iron-tanks',
                        'donate' => 'http://some-donate-link.com',
                    ],
                    'link' => [
                        'self' => "http://example.com/api/resources/{$resource1->id}",
                    ],
                ],
                [
                    'type' => 'resource',
                    'id' => $resource2->id,
                    'attributes' => [
                        'name' => 'Iron Chests',
                        'slug' => 'iron-chests',
                        'author' => 'progwml6',
                        'description' => 'Iron Chests mod has chests for the various metals in vanilla minecraft with varying sizes!',
                        'link' => 'https://mods.curse.com/mc-mods/minecraft/228756-iron-chests',
                        'donate' => 'http://donate.somewhere.com',
                    ],
                    'link' => [
                        'self' => "http://example.com/api/resources/{$resource2->id}",
                    ],
                ],
            ],
        ]);
    }

    /** @test */
    public function index_requires_authentication()
    {
        $response = $this->json('GET', 'api/resources');

        $response->assertStatus(401);
    }

    /** @test */
    public function get_resource_details()
    {
        $this->disableExceptionHandling();
        \Config::set('app.url', 'http://example.com');
        $this->actingAs(factory(User::class)->create());
        $resource = factory(Resource::class)->create([
            'name' => 'Iron Tanks',
            'slug' => 'iron-tanks',
            'author' => 'Indemnity83',
            'description' => 'Upgradable Buildcraft tanks.',
            'link' => 'https://mods.curse.com/mc-mods/minecraft/236226-iron-tanks',
            'donate' => 'http://some-donate-link.com',
        ]);

        $response = $this->json('GET', "api/resources/{$resource->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'type' => 'resource',
                'id' => $resource->id,
                'attributes' => [
                    'name' => 'Iron Tanks',
                    'slug' => 'iron-tanks',
                    'author' => 'Indemnity83',
                    'description' => 'Upgradable Buildcraft tanks.',
                    'link' => 'https://mods.curse.com/mc-mods/minecraft/236226-iron-tanks',
                    'donate' => 'http://some-donate-link.com',
                ],
                'link' => [
                    'self' => "http://example.com/api/resources/{$resource->id}",
                ],
            ],
        ]);
    }

    /** @test */
    public function show_requires_authentication()
    {
        $response = $this->json('GET', 'api/resources/1');

        $response->assertStatus(401);
    }
}
