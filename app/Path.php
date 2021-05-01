<?php

namespace App;

class Path
{
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
        return getcwd(). DIRECTORY_SEPARATOR . '.posty';
    }

    public static function home()
    {
        return $_SERVER['HOME'];
    }

    public static function databasePath()
    {
        return $_SERVER['HOME'] . DIRECTORY_SEPARATOR . '.posty' . DIRECTORY_SEPARATOR;
    }
}
