<?php

namespace App;

use App\Client\PostyClient;
use App\Models\Project;
use LaravelZero\Framework\Commands\Command as LaravelZeroCommand;

class Command extends LaravelZeroCommand
{
    protected $client;

    public function __construct()
    {
        $project = Project::findByPath(Path::current());

        if($project) {
            $this->client = (new PostyClient())->acceptJson()->baseUrl($project->endpoint . '/' . $project->endpoint_prefix)->withToken($project->auth_token);
        }

        parent::__construct();
    }
}
