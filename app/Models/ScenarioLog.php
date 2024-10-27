<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @mixin Builder
 */
class ScenarioLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'format'
    ];

}
