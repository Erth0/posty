<?php

namespace App\Commands;

use Spatie\Crypto\Rsa\KeyPair;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class CreateProjectCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'create:project';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create a new project';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // $computerUser = $_SERVER['USER'];
        // $homePath = $_SERVER['HOME'];


        $name = $this->ask('What is the project name?');
        $website = $this->ask('What is the project URI?');
        Config::set('filesystems.disks.computer.root', $_SERVER['HOME']);

        $this->task("Creating project directory if doesn't exists", function () use($name) {
            if(! Storage::disk('computer')->exists($name)) {
                Storage::disk('computer')->makeDirectory($name, 0775, true);
                Storage::disk('computer')->makeDirectory($name . "/posts", 0775, true);
            }
            return true;
        });

        $this->task('Generating project settings', function() use($name, $website) {
            [$privateKey, $publicKey] = (new KeyPair())->generate();

            Storage::disk('computer')->put("{$name}/settings.json", json_encode([
                'project_name' => $name,
                'uri' => $website,
                'api_key' => $apiKey = bin2hex(random_bytes(32)),
                'rsa_private_key' => $privateKey,
                'rsa_public_key' => $publicKey,
            ], JSON_PRETTY_PRINT));

            Storage::disk('computer')->put("{$name}/posty_{$name}_settings.json", json_encode([
                'api_key' => $apiKey,
                'rsa_public_key' => $publicKey,
            ], JSON_PRETTY_PRINT));
        });


        $rootPath = config('filesystems.disks.computer.root');
        $projectPath = $rootPath . "/{$name}";
        $settingsFilePath = $projectPath . "/posty_{$name}_settings.json";

        $this->newLine();
        $this->info("
            Congratulations your project was created successfully, we have generated two json files under ({$projectPath}) please copy
            {$settingsFilePath} under your application.
        ");
    }
}
