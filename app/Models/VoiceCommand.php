<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @mixin Builder
 */
class VoiceCommand extends Model
{
    use HasFactory;
    protected  $fillable = [
        'devices_id',
        'users_id',
        'command',
        'text_trigger',
        'voice'
    ];
    public function device()
    {
        return $this->belongsTo(Device::class, 'devices_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
