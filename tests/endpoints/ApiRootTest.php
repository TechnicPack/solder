<?php

namespace Tests\endpoints;


/*
 * This file is part of TechnicSolder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Tremby\LaravelGitVersion\GitVersionHelper;

class ApiRootTest extends TestCase
{
    /** @test */
    public function it_returns_the_current_version_of_the_api()
    {
        $version = GitVersionHelper::getVersion();

        $this->get('/api')
            ->assertResponseOk()
            ->assertJson([
                'version' => $version,
            ]);
    }
}
