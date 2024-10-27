<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
