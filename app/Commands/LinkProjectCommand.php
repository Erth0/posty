<?php

namespace App\Commands;

use App\Config;
use App\Helpers;
use App\Path;
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
        $projectDetails = [];
        $projectName = $this->ask("Please provide a project name?");
        $projectKeyName = strtolower($projectName);
        if (Config::has($projectKeyName)) {
            Helpers::abort('This project is already linked.');
        }

        $projectDetails['name'] = $projectName;
        $projectDetails['project'] = $projectKeyName;
        $projectDetails['local_path'] = Path::current();
        $projectDetails['base_url'] = $this->ask('Api Endpoint');
        $projectDetails['api_key'] = $this->ask('Api Key');
        $projectDetails['posty_endpoint_prefix'] = $this->ask('Endpoint Prefix', config('posty.posty_endpoint_prefix'));

        Config::set($projectKeyName, $projectDetails);

        $this->info("Folder was linked successfully with project: {$projectName}");
    }
}
