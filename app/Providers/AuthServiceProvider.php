<?php

/*
 * This file is part of TechnicSolder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Providers;

use Auth;
use Laravel\Passport\Passport;
use App\Guards\LegacyTokenGuard;
use Illuminate\Auth\CreatesUserProviders;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    use CreatesUserProviders;

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::extend('legacy-token', function ($app, $name, array $config) {
            return new LegacyTokenGuard(
                $this->createUserProvider($config['provider']),
                $this->app['request']
            );
        });

        Passport::routes();
    }
}
