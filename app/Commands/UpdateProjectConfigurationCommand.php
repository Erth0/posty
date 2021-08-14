<?php

namespace App\Commands;

use App\Path;
use App\Helpers;
use App\Resources\Project;
use Illuminate\Support\Str;
use LaravelZero\Framework\Commands\Command;

class UpdateProjectConfigurationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update project configuration settings';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Helpers::validate();

        $project = Project::findByPath(Path::current());
        $endpoint = Str::endsWith($project->endpoint, '/') ? Str::replaceLast('/', '', $project->endpoint) : $project->endpoint;

        $project->update([
            'name' => $this->ask("Please provide a project name?", $project->name),
            'endpoint' => $this->ask('API Endpoint', $endpoint),
            'endpoint_prefix' => $this->ask('API Endpoint Prefix', $project->endpoint_prefix),
            'auth_token' => $this->ask('Authentication token', $project->auth_token),
        ]);

        $this->info("Project ({$project->name}) was updated successfully.");
    }
}
