<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scenario extends Model
{
    use HasFactory;
    protected $fillable = [
        'users_id',
        'devices_id',
        'key',
        'value',
        'scenario_logs_id',
        'scenario_apis_id',
        'scenario_dbs_id',
        'scenario_notifies_id',
        'scenario_modules_id',

    ];
    public function device()
    {
        return $this->belongsTo(Device::class, 'devices_id');
    }
    public function ScenarioDb(){
        return $this->belongsTo(ScenarioDb::class, 'scenario_dbs_id');
    }

    public function scenarioApi(){
        return $this->belongsTo(ScenarioApi::class, 'scenario_apis_id');
    }
    public function scenarioLog(){
        return $this->belongsTo(ScenarioLog::class, 'scenario_logs_id');
    }
    public function scenarioModule(){
        return $this->belongsTo(ScenarioModule::class, 'scenario_modules_id');
    }
    public function scenarioNotify(){
        return $this->belongsTo(ScenarioNotify::class, 'scenario_notifies_id');
    }
}
