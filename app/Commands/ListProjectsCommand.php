<?php

namespace App\Commands;

use App\Resources\Project;
use Illuminate\Support\Arr;
use LaravelZero\Framework\Commands\Command;

class ListProjectsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'projects';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all linked projects';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $projects = (new Project)->all()->map(function($project) {
            return Arr::only($project->attributes, ['name', 'path', 'endpoint']);
        })
        ->toArray();

        $this->table(['Name', 'Local Path', 'Endpoint'], $projects);
    }
}
