<?php

namespace App;

use Illuminate\Http\Client\PendingRequest;

class PostyClient
{
    protected $client;

    public function __construct()
    {
        $project = Helpers::project();
        $this->client = (new PendingRequest())->baseUrl($project['api']['base_url'])->withToken($project['api']['api_key']);
    }


}
