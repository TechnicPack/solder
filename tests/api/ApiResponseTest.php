<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tremby\LaravelGitVersion\GitVersionHelper;

class ApiResponseTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_the_current_version_of_the_api()
    {
        $version = GitVersionHelper::getVersion();

        $this->get('/api')
            ->assertResponseOk()
            ->seeJsonSubset([
                'version' => $version,
            ]);
    }
}
