<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apps extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'version',
        'entrypoint',
        'description',
        'schema',
    ];

    protected $casts = [
        'schema' => 'array',
    ];
}
