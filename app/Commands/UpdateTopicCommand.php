<?php

namespace App\Commands;

use App\Helpers;
use App\Client\PostyClient;
use LaravelZero\Framework\Commands\Command;

class UpdateTopicCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'topic:update
                            {slug? : Topic slug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update topic';

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
        $name = $this->ask('Topic name?');

        $this->task("Updating ({$topic['name']}) topic", function () use ($topic, $name) {
            app(PostyClient::class)->put("topics/{$topic['slug']}", [
                'name' => $name,
            ]);
        });
    }
}
