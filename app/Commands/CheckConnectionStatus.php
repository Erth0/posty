<?php

namespace App\Commands;

use App\Project;
use App\Exceptions\ConnectionFailedException;
use LaravelZero\Framework\Commands\Command;

class CheckConnectionStatus extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    public function configure()
    {
        $this->setName('test')
            ->setDescription('Check posty connection');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        dd(app(Project::class)->getArticle('first-article'));
        $this->task('Checking if a connection can be made', function () {
            try {
                app(Project::class)->testConnection();

                return true;
            } catch (ConnectionFailedException $e) {
                return false;
            }
        });
    }
}
