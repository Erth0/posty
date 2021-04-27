<?php

namespace App;

class Path
{
    /**
     * Get the path to the built application's public directory.
     *
     * @return string
     */
    public static function assets()
    {
        return getcwd().'/.posty/build/app/public';
    }

    /**
     * Get the path to the posty build directory.
     *
     * @return string
     */
    public static function build()
    {
        return static::posty().'/build';
    }

    /**
     * Get the path to the current working directory.
     *
     * @return string
     */
    public static function current()
    {
        return getcwd();
    }

    /**
     * Get the path to the hidden Posty directory.
     *
     * @return string
     */
    public static function posty()
    {
        return getcwd().'/.posty';
    }
}
