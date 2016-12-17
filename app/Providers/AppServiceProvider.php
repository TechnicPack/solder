<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Tremby\LaravelGitVersion\GitVersionHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        View::share('version', GitVersionHelper::getVersion());
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        Passport::ignoreMigrations();
    }
}
