<?php

namespace App\Commands;

use App\Path;
use App\Helpers;
use App\Models\Project;
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
        $project = Project::findByPath(Path::current());

        if(! $project) {
            Helpers::abort("No project linked with the current folder: " . Path::current());
        }

        $confirmation = $this->confirm("Are you sure you would like to unlink project {$project->name}");

        if ($confirmation) {
            $this->task("Unlink project {$project->name}", function () use ($project) {
                return $project->delete();
            });
        } else {
            Helpers::abort('Aborting...');
        }
    }
}
