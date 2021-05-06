<?php

namespace App\Resources;

class Project extends Resource
{
    public $resource = 'projects';

    public static function findByPath(string $path)
    {
        $instance = new static();

        return $instance->all()->filter(function (Project $project) use ($path) {
            return $project->path === $path;
        })
        ->first();
    }
}
