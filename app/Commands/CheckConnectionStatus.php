<?php

namespace App\Commands;

use App\Posty;
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
        $response = $this->client->get('posty/test');

        dd($response, 'to command');

        $this->task('Testing posty connection', function() {
            return app(Posty::class)->get('posty/test')->ok();
        });
    }
}
