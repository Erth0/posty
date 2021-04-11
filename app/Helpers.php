<?php

namespace App;

use App\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Container\Container;
use Symfony\Component\Console\Output\OutputInterface;
use Illuminate\Support\Facades\Config as ApplicationConfig;

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
        $project = $name
            ? Config::get('projects' . '.' . strtolower($name))
            : collect(Config::get('projects'))->first(function ($project) {
                return $project['local_path'] === getcwd();
            });

        if (! $project) {
            static::abort("There is no project linked with the current folder: " . getcwd());
        }

        ApplicationConfig::set("database.connections.{$project['project']}", $project[$project['default_connection']]);
        DB::setDefaultConnection($project['project']);

        return $project;
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
