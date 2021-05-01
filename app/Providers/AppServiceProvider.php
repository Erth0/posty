<?php

namespace App\Providers;

use App\Path;
use App\Models\Project;
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
        $this->app->singleton(PostyClient::class, function() {
            if(file_exists(Path::databasePath() . 'database.sqlite')) {
                $project = Project::findByPath(Path::current());

                if($project) {
                    return (new PostyClient())->acceptJson()->baseUrl($project->endpoint . '/' . $project->endpoint_prefix)->withToken($project->auth_token);
                }
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
