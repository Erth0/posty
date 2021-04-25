<?php

namespace App\Commands;

use App\Command;

class UpdateTopicCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    public function configure()
    {
        $this->setName('topics:update')
            ->setAliases(['topic:update', 'update:topics', 'update:topic'])
            ->setDescription('Update topic');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $slug = $this->ask('Topic slug?');
        $topic = $this->client->get("topics/{$slug}");
        $name = $this->ask('Topic name?');

        $this->task("Updating ({$topic['name']}) topic", function () use ($topic, $name) {
            $this->client->put("topics/{$topic['slug']}", [
                'name' => $name,
            ]);
        });
    }
}
