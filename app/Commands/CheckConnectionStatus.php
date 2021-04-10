<?php

namespace App\Commands;

use Exception;
use App\Helpers;
use Illuminate\Support\Facades\DB;
use LaravelZero\Framework\Commands\Command;

class CheckConnectionStatus extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Check Posty Connection';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $project = Helpers::project();

        $this->task('Checking if project is linked', function() use($project) {
            return $project ? true : false;
        });

        $this->task('Checking if a connection can be made', function() {
            try {
                DB::connection()->getPdo();

                return true;
            } catch (Exception $e) {
                return false;
            }
        });
    }
}
