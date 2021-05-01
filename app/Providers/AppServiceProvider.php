<?php

namespace App\Providers;

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
        // $project = Project::findByPath(Path::current());

        // if($project) {
        //     $this->client = (new PostyClient())->acceptJson()->baseUrl($project->endpoint . '/' . $project->endpoint_prefix)->withToken($project->auth_token);
        // }
    }
}
