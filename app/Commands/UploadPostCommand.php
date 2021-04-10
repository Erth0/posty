<?php

namespace App\Commands;

use App\Helpers;
use App\Models\Tag;
use App\Models\Topic;
use App\Models\Article;
use Illuminate\Support\Str;
use LaravelZero\Framework\Commands\Command;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class UploadPostCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'publish';

    /**
     * The description of the command.
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
        $project = Helpers::project();
        $article = $this->ask('Article file name');
        // Check if there is a project linked
        if (! file_exists($file = $project['local_path'] . "/{$article}.md")) {
            $this->error("Article doesn't exists");
        }

        $parser = YamlFrontMatter::parse(file_get_contents($file));
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

        $article = Article::create([
            'title' => $parser->title,
            'slug' => Str::slug($parser->title),
            'summary' => $parser->summary,
            'body' => $parser->body(),
            'published_at' => now(),
            'meta' => [],
        ]);

        $this->info("Article with ID: {$article->id} was created successfully.");
    }
}
