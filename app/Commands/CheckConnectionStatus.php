<?php

namespace App\Commands;

use App\Command;
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
        $this->task('Testing posty connection', function() {
            return $this->client->get('test')->ok();
        });
    }
}
