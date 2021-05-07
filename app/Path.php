<?php

namespace App;

class Path
{
    /**
     * Get the path to the current working directory.
     *
     * @return string
     */
    public static function current(): string
    {
        return getcwd();
    }
}
