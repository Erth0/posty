<?php

namespace App\Commands;

use App\Command;
use Illuminate\Support\Arr;

class ListTopicsCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    public function configure()
    {
        $this->setName('topics:list')
            ->setAliases(['topics:all'])
            ->setDescription('List all topics');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $topics = collect($this->client->get('topics'))->map(function ($topic) {
            return Arr::only($topic, ['id', 'slug', 'name']);
        })
        ->toArray();

        $this->table(['id', 'slug', 'name'], $topics);
    }
}
