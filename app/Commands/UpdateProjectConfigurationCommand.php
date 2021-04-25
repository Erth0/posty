<?php

namespace App\Commands;

use App\Path;
use App\Config;
use App\Helpers;
use LaravelZero\Framework\Commands\Command;

class UpdateProjectConfigurationCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    public function configure()
    {
        $this->setName('project:update')
            ->setDescription('Update project configuration settings.');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $projectDetails = Helpers::project();
        if(! $projectDetails) {
            Helpers::abort('Project not found in this folder');
        }

        $projectName = $this->ask("Please provide a project name?", $projectDetails['name']);
        $projectKeyName = strtolower($projectName);
        $projectDetails['name'] = $projectName;
        $projectDetails['project'] = $projectKeyName;
        $projectDetails['local_path'] = Path::current();
        $projectDetails['base_url'] = $this->ask('Api Endpoint', $projectDetails['base_url']);
        $projectDetails['api_key'] = $this->ask('Api Key', $projectDetails['api_key']);
        $projectDetails['posty_endpoint_prefix'] = $this->ask('Endpoint Prefix', $projectDetails['posty_endpoint_prefix']);

        Config::set($projectKeyName, $projectDetails);

        $this->info("Project ({$projectDetails['name']}) was updated successfully.");
    }
}
