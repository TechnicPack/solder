<?php

/*
 * This file is part of TechnicPack Solder.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Providers;

use App\Modpack;
use App\Md5HashGenerator;
use App\FileHashGenerator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The application version.
     *
     * @var string
     */
    const VERSION = '1.0.0-alpha.1';

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Support for MariaDB
        Schema::defaultStringLength(191);

        // Load modpacks for the directory partial view.
        View::composer('partials.directory', function ($view) {
            $view->with('directory', Modpack::orderBy('name')->get());
        });

        Blade::if('assistant', function () {
            return config('app.assistant');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(FileHashGenerator::class, Md5HashGenerator::class);
    }
}
