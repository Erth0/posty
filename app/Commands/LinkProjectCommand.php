<?php

namespace App\Commands;

use App\Config;
use App\Helpers;
use App\Path;
use LaravelZero\Framework\Commands\Command;

class LinkProjectCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    public function configure()
    {
        $this->setName('link')
            ->setDescription('Link a local folder with a blog project');
    }

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
        if(Config::get($projectKeyName)) {
            Helpers::abort('This project is already linked.');
        }

        $projectDetails['name'] = $projectName;
        $projectDetails['project'] = $projectKeyName;
        $projectDetails['local_path'] = Path::current();
        $projectDetails['default_connection'] = $connection = $this->choice('What is your prefered project connection?', ['database', 'ssh']);

        if($connection === 'ssh') {
            $projectDetails[$connection]['host'] = $this->ask('Remote Host');
            $projectDetails[$connection]['port'] = $this->ask('Remote Port', 22);
            $projectDetails[$connection]['user'] = $this->ask('Remote User');
            $projectDetails[$connection]['path'] = $this->ask('Remote Path');
        }

        if($connection === 'database') {
            $projectDetails[$connection]['driver'] = $this->ask('Database Driver', 'mysql');
            $projectDetails[$connection]['url'] = $this->ask('Database URL', null);
            $projectDetails[$connection]['host'] = $this->ask('Database Host', '127.0.0.1');
            $projectDetails[$connection]['port'] = $this->ask('Database Port', '3306');
            $projectDetails[$connection]['database'] = $this->ask('Database Name', 'posty');
            $projectDetails[$connection]['username'] = $this->ask('Database Username', 'posty');
            $projectDetails[$connection]['password'] = $this->secret('Database Password');
        }

        Config::set($projectKeyName, $projectDetails);

        $this->info("Folder was linked successfully with project: {$projectName}");
    }
}
