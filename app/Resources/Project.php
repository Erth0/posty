<?php

namespace App\Resources;

class Project extends Resource
{
    /**
     * Resource key.
     *
     * @var string
     */
    public $resource = 'projects';

    public string $name;

    public string $path;

    public string $auth_token;

    public string $endpoint_prefix;

    public string $endpoint;

    /**
     * Find a project by path.
     *
     * @param string $path
     *
     * @return Project|null
     */
    public static function findByPath(string $path): ?Project
    {
        $instance = new static();

        return $instance->all()->filter(function (Project $project) use ($path) {
            return $project->path === $path;
        })
        ->first();
    }
}
