<?php

namespace App\Commands;

use App\Helpers;
use Illuminate\Support\Arr;
use LaravelZero\Framework\Commands\Command;

class ListTopicsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'topic:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all topics';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Helpers::validate();

        $topics = collect($this->client->get('topics'))->map(function ($topic) {
            return Arr::only($topic, ['id', 'slug', 'name']);
        })
        ->toArray();

        $this->table(['id', 'slug', 'name'], $topics);
    }
}
