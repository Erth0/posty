<?php

namespace App\Commands;

use App\Command;

class TestConnectionStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test posty connection';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->task('Testing posty connection', function () {
            return $this->client->get('test')->ok();
        });
    }
}
