<?php

namespace App;

use App\Models\Project;
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

    public static function validate()
    {
        $project = Project::findByPath(Path::current());

        if (! $project) {
            Helpers::abort("No project linked with the current folder: " . getcwd());
        }
    }
}
