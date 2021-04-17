<?php

namespace App\Providers;

use App\Adapters\ApiAdapter;
use App\Adapters\DatabaseAdapter;
use App\Helpers;
use App\Project;
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
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Project::class, function ($app) {
            return new Project($this->registerProjectAdapter());
        });
    }

    public function registerProjectAdapter()
    {
        $project = Helpers::project();

        $adapters = [
            'api' => ApiAdapter::class,
            'database' => DatabaseAdapter::class,
            'ssh' => SecureShellAdapter::class,
        ];

        return new $adapters[$project['default_connection']]($project);
    }
}
