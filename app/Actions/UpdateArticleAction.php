<?php

namespace App\Actions;

use App\Helpers;
use App\Models\Tag;
use App\Models\Topic;
use App\Models\Article;

class UpdateArticleAction
{
    /**
     * Article to update
     */
    protected Article $article;

    /**
     * Article data
     */
    protected array $data;

    public function __construct(Article $article, array $data)
    {
        $this->article = $article;
        $this->data = $data;
    }

    public function execute()
    {
        $this->validateArticleCanBeCreated();

        $this->article->update([
            'title' => $this->data['title'],
            'slug' => $this->data['slug'],
            'summary' => $this->data['summary'],
            'body' => $this->data['body'],
            'published_at' => $this->data['status'] === 'published' ? $this->article->published_at : null,
            'featured_image' => $this->data['featured_image'],
            'featured_image_caption' => $this->data['featured_image_caption'],
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

        $this->article->topics()->sync($topicsIds);
        $this->article->tags()->sync($tagsIds);

        return $this->article;
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
    }
}
