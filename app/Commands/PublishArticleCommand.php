<?php

namespace App\Commands;

use App\Path;
use App\Helpers;
use App\Resources\Project;
use App\Client\PostyClient;
use Illuminate\Support\Str;
use LaravelZero\Framework\Commands\Command;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class PublishArticleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'article:publish
                            {article : The article file name to be published}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish article';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Helpers::validate();

        $project = Project::findByPath(Path::current());
        $article = $this->argument('article');
        $articleFileName = Str::endsWith($article, '.md') ? $article : $article . '.md';

        if (! file_exists($file = $project->path . '/' . $articleFileName)) {
            Helpers::abort("Article couldn't be found please check the file name is correct: " . $articleFileName);
        }

        $parser = YamlFrontMatter::parse(file_get_contents($file));

        $article = app(PostyClient::class)->put("articles/{$parser->matter('id')}", [
            'title' => $parser->matter('title'),
            'summary' => $parser->matter('summary'),
            'body' => $parser->body(),
            'status' => $parser->matter('status'),
            'featured_image' => $parser->matter('featured_image'),
            'featured_image_caption' => $parser->matter('featured_image_caption'),
            'topics' => Helpers::parseString($parser->matter('topics')),
            'tags' => Helpers::parseString($parser->matter('tags')),
            'meta' => $parser->matter('meta'),
        ]);

        rename(
            $project->path . DIRECTORY_SEPARATOR . $articleFileName,
            $project->path . DIRECTORY_SEPARATOR . $article['slug'] . '.md'
        );

        $this->info("Article ({$article['title']}) was published successfully.");
    }
}
