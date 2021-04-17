<?php

namespace App;

use App\Adapters\ProjectAdapter;

class Project
{
    protected ProjectAdapter $adapter;

    public function __construct(ProjectAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function testConnection()
    {
        $this->adapter->testConnection();
    }

    public function getTags() :array
    {
        return $this->adapter->getTags();
    }

    public function getTopics() :array
    {
        return $this->adapter->getTopics();
    }

    public function getArticle(string $slug) :array
    {
        return $this->adapter->getArticle($slug);
    }

    public function createArticle(array $data)
    {
        return $this->adapter->createArticle($data);
    }

    public function deleteArticle(int $id)
    {
        return $this->adapter->deleteArticle($id);
    }
}
