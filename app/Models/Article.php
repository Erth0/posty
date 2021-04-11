<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends Model
{
    use HasFactory,
        SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'posty_articles';

    protected $guarded = [];

    protected $casts = [
        'published_at' => 'datetime',
        'meta' => 'array',
    ];

    public static function findBySlug(string $slug)
    {
        return static::where('slug', $slug)->first();
    }

    /**
     * Get the tags relationship.
     *
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            Tag::class,
            'posty_articles_tags',
            'article_id',
            'tag_id'
        );
    }

    /**
     * Get the topics relationship.
     *
     * @return BelongsToMany
     */
    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(
            Topic::class,
            'posty_articles_topics',
            'article_id',
            'topic_id'
        );
    }
}
