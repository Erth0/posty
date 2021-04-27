<?php

namespace App;

use App\Config;
use Illuminate\Container\Container;
use Symfony\Component\Console\Output\OutputInterface;

class Helpers
{
    /**
     * Display a danger message and exit.
     *
     * @param string $text
     *
     * @return void
     */
    public static function abort($text)
    {
        static::danger($text);

        exit(1);
    }

    /**
     * Display a danger message.
     *
     * @param string $text
     *
     * @return void
     */
    public static function danger($text)
    {
        static::app(OutputInterface::class)->writeln('<fg=red>'.$text.'</>');
    }

    /**
     * Resolve a service from the container.
     *
     * @param string|null $name
     *
     * @return mixed
     */
    public static function app($name = null)
    {
        return $name ? Container::getInstance()->make($name) : Container::getInstance();
    }

    /**
     * Resolve a service from the container.
     *
     * @param string|null $name
     *
     * @return mixed
     */
    public static function project($name = null)
    {
        $config = $name
            ? Config::get(strtolower($name))
            : collect(Config::load())->first(function ($project) {
                return $project['local_path'] === getcwd();
            });

        if (! $config) {
            Helpers::abort("No project linked with the current folder: " . getcwd());
        }

        return $config;
    }

    /**
     * Get or set configuration values.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed
     */
    public static function config($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $innerKey => $value) {
                Config::set($innerKey, $value);
            }

            return;
        }

        return Config::get($key, $value);
    }

    /**
     * Get the home directory for the user.
     *
     * @return string
     */
    public static function home()
    {
        return $_SERVER['HOME'] ?? $_SERVER['USERPROFILE'];
    }
}
