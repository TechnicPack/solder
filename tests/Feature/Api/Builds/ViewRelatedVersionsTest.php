<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Feature\Api\Builds;

use App\User;
use App\Build;
use App\Version;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewRelatedVersionsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function get_versions_linked_to_a_public_build()
    {
        \Config::set('app.url', 'http://example.com');
        \Storage::shouldReceive('url', 'url')->andReturn('http://example.com/storage/filename.zip', 'http://example.com/storage/another-filename.zip');
        $build = factory(Build::class)->states(['public'])->create();
        $version1 = factory(Version::class)->create([
            'version_number' => '1.0.0',
            'zip_path' => 'filename.zip',
            'zip_md5' => 'md5hash1234',
        ]);
        $version2 = factory(Version::class)->create([
            'version_number' => '2.0.0',
            'zip_path' => 'another-filename.zip',
            'zip_md5' => 'md5hash5678',
        ]);
        $build->versions()->attach($version1);
        $build->versions()->attach($version2);

        $response = $this->json('GET', "api/builds/{$build->id}/versions");

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                [
                    'type' => 'version',
                    'id' => $version1->id,
                    'attributes' => [
                        'version_number' => '1.0.0',
                        'zip_url' => 'http://example.com/storage/filename.zip',
                        'zip_md5' => 'md5hash1234',
                    ],
                    'links' => [
                        'self' => "http://example.com/api/versions/{$version1->id}",
                    ],
                ],
                [
                    'type' => 'version',
                    'id' => $version2->id,
                    'attributes' => [
                        'version_number' => '2.0.0',
                        'zip_url' => 'http://example.com/storage/another-filename.zip',
                        'zip_md5' => 'md5hash5678',
                    ],
                    'links' => [
                        'self' => "http://example.com/api/versions/{$version2->id}",
                    ],
                ],
            ],
        ]);
    }

    /** @test */
    public function private_build_requires_authentication()
    {
        $this->withExceptionHandling();
        $build = factory(Build::class)->states(['private'])->create();

        $response = $this->json('GET', "api/builds/{$build->id}/versions");

        $response->assertStatus(403);
    }

    /** @test */
    public function private_build_with_authentication()
    {
        $this->actingAs(factory(User::class)->create());
        $build = factory(Build::class)->states(['private'])->create();

        $response = $this->json('GET', "api/builds/{$build->id}/versions");

        $response->assertStatus(200);
        $response->assertJson(['data' => []]);
    }

    /** @test */
    public function draft_build_requires_authentication()
    {
        $this->withExceptionHandling();
        $build = factory(Build::class)->states(['draft'])->create();

        $response = $this->json('GET', "api/builds/{$build->id}/versions");

        $response->assertStatus(403);
    }

    /** @test */
    public function draft_build_with_authentication()
    {
        $this->actingAs(factory(User::class)->create());
        $build = factory(Build::class)->states(['draft'])->create();

        $response = $this->json('GET', "api/builds/{$build->id}/versions");

        $response->assertStatus(200);
        $response->assertJson(['data' => []]);
    }
}
