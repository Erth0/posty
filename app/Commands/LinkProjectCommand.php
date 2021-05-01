<?php

namespace App\Commands;

use App\Path;
use App\Helpers;
use App\Models\Project;
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
        $endpoint = $this->ask('API Endpoint');
        $endpoint = Str::endsWith($endpoint, '/') ? Str::replaceLast('/', '', $endpoint) : $endpoint;

        $project = Project::create([
            'name' => $name,
            'path' => Path::current(),
            'endpoint' => $endpoint,
            'endpoint_prefix' => $this->ask('API Endpoint Prefix', config('posty.posty_endpoint_prefix')),
            'auth_token' => trim($this->ask('Authentication token')),
        ]);

        $this->info("Folder was linked successfully with project: {$project->name}");
    }
}
