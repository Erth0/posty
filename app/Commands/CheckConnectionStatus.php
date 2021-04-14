<?php

namespace App\Commands;

use Exception;
use App\Helpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use LaravelZero\Framework\Commands\Command;

class CheckConnectionStatus extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    public function configure()
    {
        $this->setName('test')
            ->setDescription('Check posty connection');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $project = Helpers::project();

        $response = Http::withToken('UiLFSdcFn04tcZ55Jcgx1oK8Tbh5Fk7d')->get($project['api']['endpoint'] . 'posty/test')->object();
        dd($response->success);

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
