<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScenarioLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'format'
    ];

}
