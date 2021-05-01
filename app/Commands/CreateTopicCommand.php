<?php

namespace App\Commands;

use App\Helpers;
use LaravelZero\Framework\Commands\Command;

class CreateTopicCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'topic:create
                            {name? : Topic name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new topic';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Helpers::validate();

        $topicName = $this->argument('name') ?? $this->ask('Topic name?');

        $this->task('Creating a new topic', function() use($topicName) {
            $this->client->post('topics', [
                'name' => $topicName,
            ]);
        });
    }
}
