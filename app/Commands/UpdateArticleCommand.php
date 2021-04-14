<?php

namespace App\Commands;

use App\Helpers;
use App\Models\Article;
use Illuminate\Support\Str;
use App\Actions\UpdateArticleAction;
use LaravelZero\Framework\Commands\Command;
use Spatie\YamlFrontMatter\YamlFrontMatter;
use Symfony\Component\Console\Input\InputArgument;

class UpdateArticleCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    public function configure()
    {
        $this->setName('update')
            ->addArgument('article', InputArgument::OPTIONAL, "Article name to be published")
            ->setDescription('Publish Article');
    }

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

        $article = Article::find($articleId = $parser->id);
        if(! $article) {
            Helpers::abort("Article couldn't be found in the database with ID: {$articleId}");
        }

        (new UpdateArticleAction([
            'id' => $parser->id,
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
        ]))->execute();

        $this->info("Article ({$article->title}) was updated successfully.");
    }
}