<?php

namespace App\Commands;

use App\Path;
use LaravelZero\Framework\Commands\Command;

class InstallDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install posty database';


    protected $hidden = true;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->task("Installing...", function() {
            if (! is_dir(Path::databasePath())) {
                mkdir(Path::databasePath());
            }

            if(! file_exists(Path::databasePath() . 'database.sqlite')) {
                file_put_contents(Path::databasePath() . 'database.sqlite', '');
            }
        });
    }
}
