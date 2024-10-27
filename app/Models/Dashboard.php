<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @mixin Builder
 */
class Dashboard extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'device_id',
        'widget_id',
        'command',
        'name',
        'key',
        'values',
    ];
    public function widget()
    {
        return $this->belongsTo(Widget::class, 'widget_id');
    }
    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id');
    }
}
