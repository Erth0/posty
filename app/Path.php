<?php

namespace App;

class Path
{
    /**
     * Get the path to the built application.
     *
     * @return string
     */
    public static function app()
    {
        return static::build().'/app';
    }

    /**
     * Get the path to the vendor directory.
     *
     * @return string
     */
    public static function vendor()
    {
        return static::build().'/vendor';
    }

    /**
     * Get the path to the deployment artifact.
     *
     * @return string
     */
    public static function artifact()
    {
        return getcwd().'/.posty/build/app.zip';
    }

    /**
     * Get the path to the deployment artifact.
     *
     * @return string
     */
    public static function vendorArtifact()
    {
        return getcwd().'/.posty/build/vendor.zip';
    }

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
     * Get the path to the project's manifest file.
     *
     * @return string
     */
    public static function manifest()
    {
        return getcwd().'/posty.yml';
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
