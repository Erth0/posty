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

    /**
     * Find a project by path.
     *
     * @param string $path
     *
     * @return void
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
