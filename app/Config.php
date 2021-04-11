<?php

namespace App;

use Illuminate\Support\Arr;
class Config
{
    /**
     * Get the given configuration value.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return void
     */
    public static function get($key, $default = null)
    {
        return Arr::get(static::load(), $key, $default);
    }

    /**
     * Store the given configuration value.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public static function set($key, $value)
    {
        $config = static::load();

        Arr::set($config, $key, $value);

        file_put_contents(static::path(), json_encode($config, JSON_PRETTY_PRINT));
    }

    /**
     * Check the given configuration exists
     *
     * @param string $key
     *
     * @return boolean
     */
    public static function has(string $key)
    {
        return static::get($key) ? true : false;
    }

    /**
     * Forget the given configuration value
     *
     * @param string $key
     *
     * @return void
     */
    public static function forget(string $key)
    {
        $config = static::load();

        Arr::forget($config, $key);

        file_put_contents(static::path(), json_encode($config, JSON_PRETTY_PRINT));
    }

    /**
     * Load the entire configuration array.
     *
     * @return array
     */
    public static function load()
    {
        if (! is_dir(dirname(static::path()))) {
            mkdir(dirname(static::path()), 0755, true);
        }

        if (file_exists(static::path())) {
            return json_decode(file_get_contents(static::path()), true);
        }

        return [];
    }

    /**
     * Get the path to the configuration file.
     *
     * @return string
     */
    protected static function path()
    {
        return '/Users/eth0/cli/posty/config.json';
        return Path::current() . '/config.json';
        dd($_SERVER['HOME'] ?? $_SERVER['USERPROFILE']);
        return $_SERVER['HOME'] ?? $_SERVER['USERPROFILE'] . '/.posty/config.json';
    }
}
