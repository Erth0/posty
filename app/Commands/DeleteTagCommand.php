<?php

namespace App\Commands;

use App\Command;
use App\Helpers;

class DeleteTagCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    public function configure()
    {
        $this->setName('tags:delete')
            ->setAliases(['tag:delete', 'delete:tags', 'delete:tag'])
            ->setDescription('Delete tag');
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

        $confirmation = $this->confirm("Are you sure you would like to delete ({$tag['name']}) tag?");

        if($confirmation) {
            $this->task("Deleting ({$tag['name']}) tag", function () use ($tag) {
                $this->client->delete("tags/{$tag['slug']}");
            });
        }else {
            Helpers::abort('Aborting...');
        }
    }
}
