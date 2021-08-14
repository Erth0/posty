<?php

namespace App\Commands;

use App\Path;
use App\Helpers;
use App\Resources\Project;
use App\Client\PostyClient;
use LaravelZero\Framework\Commands\Command;

class CreateArticleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'article:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new draft article';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Helpers::validate();

        $project = Project::findByPath(Path::current());
        $topics = collect(app(PostyClient::class)->get('topics'))->pluck('slug')->toArray();
        $tags = collect(app(PostyClient::class)->get('tags'))->pluck('slug')->toArray();
        $details = [];
        $details['title'] = $this->ask('Title');
        $details['status'] = $this->choice('Status', ['published', 'draft'], 'published');
        $details['summary'] = $this->ask('Summary');
        $details['tags'] = [];
        $details['topics'] = [];

        if (count($topics) > 0) {
            $details['topics'] = $this->choice('Topics', $topics, null, null, true);
        }

        if (count($tags) > 0) {
            $details['tags'] = $this->choice('Tags', $tags, null, null, true);
        }

        $article = app(PostyClient::class)->post('articles', $details);

        $details['id'] = $article['id'];

        file_put_contents(
            $file = $project->path . "/{$article['slug']}.md",
            $this->articleTemplate($details)
        );

        $this->info("Article was created successfully: file://{$file}");
    }

    public function articleTemplate(array $details)
    {
        $tags = implode(',', $details['tags']);
        $topics = implode(',', $details['topics']);

        return <<<EOF
        ---
        id: {$details['id']}
        title: {$details['title']}
        summary: {$details['summary']}
        topics: {$topics}
        tags: {$tags}
        status: {$details['status']}
        featured_image:
        featured_image_caption:
        ---
        EOF;
    }
}
