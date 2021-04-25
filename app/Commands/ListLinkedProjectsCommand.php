<?php

namespace App\Commands;

use App\Config;
use Illuminate\Support\Arr;
use LaravelZero\Framework\Commands\Command;

class ListLinkedProjectsCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    public function configure()
    {
        $this->setName('projects:list')
            ->setDescription('List all linked projects');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $projects = collect(Config::load())->map(function($project) {
            return Arr::only($project, ['project', 'local_path', 'base_url']);
        })
        ->toArray();

        $this->table(['Name', 'Local Path', 'Endpoint'], $projects);
    }
}
