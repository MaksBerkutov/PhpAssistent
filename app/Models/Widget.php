<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @mixin Builder
 */
class Widget extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'widget_name',
        'input_params',
        'is_private',
        'accesses_key'
    ];
    protected $casts = [
        'accesses_key' => 'hashed',
    ];
}
