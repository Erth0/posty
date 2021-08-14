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

                if (! $parser->id) {
                    return;
                }

                $article = app(PostyClient::class)->get("articles/{$parser->id}");

                if (! $article) {
                    $this->info("Article couldn't be found in the database, skipping...");
                    return;
                }

                $article = app(PostyClient::class)->put("articles/{$parser->id}", [
                    'title' => $parser->title,
                    'slug' => $parser->slug,
                    'summary' => $parser->summary,
                    'body' => $parser->body(),
                    'status' => $parser->status,
                    'featured_image' => $parser->featured_image,
                    'featured_image_caption' => $parser->featured_image_caption,
                    'topics' => Helpers::parseString($parser->topics),
                    'tags' => Helpers::parseString($parser->tags),
                    'meta' => $parser->meta,
                ]);

                rename(
                    $project->path . DIRECTORY_SEPARATOR . $file,
                    $project->path . DIRECTORY_SEPARATOR . $article['slug'] . '.md'
                );

                $this->info("Article with ID {$parser->id} was synchronized successfully.");
            });
    }
}
