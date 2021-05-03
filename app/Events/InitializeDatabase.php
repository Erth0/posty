<?php

namespace App\Events;

use Illuminate\Support\Facades\File;
use Illuminate\Database\Migrations\Migrator;

class InitializeDatabase
{
    /**
     * @var Illuminate\Database\Migrations\Migrator
     */
    private $migrator;

    public function __construct(Migrator $migrator)
    {
        $this->migrator = $migrator;
    }

    public function handle(): void
    {
        $databasePath = (string) config('database.connections.sqlite.database');

        if ($databasePath !== ':memory:' && ! File::exists($databasePath)) {
            File::ensureDirectoryExists(dirname($databasePath));
            File::put($databasePath, '');
        }

        if (! $this->migrator->repositoryExists()) {
            $this->migrator->getRepository()->createRepository();
        }

        $this->migrator->run(database_path('migrations'));
    }
}
