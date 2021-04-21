<?php

namespace App\Models;

use Sushi\Sushi;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use Sushi;

    protected $schema = [
        'id' => 'integer',
        'name' => 'string',
        'symbol' => 'string',
        'precision' => 'float'
    ];

    public function getRows()
    {

    }
}
