<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class ApiClientTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_verifies_global_tokens()
    {
        $client = factory(App\Client::class)->create([
            'is_global' => true,
        ]);

        $this->get('/api/verify/' . $client->token)
            ->assertResponseOk()
            ->seeJsonSubset([
                'name' => $client->name,
                'valid' => 'Key validated.',
            ]);
    }

    /** @test */
    public function it_rejects_non_global_tokens()
    {
        $client = factory(App\Client::class)->create([
            'is_global' => false,
        ]);

        $this->get('/api/verify/' . $client->token)
            ->assertResponseStatus(404)
            ->seeJsonSubset([
                'error' => 'Invalid key provided.',
            ]);
    }

    /** @test */
    public function it_rejects_invalid_tokens()
    {
        $this->get('/api/verify/fake_token')
            ->assertResponseStatus(404)
            ->seeJsonSubset([
                'error' => 'Invalid key provided.',
            ]);
    }
}
