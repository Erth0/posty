<?php

namespace App\Providers;

use App\Path;
use App\Resources\Project;
use App\Client\PostyClient;
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
        $this->app->singleton(PostyClient::class, function () {
            $project = Project::findByPath(Path::current());
            if ($project) {
                return (new PostyClient([
                    'endpoint' => $project->endpoint,
                    'endpoint_prefix' => $project->endpoint_prefix,
                    'auth_token' => $project->auth_token,
                ]));
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
