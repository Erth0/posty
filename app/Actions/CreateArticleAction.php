<?php

namespace App\Actions;

use App\Helpers;
use App\Models\Tag;
use App\Models\Topic;
use App\Models\Article;

class CreateArticleAction
{
    /**
     * Article data
     */
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function execute()
    {
        $this->validateArticleCanBeCreated();

        $article = Article::create([
            'title' => $this->data['title'],
            'slug' => $this->data['slug'],
            'summary' => $this->data['summary'],
            'body' => $this->data['body'],
            'published_at' => $this->data['status'] === 'published' ? now() : null,
            'featured_image' => $this->data['featured_image'],
            'featured_image_caption' => $this->data['featured_image_caption'],
            'meta' => [],
        ]);

        $topics = explode(',', $this->data['topics']);
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

        $tags = explode(',', $this->data['tags']);
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

        return $article;
    }

    /**
     * Validate that an article can be created
     *
     * @return void
     */
    private function validateArticleCanBeCreated()
    {
        if(! $this->data['title']) {
            Helpers::abort('Article title is required');
        }

        if(! $this->data['body'] || $this->data['body'] === '') {
            Helpers::abort('Article body is required');
        }

        $article = Article::findBySlug($slug = $this->data['slug']);
        if($article) {
            Helpers::abort("Article with slug ({$slug}) already exists");
        }
    }
}
