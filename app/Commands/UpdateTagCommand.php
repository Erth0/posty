<?php

namespace App\Commands;

use App\Command;

class UpdateTagCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    public function configure()
    {
        $this->setName('tags:update')
            ->setAliases(['tag:update', 'update:tags', 'update:tag'])
            ->setDescription('Update tag');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $slug = $this->ask('Tag slug?');
        $tag = $this->client->get("tags/{$slug}");
        $name = $this->ask('Tag name?');

        $this->task("Updating ({$tag['name']}) tag", function () use ($tag, $name) {
            $this->client->put("tags/{$tag['slug']}", [
                'name' => $name,
            ]);
        });
    }
}
