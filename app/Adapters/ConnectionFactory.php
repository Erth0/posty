<?php

namespace App\Adapters;

use InvalidArgumentException;

class ConnectionFactory
{
    public function make(array $config)
    {
        $this->createConnector($config)->connect($config);
    }

    public function createConnector(array $config)
    {
        if(! isset($config['driver'])) {
            throw new InvalidArgumentException('A Driver must be specified');
        }

        switch ($config['driver']) {
            case 'api':
                # code...
                break;
            case 'database':
                # code...
                break;
        }

        throw new InvalidArgumentException("Unsupported driver [{$config['driver']}].");
    }
}
