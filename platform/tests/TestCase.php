<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests;

use Illuminate\Support\Facades\Gate;
use Platform\Providers\PlatformServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    /**
     * Autoload Service Providers.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            PlatformServiceProvider::class,
        ];
    }

    /**
     * Authorize the given ability.
     *
     * @param $ability
     */
    protected function authorizeAbility($ability)
    {
        Gate::define($ability, function () {
            return true;
        });
    }

    /**
     * Deny the given ability.
     *
     * @param $ability
     */
    protected function denyAbility($ability)
    {
        Gate::define($ability, function () {
            return false;
        });
    }
}
