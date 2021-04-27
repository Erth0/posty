<?php

namespace App\Commands;

use App\Command;
use App\Helpers;

class CreateTagCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tag:create
                            {name? : Tag name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new tag';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Helpers::validate();

        $tagName = $this->argument('name') ?? $this->ask('Tag name?');

        $this->task('Creating a new tag', function() use($tagName) {
            $this->client->post('tags', [
                'name' => $tagName,
            ]);
        });
    }
}
