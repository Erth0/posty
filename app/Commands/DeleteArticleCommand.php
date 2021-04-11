<?php

namespace App\Commands;

use App\Helpers;
use App\Models\Article;
use Illuminate\Support\Str;
use LaravelZero\Framework\Commands\Command;
use Spatie\YamlFrontMatter\YamlFrontMatter;
use Symfony\Component\Console\Input\InputArgument;

class DeleteArticleCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    public function configure()
    {
        $this->setName('delete')
            ->setAliases(['remove'])
            ->addArgument('article', InputArgument::OPTIONAL, "Article file name to be deleted")
            ->setDescription('Delete Article');
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

        if(! file_exists($file = $project['local_path'] . '/' . $articleFileName)) {
            Helpers::abort("Article couldn't be found: " . $articleFileName);
        }

        $parser = YamlFrontMatter::parse(file_get_contents($file));
        $slug = $parser->slug ?? Str::slug($parser->title);

        if(! $slug) {
            Helpers::abort('Article slug is required to find the article');
        }

        $article = Article::findBySlug($slug);

        if(! $article) {
            Helpers::abort("Article ({$slug}) does not exists in the database");
        }

        $article->delete();

        unlink($file);

        $this->info("Article ({$article->title}) was deleted successfully");
    }
}
