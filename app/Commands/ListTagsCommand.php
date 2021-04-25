<?php

namespace App\Commands;

use App\Command;
use Illuminate\Support\Arr;

class ListTagsCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    public function configure()
    {
        $this->setName('tags:list')
            ->setAliases(['tags:all'])
            ->setDescription('List all tags');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tags = collect($this->client->get('tags'))->map(function ($tag) {
            return Arr::only($tag, ['id', 'slug', 'name']);
        })
        ->toArray();

        $this->table(['id', 'slug', 'name'], $tags);
    }
}
