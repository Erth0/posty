<?php

namespace App\Commands;

use App\Helpers;
use App\Client\PostyClient;
use LaravelZero\Framework\Commands\Command;

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
        Helpers::validate();

        $slug = $this->argument('slug') ?? $this->ask('Topic slug?');
        $topic = app(PostyClient::class)->get("topics/{$slug}");

        $confirmation = $this->confirm("Are you sure you would like to delete ({$topic['name']}) topic?");

        if($confirmation) {
            $this->task("Deleting ({$topic['name']}) topic", function () use ($topic) {
                app(PostyClient::class)->delete("topics/{$topic['slug']}");
            });
        }else {
            Helpers::abort('Aborting...');
        }
    }
}
