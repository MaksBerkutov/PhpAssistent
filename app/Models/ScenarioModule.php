<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScenarioModule extends Model
{
    use HasFactory;
    protected $fillable = [
        'devices_id',
        'command'
    ];
    public function device()
    {
        return $this->belongsTo(Device::class, 'devices_id');
    }

}
