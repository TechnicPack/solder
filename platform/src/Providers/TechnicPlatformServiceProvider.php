<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Platform\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class PlatformServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings.
     *
     * @var array
     */
    protected $policies = [
        'Platform\Key' => 'Platform\Policies\KeyPolicy',
        'Platform\Client' => 'Platform\Policies\ClientPolicy',
    ];

    /**
     * Bootstrap any platform services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/api.php');
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->loadModelFactoriesFrom(__DIR__.'/../../database/factories');
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        $this->registerPolicies();
    }

    /**
     * Register any platform services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Register factories.
     *
     * @param  string  $path
     * @return void
     */
    protected function loadModelFactoriesFrom($path)
    {
        $this->app->make(Factory::class)->load($path);
    }

    /**
     * Register policies.
     *
     * @return void
     */
    public function registerPolicies()
    {
        foreach ($this->policies as $key => $value) {
            Gate::policy($key, $value);
        }
    }
}
