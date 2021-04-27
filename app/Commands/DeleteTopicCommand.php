<?php

namespace App\Commands;

use App\Command;
use App\Helpers;

class DeleteTopicCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'topic:delete
                            {slug? : Topic slug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete topic';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $slug = $this->argument('slug') ?? $this->ask('Topic slug?');
        $topic = $this->client->get("topics/{$slug}");

        $confirmation = $this->confirm("Are you sure you would like to delete ({$topic['name']}) topic?");

        if($confirmation) {
            $this->task("Deleting ({$topic['name']}) topic", function () use ($topic) {
                $this->client->delete("topics/{$topic['slug']}");
            });
        }else {
            Helpers::abort('Aborting...');
        }
    }
}
