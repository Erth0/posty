<?php

namespace App\Commands;

use App\Config;
use Illuminate\Support\Arr;
use LaravelZero\Framework\Commands\Command;

class ListLinkedProjectsCommand extends Command
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
        $projects = collect(Config::load())->map(function($project) {
            return Arr::only($project, ['name', 'local_path', 'base_url']);
        })
        ->toArray();

        $this->table(['Name', 'Local Path', 'Endpoint'], $projects);
    }
}
