<?php

namespace App\Commands;

use App\Path;
use Illuminate\Support\Facades\Artisan;
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
        $databasePath = Path::databasePath();

        if (! is_dir($databasePath)) {
            mkdir($databasePath);
        }

        if (! file_exists($databasePath . 'database.sqlite')) {
            file_put_contents($databasePath . 'database.sqlite', '');
        }

        Artisan::call('migrate', [
            '--force' => true,
        ]);
    }
}
