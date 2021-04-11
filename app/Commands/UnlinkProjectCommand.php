<?php

namespace App\Commands;

use App\Config;
use App\Helpers;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Console\Input\InputArgument;

class UnlinkProjectCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    public function configure()
    {
        $this->setName('unlink')
            ->addArgument('project', InputArgument::OPTIONAL, "Project Name")
            ->setDescription('Unlink Project');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $projectKeyName = strtolower($this->argument('project') ?? $this->ask("Project Name"));
        $project = Config::has($projectKeyName);

        if(! $project) {
            Helpers::abort("The project ({$projectKeyName}) doesn't exists please check project name was correct");
        }

        $confirmation = $this->confirm("Are you sure you would like to unlink project {$projectKeyName}");

        if($confirmation) {
            $this->task("Unlink project {$projectKeyName}", function() use($projectKeyName) {
                Config::forget($projectKeyName);

                return true;
            });
        }else {
            Helpers::abort('Aborting...');
        }
    }
}
