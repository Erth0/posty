<?php

namespace App;

use Illuminate\Http\Client\PendingRequest;

class PostyClient extends PendingRequest
{
    protected $client;

    public function __construct()
    {
        $project = Helpers::project();
        $this->client = (new PendingRequest())->baseUrl($project['api']['base_url'])->withToken($project['api']['api_key']);
    }

    public function get()
    {
        return $this->client->get
    }
}
