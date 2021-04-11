<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;
class SynchronizeArticlesCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    public function configure()
    {
        $this->setName('sync')
            ->setDescription('Synchronize Project Articles');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // get all articles
        // update them accordingly
    }
}
