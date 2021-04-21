<?php

namespace App;

use App\Client\PostyClient;
use App\Helpers;
use LaravelZero\Framework\Commands\Command as LaravelZeroCommand;

class Command extends LaravelZeroCommand
{
    protected $client;

    public function __construct()
    {
        $config = Helpers::project();
        if(! $config) {
            Helpers::abort("No project linked with the current folder: " . getcwd());
        }

        $this->client = (new PostyClient())->acceptJson()->baseUrl($config['base_url'])->withToken($config['api_key']);

        parent::__construct();
    }
}
