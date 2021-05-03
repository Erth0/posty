<?php

namespace App\Providers;

use App\Path;
use App\Helpers;
use App\Models\Project;
use App\Client\PostyClient;
use Illuminate\Support\Facades\Artisan;
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
        if (! Helpers::databaseCreated()) {
            Artisan::call('install');
        }

        $project = Project::findByPath(Path::current());

        if ($project) {
            $this->app->singleton(PostyClient::class, function () use($project) {
                return (new PostyClient())->acceptJson()->baseUrl($project->endpoint . '/' . $project->endpoint_prefix)->withToken($project->auth_token);
            });
        }
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
