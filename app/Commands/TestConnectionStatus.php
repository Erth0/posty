<?php

namespace App\Commands;

use App\Path;
use App\Helpers;
use App\Client\PostyClient;
use App\Resources\Project;
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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Helpers::validate();
        $project = Project::findByPath(Path::current());

        $this->task("Testing posty connection with ({$project->endpoint})", function () {
            return app(PostyClient::class)->get('test');
        });
    }
}
