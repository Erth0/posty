<?php

namespace App;

use Exception;
use App\Config;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Container\Container;
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
        // static::danger($text);

        exit(1);
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
            : collect(Config::get('projects'))->first(function($project) {
                return $project['local_path'] === getcwd();
            });

        if(! $project) {
            static::abort("Project is not linked");
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
     * Ensure that the user has authenticated with Laravel Vapor.
     *
     * @return void
     */
    public static function ensure_api_token_is_available()
    {
        if (isset($_ENV['VAPOR_API_TOKEN']) ||
            getenv('VAPOR_API_TOKEN')) {
            return;
        }

        if (! static::config('token') || ! static::config('team')) {
            throw new Exception("Please authenticate using the 'login' command before proceeding.");
        }
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
