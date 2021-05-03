<?php

namespace App\Commands;

use App\Helpers;
use App\Client\PostyClient;
use LaravelZero\Framework\Commands\Command;

class UpdateTagCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tag:update
                            {slug? : Tag slug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update tag';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Helpers::validate();

        $slug = $this->argument('slug') ?? $this->ask('Tag slug?');
        $tag = app(PostyClient::class)->get("tags/{$slug}");
        $name = $this->ask('Tag name?');

        $this->task("Updating ({$tag['name']}) tag", function () use ($tag, $name) {
            app(PostyClient::class)->put("tags/{$tag['slug']}", [
                'name' => $name,
            ]);
        });
    }
}
