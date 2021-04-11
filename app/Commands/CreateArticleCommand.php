<?php

namespace App\Commands;

use App\Helpers;
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
        $details['slug'] = Str::slug($details['title']);
        $details['summary'] = $this->ask('Summary');
        $topics = Topic::select('slug')->pluck('slug')->toArray();
        $tags = Tag::select('slug')->pluck('slug')->toArray();
        $details['topic'] = implode(',', $this->choice('Topics', $topics, null, null, true));
        $details['tags'] = implode(',', $this->choice('Tags', $tags, null, null, true));

        if(file_exists($this->project['local_path'] . "/{$details['slug']}.md")) {
            Helpers::abort('Article file name already exists.');
        }

        $this->task("Creating a new article", function () use($details) {
            return file_put_contents(
                $this->project['local_path'] . "/{$details['slug']}.md",
                $this->articleTemplate($details)
            );
        });
    }

    public function articleTemplate(array $details)
    {
        return <<<EOF
        ---
        title: {$details['title']}
        slug: {$details['slug']}
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
