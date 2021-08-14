<?php

namespace App\Commands;

use App\Path;
use App\Helpers;
use App\Resources\Project;
use App\Client\PostyClient;
use Illuminate\Support\Str;
use LaravelZero\Framework\Commands\Command;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class SynchronizeArticlesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'article:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize all articles for this project';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Helpers::validate();

        $project = Project::findByPath(Path::current());

        collect(scandir($project->path))
            ->reject(function ($file) {
                return in_array($file, ['.', '..', '.DS_Store']);
            })
            ->reject(function ($file) use ($project) {
                return is_dir($project->path . DIRECTORY_SEPARATOR . $file);
            })
            ->reject(function ($file) {
                return ! Str::endsWith($file, '.md');
            })
            ->each(function ($file) use ($project) {
                $parser = YamlFrontMatter::parse(file_get_contents($file));

                if (! $parser->matter('id')) {
                    return;
                }

                $article = app(PostyClient::class)->get("articles/{$parser->matter('id')}");

                $article = app(PostyClient::class)->put("articles/{$parser->matter('id')}", [
                    'title' => $parser->matter('title'),
                    'summary' => $parser->matter('summary'),
                    'body' => $parser->body(),
                    'status' => $parser->matter('status'),
                    'featured_image' => $parser->matter('featured_image'),
                    'featured_image_caption' => $parser->matter('featured_image_caption'),
                    'topics' => Helpers::parseString($parser->matter('topics')),
                    'tags' => Helpers::parseString($parser->matter('tags')),
                    'meta' => $parser->matter('meta'),
                ]);

                rename(
                    $project->path . DIRECTORY_SEPARATOR . $file,
                    $project->path . DIRECTORY_SEPARATOR . $article['slug'] . '.md'
                );

                $this->info("Article with ID {$parser->matter('id')} was synchronized successfully.");
            });
    }
}
