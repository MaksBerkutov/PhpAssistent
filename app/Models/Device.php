<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @mixin Builder
 */
class Device extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'url',
        'user_id',
        'command',
        'name_board',
        'available'
    ];

}
