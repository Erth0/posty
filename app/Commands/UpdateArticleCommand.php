<?php

namespace App\Commands;

use App\Command;
use App\Helpers;
use Illuminate\Support\Str;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class UpdateArticleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'article:update {article?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update article';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $project = Helpers::project();

        $article = $this->argument('article') ?? $this->ask('Article file name');
        $articleFileName = Str::endsWith($article, '.md') ? $article : $article . '.md';

        if (! file_exists($file = $project['local_path'] . '/' . $articleFileName)) {
            Helpers::abort("Article couldn't be found please check the file name is correct: " . $articleFileName);
        }

        $parser = YamlFrontMatter::parse(file_get_contents($file));
        $article = $this->client->get("articles/{$parser->id}");

        if (! $article) {
            Helpers::abort("Article couldn't be found in the database with ID: {$parser->id}");
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

        $this->info("Article ({$article['title']}) was updated successfully.");
    }
}
