<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiModpackTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function response_includes_mirror_url()
    {
        $this->getJson('/api/modpack')
            ->assertResponseOk()
            ->seeJsonSubset([
                'mirror_url' => config('app.url'),
            ]);
    }

    /** @test */
    public function it_lists_public_modpacks()
    {
        $modpack = factory(App\Modpack::class)->create([
            'published' => true,
        ]);

        $this->getJson('/api/modpack')
            ->assertResponseOk()
            ->seeJsonSubset([
                'modpacks' => [
                    $modpack->slug => $modpack->name,
                ]
            ]);
    }

    /** @test */
    public function it_hides_unpublished_modpacks()
    {
        factory(App\Modpack::class)->create([
            'published' => false,
        ]);

        $this->getJson('/api/modpack')
            ->assertResponseOk()
            ->seeJsonEquals([
                'mirror_url' => config('app.url'),
                'modpacks' => [],
            ]);
    }

    /** @test */
    public function it_lists_unpublished_modpacks_with_global_token()
    {
        $modpack = factory(App\Modpack::class)->create([
            'published' => false,
        ]);

        $client = factory(App\Client::class)->create([
            'is_global' => true,
        ]);

        $this->getJson('/api/modpack?k='.$client->token)
            ->assertResponseOk()
            ->seeJsonSubset([
                'modpacks' => [
                    $modpack->slug => $modpack->name,
                ]
            ]);
    }

    /** @test */
    public function it_lists_unpublished_modpacks_with_allowed_client()
    {
        $modpack = factory(App\Modpack::class)->create([
            'published' => false,
        ]);

        $client = factory(App\Client::class)->create([
            'is_global' => false,
        ]);

        $modpack->allow($client);

        $this->getJson('/api/modpack?cid='.$client->token)
            ->assertResponseOk()
            ->seeJsonSubset([
                'modpacks' => [
                    $modpack->slug => $modpack->name,
                ]
            ]);
    }

    /** @test */
    public function it_lists_detailed_results_with_full_include_parameter()
    {
        $modpack = factory(App\Modpack::class)->create([
            'published' => true,
        ]);

        $this->getJson('/api/modpack?include=full')
            ->assertResponseOk()
            ->seeJsonSubset([
                'modpacks' => [
                    $modpack->slug => [
                        'name' => $modpack->slug,
                        'display_name' => $modpack->name,
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_shows_modpack()
    {
        $modpack = factory(App\Modpack::class)->create([
            'published' => true,
        ]);

        $this->getJson('/api/modpack/'.$modpack->slug)
            ->assertResponseOk()
            ->seeJsonSubset([
                'name' => $modpack->slug,
                'display_name' => $modpack->name,
            ]);
    }

    /** @test */
    public function it_shows_modpack_to_global_client()
    {
        $modpack = factory(App\Modpack::class)->create([
            'published' => false,
        ]);

        $client = factory(App\Client::class)->create([
            'is_global' => true,
        ]);

        $this->getJson('/api/modpack/'.$modpack->slug.'?k='.$client->token)
            ->assertResponseOk()
            ->seeJsonSubset([
                'name' => $modpack->slug,
                'display_name' => $modpack->name,
            ]);
    }

    /** @test */
    public function it_shows_modpack_to_allowed_client()
    {
        $modpack = factory(App\Modpack::class)->create([
            'published' => false,
        ]);

        $client = factory(App\Client::class)->create([
            'is_global' => false,
        ]);

        $modpack->allow($client);

        $this->getJson('/api/modpack/'.$modpack->slug.'?cid='.$client->token)
            ->assertResponseOk()
            ->seeJsonSubset([
                'name' => $modpack->slug,
                'display_name' => $modpack->name,
            ]);
    }

    /** @test */
    public function it_shows_an_error_for_invalid_modpack()
    {
        $this->getJson('/api/modpack/fake')
            ->assertResponseStatus(404)
            ->seeJsonEquals([
                'error' => 'Modpack does not exist/Build does not exist',
            ]);
    }

    /** @test */
    public function it_shows_an_error_for_unpublished_modpack()
    {
        $modpack = factory(App\Modpack::class)->create([
            'published' => false,
        ]);

        $this->getJson('/api/modpack/'.$modpack->slug)
            ->assertResponseStatus(404)
            ->seeJsonEquals([
                'error' => 'Modpack does not exist/Build does not exist',
            ]);
    }

    /** @test */
    public function it_shows_a_build()
    {
        $modpack = factory(App\Modpack::class)->create([
            'published' => true,
        ]);

        $build = factory(App\Build::class)->create([
            'modpack_id' => $modpack->id,
            'published' => true,
        ]);

        $this->getJson('/api/modpack/'.$modpack->slug.'/'.$build->version)
            ->assertResponseOk()
            ->seeJsonStructure([
                'minecraft',
                'forge',
                'mods',
            ]);
    }

    /** @test */
    public function it_hides_a_build_if_the_modpack_isnt_published()
    {
        $modpack = factory(App\Modpack::class)->create([
            'published' => false,
        ]);

        $build = factory(App\Build::class)->create([
            'modpack_id' => $modpack->id,
            'published' => true,
        ]);

        $this->getJson('/api/modpack/'.$modpack->slug.'/'.$build->version)
            ->assertResponseStatus(404)
            ->seeJsonEquals([
                'error' => 'Modpack does not exist/Build does not exist',
            ]);
    }

    /** @test */
    public function it_shows_an_unpublished_build_to_an_allowed_client()
    {
        $modpack = factory(App\Modpack::class)->create([
            'published' => false,
        ]);

        $build = factory(App\Build::class)->create([
            'modpack_id' => $modpack->id,
            'published' => true,
        ]);

        $client = factory(App\Client::class)->create([
            'is_global' => false,
        ]);

        $modpack->allow($client);

        $this->getJson('/api/modpack/'.$modpack->slug.'/'.$build->version.'?cid='.$client->token)
            ->assertResponseOk()
            ->seeJsonStructure([
                'minecraft',
                'forge',
                'mods',
            ]);
    }

    /** @test */
    public function it_shows_an_unpublished_build_to_a_global_client()
    {
        $modpack = factory(App\Modpack::class)->create([
            'published' => false,
        ]);

        $build = factory(App\Build::class)->create([
            'modpack_id' => $modpack->id,
            'published' => true,
        ]);

        $client = factory(App\Client::class)->create([
            'is_global' => true,
        ]);

        $this->getJson('/api/modpack/'.$modpack->slug.'/'.$build->version.'?k='.$client->token)
            ->assertResponseOk()
            ->seeJsonStructure([
                'minecraft',
                'forge',
                'mods',
            ]);
    }

    /** @test */
    public function it_shows_an_error_for_invalid_build()
    {
        $modpack = factory(App\Modpack::class)->create([
            'published' => false,
        ]);

        $this->getJson('/api/modpack/'.$modpack->slug.'/fake')
            ->assertResponseStatus(404)
            ->seeJsonEquals([
                'error' => 'Modpack does not exist/Build does not exist',
            ]);
    }

}
