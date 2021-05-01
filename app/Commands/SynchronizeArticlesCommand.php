<?php

namespace App\Commands;

use App\Path;
use App\Helpers;
use App\Models\Project;
use App\Client\PostyClient;
use LaravelZero\Framework\Commands\Command;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class SynchronizeArticlesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'article:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize all articles for this project';

    /**
     * Posty Client
     *
     * @var \App\Client\PostyClient
     */
    protected $client;

    public function __construct()
    {
        $this->client = app(PostyClient::class);

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Helpers::validate();

        $project = Project::findByPath(Path::current());

        collect(scandir($project->path))
            ->reject(function ($file) {
                return in_array($file, ['.', '..', '.DS_Store']);
            })
            ->each(function ($file) {
                $parser = YamlFrontMatter::parse(file_get_contents($file));

                if (! $parser->id) {
                    return;
                }

                $article = $this->client->get("articles/{$parser->id}");

                if (! $article) {
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
