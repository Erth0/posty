<?php

namespace App\Commands;

use App\Path;
use App\Helpers;
use App\Resources\Project;
use Illuminate\Support\Str;
use LaravelZero\Framework\Commands\Command;

class LinkProjectCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Link current folder with a posty project';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $project = Project::findByPath(Path::current());
        if ($project) {
            Helpers::abort('This project is already linked.');
        }

        $name = $this->ask("Please provide a project name?");
        if (! $name) {
            Helpers::abort('Project name is required.');
        }

        $endpoint = $this->ask('API Endpoint');
        $endpoint = Str::endsWith($endpoint, '/') ? Str::replaceLast('/', '', $endpoint) : $endpoint;
        if (! $endpoint) {
            Helpers::abort('Endpoint is required.');
        }

        $endpointPrefix = $this->ask('API Endpoint Prefix', config('posty.posty_endpoint_prefix'));
        if (! $endpointPrefix) {
            Helpers::abort('Endpoint prefix is required.');
        }

        $authenticationToken = trim($this->ask('Authentication token'));
        if (! $authenticationToken) {
            Helpers::abort('Authentication token is required.');
        }

        $project = (new Project)->create([
            'name' => $name,
            'path' => Path::current(),
            'endpoint' => $endpoint,
            'endpoint_prefix' => $endpointPrefix,
            'auth_token' => $authenticationToken,
        ]);

        $this->info("Folder was linked successfully with project: {$project->name}");
    }
}
