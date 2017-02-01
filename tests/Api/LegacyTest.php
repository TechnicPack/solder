<?php
/*
 * This file is part of solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Api;

use App\Build;
use App\Token;
use App\Modpack;
use App\Version;
use App\Resource;
use Tests\TestCase;
use Tremby\LaravelGitVersion\GitVersionHelper;

class LegacyTest extends TestCase
{
    /** @test */
    public function it_verifies_tokens()
    {
        factory(Token::class)->create([
            'name' => 'Test Token',
            'token' => 'test-token',
        ]);

        // Valid Token
        $response = $this->json('GET', '/api/verify/test-token');
        $response->assertStatus(200)
            ->assertJson([
                'valid' => 'Key validated.',
            ]);

        // Invalid Token
        $response = $this->json('GET', '/api/verify/fake-token');
        $response->assertStatus(404);
    }

    /** @test */
    public function test_base_reply()
    {
        $response = $this->json('GET', 'api');

        $response->assertStatus(200)
            ->assertJson([
                'api' => 'TechnicSolder',
                'version' => GitVersionHelper::getVersion(),
                'stream' => config('app.env'),
            ]);
    }

    /** @test */
    public function it_returns_a_modpack_list()
    {
        factory(Modpack::class)->create();

        $response = $this->json('GET', 'api/modpack');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_returns_a_mod_list()
    {
        factory(Resource::class)->create();

        $response = $this->json('GET', 'api/mod');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_returns_a_404_on_invalid_modpack()
    {
        $response = $this->json('GET', 'api/modpack/bob');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_returns_a_modpack_by_slug()
    {
        factory(Modpack::class)->create(['slug' => 'test-modpack']);

        $response = $this->json('GET', 'api/modpack/test-modpack');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_returns_a_404_on_invalid_mod()
    {
        $response = $this->json('GET', 'api/mod/bob');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_returns_a_mod_by_slug()
    {
        factory(Resource::class)->create(['slug' => 'test-mod']);

        $response = $this->json('GET', 'api/mod/test-mod');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_returns_a_modpack_build_by_version()
    {
        factory(Build::class)->create([
            'version' => '1.0.0',
            'modpack_id' => factory(Modpack::class)->states(['public'])->create([
                'slug' => 'test-modpack',
            ])->id,
        ]);

        $response = $this->json('GET', 'api/modpack/test-modpack/1.0.0');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_returns_a_mod_version_by_version()
    {
        factory(Version::class)->create([
            'version' => '1.0.0',
            'resource_id' => factory(Resource::class)->create([
                'slug' => 'test-mod',
            ])->id,
        ]);

        $response = $this->json('GET', 'api/mod/test-mod/1.0.0');

        $response->assertStatus(200);
    }
}
