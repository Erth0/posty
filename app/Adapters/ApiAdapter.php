<?php

namespace App\Adapters;

use App\Adapters\ProjectAdapter;
use App\Exceptions\ConnectionFailedException;
use App\Helpers;
use App\Models\Article;
use Illuminate\Http\Client\PendingRequest;

class ApiAdapter implements ProjectAdapter
{
    protected $config;

    protected $client;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->client = (new PendingRequest())
            ->baseUrl($config['api']['base_url'])
            ->withToken($config['api']['api_key'])
            ->acceptJson();
    }

    public function testConnection()
    {
        $response = $this->client->get('posty/test');

        if (! $response->ok()) {
            throw new ConnectionFailedException("Connection with host {$this->config['api']['base_url']} failed.");
        }

        return true;
    }

    public function getTags()
    {
        $response = $this->client->get('posty/tags');

        if(! $response->ok()) {
            Helpers::abort("Can't fetch tags");
        }

        return $response->json();
    }

    public function getTopics()
    {
        $response = $this->client->get('posty/topics');

        if(! $response->ok()) {
            Helpers::abort("Can't fetch topics");
        }

        return $response->json();
    }

    public function getArticle(string $slug)
    {
        $response = $this->client->get("posty/articles/{$slug}/show");

        if(! $response->ok()) {
            Helpers::abort("Can't fetch article with slug: {$slug}");
        }

        return $response->json();
    }

    public function createArticle(array $data)
    {
        $response = $this->client->post('posty/articles', $data);

        if(! $response->ok()) {
            Helpers::abort("Something went wrong while trying to create the article.");
        }

        return $response->json();
    }

    public function updateArticle(int $id, array $data)
    {
        $response = $this->client->post("posty/articles/{$id}/update", $data);

        if(! $response->ok()) {
            Helpers::abort("Something went wrong while trying to update the article.");
        }

        return $response->json();
    }

    public function deleteArticle(int $id)
    {

    }
}
