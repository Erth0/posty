<?php

namespace App\Adapters;

interface ProjectAdapter
{
    public function __construct(array $config);

    public function testConnection();

    public function getTags();

    public function getTopics();

    public function getArticle(string $slug);

    public function createInitialArticle(array $data);
}
