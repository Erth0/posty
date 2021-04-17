<?php

namespace App\Adapters;

use Exception;
use App\Models\Tag;
use App\Models\Topic;
use App\Models\Article;
use App\Adapters\ProjectAdapter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Exceptions\ConnectionFailedException;

class DatabaseAdapter implements ProjectAdapter
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
        Config::set("database.connections.{$config['project']}", $config[$config['default_connection']]);
        DB::setDefaultConnection($config['project']);
    }

    public function testConnection()
    {
        try {
            DB::connection()->getPdo();

            return true;
        } catch (Exception $e) {
            throw new ConnectionFailedException($e->getMessage());
        }
    }

    public function getTags()
    {
        return Tag::select('slug', 'name')->get()->toArray();
    }

    public function getTopics()
    {
        return Topic::select('slug', 'name')->get()->toArray();
    }

    public function getArticle(string $slug)
    {
        return Article::with('topics', 'tags')->where('slug', $slug)->first();
    }

    public function createInitialArticle()
    {
    }
}
