<?php

namespace Tests\endpoints;


/*
 * This file is part of solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use App\Build;
use App\Modpack;
use App\Resource;
use App\Version;
use Tremby\LaravelGitVersion\GitVersionHelper;

class LegacyApiTest extends TestCase
{
    /** @test */
    function it_verifies_tokens()
    {
        factory(\App\Token::class)->create([
            'name' => 'Test Token',
            'token' => 'test-token',
        ]);

        $this->getJson('/api/verify/test-token');

        $this->assertResponseOk();
        $this->assertJson([
            'name' => 'Test Token',
            'valid' => 'Key validated.',
        ]);

        $this->get('/api/verify/fake_token');

        $this->assertStatus(404);
        $this->isJson();
    }

    /** @test */
    function test_base_reply()
    {
        $this->getJson('api');

        $this->assertResponseOk();
        $this->seeJson();
        $this->assertJson([
            'api' => 'TechnicSolder',
            'version' => GitVersionHelper::getVersion(),
            'stream' => config('app.env'),
        ]);
    }

    /** @test */
    function it_returns_a_modpack_list()
    {
        factory(Modpack::class)->create();

        $this->getJson('api/modpack');

        $this->assertResponseOk();
        $this->seeJson();
        $this->seeJsonStructure(['modpacks', 'mirror_url']);
    }

    /** @test */
    function it_returns_a_mod_list()
    {
        factory(Resource::class)->create();

        $this->getJson('api/mod');

        $this->assertResponseOk();
        $this->seeJson();
        $this->seeJsonStructure(['mods']);
    }

    /** @test */
    function it_returns_a_404_on_invalid_modpack()
    {
        $this->getJson('api/modpack/bob');

        $this->assertStatus(404);
        $this->seeJson();
        $this->seeJsonStructure(['error']);
    }

    /** @test */
    function it_returns_a_modpack_by_slug()
    {
        factory(Modpack::class)->states(['public'])->create(['slug' => 'test-modpack']);

        $this->getJson('api/modpack/test-modpack');

        $this->assertResponseOk();
        $this->seeJson();
        $this->seeJsonStructure([
            'name',
            'display_name',
            'url',
            'icon',
            'icon_md5',
            'latest',
            'logo',
            'logo_md5',
            'recommended',
            'background',
            'background_md5',
            'builds',
        ]);
    }

    /** @test */
    function it_returns_a_404_on_invalid_mod()
    {
        $this->getJson('api/mod/bob');

        $this->assertStatus(404);
        $this->seeJson();
        $this->seeJsonStructure(['error']);
    }

    /** @test */
    function it_returns_a_mod_by_slug()
    {
        factory(Resource::class)->create(['slug' => 'test-mod']);

        $this->getJson('api/mod/test-mod');

        $this->assertResponseOk();
        $this->seeJson();
        $this->seeJsonStructure([
            'name',
            'pretty_name',
            'author',
            'description',
            'link',
            'donate',
            'versions',
        ]);
    }

    /** @test */
    function it_returns_a_modpack_build_by_version()
    {
        factory(Build::class)->states(['public'])->create([
            'version' => '1.0.0',
            'modpack_id' => factory(Modpack::class)->states(['public'])->create([
                'slug' => 'test-modpack',
            ])->id,
        ]);

        $this->getJson('api/modpack/test-modpack/1.0.0');

        $this->assertResponseOk();
        $this->seeJson();
        $this->seeJsonStructure([
            'minecraft',
            'forge',
            'java',
            'memory',
            'mods',
        ]);
    }

    /** @test */
    function it_returns_a_mod_version_by_version()
    {
        factory(Version::class)->create([
            'version' => '1.0.0',
            'resource_id' => factory(Resource::class)->create([
                'slug' => 'test-mod',
            ])->id,
        ]);

        $this->getJson('api/mod/test-mod/1.0.0');

        $this->assertResponseOk();
        $this->seeJson();
        $this->seeJsonStructure([
            'md5',
            'filesize',
            'url',
        ]);
    }
}
