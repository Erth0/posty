<?php

namespace App\Commands;

use App\Helpers;
use App\Models\Tag;
use App\Models\Topic;
use App\Models\Article;
use Illuminate\Support\Str;
use LaravelZero\Framework\Commands\Command;
use Spatie\YamlFrontMatter\YamlFrontMatter;
use Symfony\Component\Console\Input\InputArgument;

class PublishArticleCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    public function configure()
    {
        $this->setName('publish')
            ->setAliases(['push'])
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

        // Check if there is a project linked
        if (! file_exists($file = $project['local_path'] . '/' . $articleFileName)) {
            Helpers::abort("Article couldn't be found please check the file name is correct: " . $articleFileName);
        }

        // Check for validation
        $parser = YamlFrontMatter::parse(file_get_contents($file));
        if(! $parser->title) {
            Helpers::abort('Article title is required');
        }

        if(! $parser->body() || $parser->body() === '') {
            Helpers::abort('Article body is required');
        }

        // Check that article does not exists already
        $article = Article::findBySlug($slug = $parser->slug ?? Str::slug($parser->title));
        if($article) {
            Helpers::abort("Article with slug ({$slug}) already exists");
        }

        $article = Article::create([
            'title' => $parser->title,
            'slug' => $slug,
            'summary' => $parser->summary,
            'body' => $parser->body(),
            'published_at' => $parser->published_at ?? now(),
            'meta' => [],
        ]);

        $topics = explode(',', $parser->topics);
        $topicsIds = [];
        foreach ($topics as $topicSlug) {
            $topic = Topic::where('slug', $topicSlug)->first();
            if (! $topic) {
                $topic = Topic::create([
                    'slug' => $topicSlug,
                    'name' => ucfirst($topicSlug),
                ]);
            }
            $topicsIds[] = $topic->id;
        }

        $tags = explode(',', $parser->tags);
        $tagsIds = [];
        foreach ($tags as $tagSlug) {
            $tag = Tag::where('slug', $tagSlug)->first();
            if (! $tag) {
                $tag = Tag::create([
                    'slug' => $tagSlug,
                    'name' => ucfirst($tagSlug),
                ]);
            }
            $tagsIds[] = $tag->id;
        }

        $article->topics()->attach($topicsIds);
        $article->tags()->attach($tagsIds);

        $this->info("Article with ID: {$article->id} was created successfully.");
    }
}
