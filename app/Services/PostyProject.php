<?php

namespace App\Services;

class PostyProject
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }
}
