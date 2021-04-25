<?php

namespace App\Commands;

use App\Command;
use App\Helpers;

class DeleteTopicCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    public function configure()
    {
        $this->setName('topics:delete')
            ->setAliases(['topic:delete', 'delete:topics', 'delete:topic'])
            ->setDescription('Delete topic');
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

        $confirmation = $this->confirm("Are you sure you would like to delete ({$topic['name']}) topic?");

        if($confirmation) {
            $this->task("Deleting ({$topic['name']}) tag", function () use ($topic) {
                $this->client->delete("topics/{$topic['slug']}");
            });
        }else {
            Helpers::abort('Aborting...');
        }
    }
}
