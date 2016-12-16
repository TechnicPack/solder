<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class ApiModTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_lists_mods()
    {
        $mod = factory(App\Mod::class)->create();

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
        $mod = factory(App\Mod::class)->create();

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
                'error' => 'No mod requested/Mod does not exist/Mod version does not exist',
            ]);
    }

    /** @test */
    public function it_lists_mod_releases()
    {
        $mod = factory(App\Mod::class)->create();

        $release = factory(App\Release::class)->create([
            'mod_id' => $mod->id,
        ]);

        $this->getJson('/api/mod/' . $mod->slug)
            ->assertResponseOk()
            ->seeJsonSubset([
                'versions' => [
                    $release->version,
                ]
            ]);
    }

    /** @test */
    public function it_shows_a_release()
    {
        $mod = factory(App\Mod::class)->create();

        $release = factory(App\Release::class)->create([
            'mod_id' => $mod->id,
        ]);

        $this->getJson('/api/mod/' . $mod->slug . '/' . $release->version)
            ->assertResponseOk()
            ->seeJsonStructure([
                'md5',
                'url',
            ]);
    }

    /** @test */
    public function it_shows_an_error_on_invalid_release()
    {
        $mod = factory(App\Mod::class)->create();

        $this->getJson('/api/mod/' . $mod->slug . '/fake')
            ->assertResponseStatus(404)
            ->seeJsonEquals([
                'error' => 'No mod requested/Mod does not exist/Mod version does not exist',
            ]);
    }

}
