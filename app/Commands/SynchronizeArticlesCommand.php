<?php

namespace App\Commands;

use App\Command;
use App\Helpers;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class SynchronizeArticlesCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    public function configure()
    {
        $this->setName('sync')
            ->setAliases(['synchronize'])
            ->setDescription('Synchronize Project Articles');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $project = Helpers::project();
        collect(scandir($project['local_path']))
        ->reject(function($file) {
            return in_array($file, ['.', '..', '.DS_Store']);
        })
        ->each(function($file) {
            $parser = YamlFrontMatter::parse(file_get_contents($file));

            if(! $parser->id) {
                return;
            }

            $article = $this->client->get("articles/{$parser->id}");

            if(! $article) {
                $this->info("Article couldn't be found in the database, skipping...");
                return;
            }

            $article = $this->client->put("articles/{$parser->id}", [
                'title' => $parser->title,
                'slug' => $parser->slug,
                'summary' => $parser->summary,
                'body' => $parser->body(),
                'status' => $parser->status,
                'featured_image' => $parser->featured_image,
                'featured_image_caption' => $parser->featured_image_caption,
                'topics' => $parser->topics,
                'tags' => $parser->tags,
                'meta' => $parser->meta,
            ]);

            $this->info("Article with ID {$parser->id} was synchronized successfully.");
        });
    }
}
