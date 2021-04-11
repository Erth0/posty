<?php

namespace App\Commands;

use App\Helpers;
use App\Models\Article;
use App\Actions\UpdateArticleAction;
use LaravelZero\Framework\Commands\Command;
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

            $article = Article::findBySlug($slug = $parser->slug);
            if(! $article) {
                $this->info("Article couldn't be found in the database with slug: {$slug}, skipping...");
                return;
            }

            (new UpdateArticleAction($article, [
                'title' => $parser->title,
                'slug' => $parser->slug,
                'summary' => $parser->summary,
                'body' => $parser->body(),
                'status' => $parser->status,
                'featured_image' => $parser->featured_image,
                'featured_image_caption' => $parser->featured_image_caption,
                'topics' => $parser->topics,
                'tags' => $parser->tags,
            ]))->execute();
        });
    }
}
