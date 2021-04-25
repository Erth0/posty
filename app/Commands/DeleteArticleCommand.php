<?php

namespace App\Commands;

use App\Command;
use App\Helpers;
use Illuminate\Support\Str;
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

        if(! $parser->id) {
            Helpers::abort('Article id is required to find the article');
        }

        $article = $this->client->get("articles/{$parser->id}");

        if(! $article) {
            Helpers::abort("Article ({$parser->id}) does not exists in the database");
        }

        $confirmation = $this->confirm("Are you sure your would like to delete this article: ({$article['title']})");

        if($confirmation) {
            $this->client->delete("articles/{$parser->id}");

            unlink($file);

            $this->info("Article ({$article['title']}) was deleted successfully");
        }
    }
}
