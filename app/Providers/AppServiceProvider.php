<?php

namespace App\Providers;

use App\Helpers;
use App\Services\PostyProject;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        dd(app(PostyProject::class));

        $project = Helpers::project();
        Config::set("database.connections.{$project['project']}", $project[$project['default_connection']]);
        DB::setDefaultConnection($project['project']);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PostyProject::class, function ($app) {
            return new PostyProject(Helpers::project());
        });
    }
}
