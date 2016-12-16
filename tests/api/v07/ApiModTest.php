<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class ApiModTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_lists_mods()
    {
        $mod = factory(App\Resource::class)->create();

        $this->getJson('/api/mod')
            ->assertResponseOk()
            ->seeJsonSubset([
                'mods' => [
                    $mod->slug => $mod->name,
                ]
            ]);
    }

    /** @test */
    public function it_shows_a_mod()
    {
        $mod = factory(App\Resource::class)->create();

        $this->getJson('/api/mod/' . $mod->slug)
            ->assertResponseOk()
            ->seeJsonSubset([
                'name' => $mod->slug,
                'pretty_name' => $mod->name,
                'author' => $mod->author,
                'description' => $mod->description,
                'link' => $mod->link,
            ]);
    }

    /** @test */
    public function it_shows_an_error_on_invalid_mod()
    {
        $this->getJson('/api/mod/fake')
            ->assertResponseStatus(404)
            ->seeJsonEquals([
                'error' => 'No mod requested/Resource does not exist/Resource version does not exist',
            ]);
    }

    /** @test */
    public function it_lists_mod_versions()
    {
        $mod = factory(App\Resource::class)->create();

        $version = factory(App\Version::class)->create([
            'resource_id' => $mod->id,
        ]);

        $this->getJson('/api/mod/' . $mod->slug)
            ->assertResponseOk()
            ->seeJsonSubset([
                'versions' => [
                    $version->version,
                ]
            ]);
    }

    /** @test */
    public function it_shows_a_version()
    {
        $resource = factory(App\Resource::class)->create();

        $version = factory(App\Version::class)->create([
            'resource_id' => $resource->id,
        ]);

        $this->getJson('/api/mod/' . $resource->slug . '/' . $version->version)
            ->assertResponseOk()
            ->seeJsonStructure([
                'md5',
                'url',
            ]);
    }

    /** @test */
    public function it_shows_an_error_on_invalid_version()
    {
        $mod = factory(App\Resource::class)->create();

        $this->getJson('/api/mod/' . $mod->slug . '/fake')
            ->assertResponseStatus(404)
            ->seeJsonEquals([
                'error' => 'No mod requested/Resource does not exist/Resource version does not exist',
            ]);
    }

}
