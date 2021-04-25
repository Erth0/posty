<?php

namespace App\Commands;

use App\Command;

class CreateTopicCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    public function configure()
    {
        $this->setName('topics:create')
            ->setAliases(['topic:create', 'create:topics', 'create:topic'])
            ->setDescription('Create a new topic');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $topicName = $this->ask('Topic name?');

        $this->task('Creating a new topic', function() use($topicName) {
            $this->client->post('topics', [
                'name' => $topicName,
            ]);
        });
    }
}
