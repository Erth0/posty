<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';

    protected $fillable = [
        'name',
        'path',
        'endpoint',
        'endpoint_prefix',
        'auth_token'
    ];

    public static function findByPath(string $path)
    {
        return Project::where('path', $path)->first();
    }
}
