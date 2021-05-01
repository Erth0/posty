<?php

namespace App\Commands;

use App\Helpers;
use App\Client\PostyClient;
use LaravelZero\Framework\Commands\Command;

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
     * Posty Client
     *
     * @var \App\Client\PostyClient
     */
    protected $client;

    public function __construct()
    {
        $this->client = app(PostyClient::class);

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Helpers::validate();

        $this->task('Testing posty connection', function () {
            return $this->client->get('test');
        });
    }
}
