<?php

namespace App\Commands;

use App\Config;
use App\Helpers;
use LaravelZero\Framework\Commands\Command;

class UnlinkProjectCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unlink';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unlink current folder from the projects list';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $project = Helpers::project();
        $projectKeyName = $project['project'];
        $confirmation = $this->confirm("Are you sure you would like to unlink project {$projectKeyName}");

        if ($confirmation) {
            $this->task("Unlink project {$projectKeyName}", function () use ($projectKeyName) {
                Config::forget($projectKeyName);

                return true;
            });
        } else {
            Helpers::abort('Aborting...');
        }
    }
}
