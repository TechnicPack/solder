<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Unit;

use Tests\Modpack;
use Tests\TestCase;
use Platform\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HasClientsTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_client_can_be_manually_attached_and_detached()
    {
        $client = factory(Client::class)->create();
        $modpack = Modpack::create();

        $modpack->clients()->attach($client);

        $this->assertTrue($modpack->hasClients());
        $this->assertTrue($modpack->knowsClient($client));
        $this->assertTrue($modpack->knowsClient($client->id));

        $modpack->clients()->detach($client);

        $this->assertFalse($modpack->hasClients());
        $this->assertFalse($modpack->knowsClient($client));
        $this->assertFalse($modpack->knowsClient($client->id));
    }

    /** @test **/
    public function a_client_can_be_attached_and_detached_using_methods()
    {
        $client = factory(Client::class)->create();
        $modpack = Modpack::create();

        $modpack->attachClient($client);

        $this->assertTrue($modpack->hasClients());
        $this->assertTrue($modpack->knowsClient($client));
        $this->assertTrue($modpack->knowsClient($client->id));

        $modpack->detachClient($client);

        $this->assertFalse($modpack->hasClients());
        $this->assertFalse($modpack->knowsClient($client));
        $this->assertFalse($modpack->knowsClient($client->id));
    }

    /** @test **/
    public function queries_can_be_scoped_to_a_specific_client()
    {
        $client = factory(Client::class)->create();
        $modpackA = factory(Modpack::class)->create();
        $modpackB = factory(Modpack::class)->create();
        $modpackA->clients()->attach($client);

        $modpacks = Modpack::forClient($client)->get();

        $modpacks->assertContains($modpackA);
        $modpacks->assertNotContains($modpackB);
    }

    /** @test **/
    public function queries_can_be_scoped_to_a_specific_client_id()
    {
        $client = factory(Client::class)->create();
        $modpackA = factory(Modpack::class)->create();
        $modpackB = factory(Modpack::class)->create();
        $modpackA->clients()->attach($client);

        $modpacks = Modpack::forClient($client->id)->get();

        $modpacks->assertContains($modpackA);
        $modpacks->assertNotContains($modpackB);
    }

    /** @test **/
    public function queries_can_be_scoped_to_a_specific_client_token()
    {
        $client = factory(Client::class)->create();
        $modpackA = factory(Modpack::class)->create();
        $modpackB = factory(Modpack::class)->create();
        $modpackA->clients()->attach($client);

        $modpacks = Modpack::forClientToken($client->token)->get();

        $modpacks->assertContains($modpackA);
        $modpacks->assertNotContains($modpackB);
    }
}
