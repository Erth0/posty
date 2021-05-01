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
        // php -r "echo $_SERVER['home']";
        // php -r "echo 'test';";
        // php -r "if(! is_dir($_SERVER['home'] . '/.posty')){ mkdir($_SERVER['home'] . '/.posty'); } if(! file_exists($_SERVER['home'] . '/.posty/' . 'database.sqlite')) { file_put_contents($_SERVER['home'] . '/.posty/' . 'database.sqlite', '') }"
        $this->task("Installing...", function () {
            $databasePath = $_SERVER['HOME'] . DIRECTORY_SEPARATOR . '.posty' . DIRECTORY_SEPARATOR;

            if (! is_dir($databasePath)) {
                mkdir($databasePath);
            }

            if (! file_exists($databasePath . 'database.sqlite')) {
                file_put_contents($databasePath . 'database.sqlite', '');
            }

            $migrated = Artisan::call('migrate', [
                '--force' => true,
            ]);
        });
    }
}
