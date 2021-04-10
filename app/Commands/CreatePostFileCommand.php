<?php

namespace App\Commands;

use App\Helpers;
use App\Models\Tag;
use App\Models\Topic;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use LaravelZero\Framework\Commands\Command;

class CreatePostFileCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'article:create';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create a new article';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $project = Helpers::project();
        $details = [];
        $details['title'] = $this->ask('Title');
        $details['slug'] = Str::slug($details['title']);
        $details['summary'] = $this->ask('Summary');
        $topics = DB::connection($project['project'])->table('posty_topics')->get('slug')->pluck('slug')->toArray();
        $tags = DB::connection($project['project'])->table('posty_tags')->get('slug')->pluck('slug')->toArray();
        $details['topic'] = implode(',', $this->choice('Topics', $topics, null, null, true));
        $details['tags'] = implode(',', $this->choice('Tags', $tags, null, null, true));

        if(file_exists($project['local_path'] . "/{$details['slug']}.md")) {
            $this->error('This article already exists.');
        }

        $this->task("Creating a new article", function () use($project, $details) {
            file_put_contents($project['local_path'] . "/{$details['slug']}.md", $this->articleTemplate($details));

            return true;
        });
    }

    public function articleTemplate(array $details)
    {
        return <<<EOF
        ---
        title: {$details['title']}
        summary: {$details['summary']}
        topic: {$details['topic']}
        tags: {$details['tags']}
        preview_image:
        preview_image_twitter:
        ---
        EOF;
    }
}
