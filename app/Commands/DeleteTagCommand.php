<?php

namespace App\Commands;

use App\Helpers;
use App\Client\PostyClient;
use LaravelZero\Framework\Commands\Command;

class DeleteTagCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tag:delete
                            {slug? : Tag slug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete tag';

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

        $confirmation = $this->confirm("Are you sure you would like to delete ({$tag['name']}) tag?");

        if($confirmation) {
            $this->task("Deleting ({$tag['name']}) tag", function () use ($tag) {
                app(PostyClient::class)->delete("tags/{$tag['slug']}");
            });
        }else {
            Helpers::abort('Aborting...');
        }
    }
}
