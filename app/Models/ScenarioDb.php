<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @mixin Builder
 */
class ScenarioDb extends Model
{
    use HasFactory;
    protected $fillable = [
        'login',
        'password',
        'db_name',
        'table_name',
        'name_key',
        'name_value'
    ];

}
