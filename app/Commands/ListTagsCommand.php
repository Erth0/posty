<?php

namespace App\Commands;

use App\Helpers;
use App\Client\PostyClient;
use Illuminate\Support\Arr;
use LaravelZero\Framework\Commands\Command;

class ListTagsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tag:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all tags';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Helpers::validate();

        $tags = collect(app(PostyClient::class)->get('tags'))->map(function ($tag) {
            return Arr::only($tag, ['id', 'slug', 'name']);
        })
        ->toArray();

        $this->table(['id', 'slug', 'name'], $tags);
    }
}
