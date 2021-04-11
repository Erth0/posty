<?php

namespace App\Commands;

use App\Helpers;
use App\Models\Article;
use Illuminate\Support\Str;
use Illuminate\Console\Scheduling\Schedule;
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
        $articleFileName = $this->argument('article') ?? $this->ask('Article file name');
        if(! file_exists($file = $project['local_path'] . '/' . $articleFileName)) {
            Helpers::abort("Article couldn't be found: " . $articleFileName);
        }

        $parser = YamlFrontMatter::parse(file_get_contents($file));
        $slug = Str::slug($parser->title) ?? $this->ask('Article slug');

        if($slug) {
            Helpers::abort('Article slug is required to find the article');
        }

        $article = Article::findBySlug($slug);

        if(! $article) {
            Helpers::abort("Article ({$slug}) does not exists in the database");
        }

        $article->delete();

        // Delete file
        // unlink($file);

        $this->info("Article ({$article->title}) was deleted successfully");
    }
}
