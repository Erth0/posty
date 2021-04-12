<?php

namespace App\Commands;

use App\Helpers;
use App\Models\Article;
use App\Models\Tag;
use App\Models\Topic;
use Illuminate\Support\Str;
use LaravelZero\Framework\Commands\Command;

class CreateArticleCommand extends Command
{
    protected $project;

    /**
     * Configure the command options.
     *
     * @return void
     */
    public function configure()
    {
        $this->setName('create')
            ->setAliases(['article:create', 'create:article'])
            ->setDescription('Create a new article');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->project = Helpers::project();

        $details = [];
        $details['title'] = $this->ask('Title');
        $details['status'] = $this->choice('Status', ['draft', 'published'], 'published');
        $details['summary'] = $this->ask('Summary');
        $topics = Topic::select('slug')->pluck('slug')->toArray();
        $tags = Tag::select('slug')->pluck('slug')->toArray();
        $details['topic'] = implode(',', $this->choice('Topics', $topics, null, null, true));
        $details['tags'] = implode(',', $this->choice('Tags', $tags, null, null, true));

        $article = Article::create([
            'title' => $details['title'],
            'summary' => $details['summary']
        ]);

        $details['id'] = $article->id;

        file_put_contents(
            $file = $this->project['local_path'] . "/{$article->slug}.md",
            $this->articleTemplate($details)
        );

        $this->info("Article was created successfully: file://{$file}");
    }

    public function articleTemplate(array $details)
    {
        return <<<EOF
        ---
        id: {$details['id']}
        title: {$details['title']}
        summary: {$details['summary']}
        topic: {$details['topic']}
        tags: {$details['tags']}
        status: {$details['status']}
        featured_image:
        featured_image_caption:
        ---
        EOF;
    }
}
