<?php

namespace App\Commands;

use App\Command;

class CreateTagCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    public function configure()
    {
        $this->setName('tags:create')
            ->setAliases(['create:tags', 'tag:create', 'create:tag'])
            ->setDescription('Create a new tag');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tagName = $this->ask('Tag name?');

        $this->task('Creating a new tag', function() use($tagName) {
            $this->client->post('tags', [
                'name' => $tagName,
            ]);
        });
    }
}
