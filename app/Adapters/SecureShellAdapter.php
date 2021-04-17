<?php

namespace App\Adapters;

use App\Adapters\ProjectAdapter;

class SecureShellAdapter implements ProjectAdapter
{
    public function testConnection()
    {
        dd('This is the ssh testing');
    }
}
