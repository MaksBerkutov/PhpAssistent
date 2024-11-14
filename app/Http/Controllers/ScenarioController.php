<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Scenario;
use App\Models\ScenarioApi;
use App\Models\ScenarioDb;
use App\Models\ScenarioLog;
use App\Models\ScenarioModule;
use App\Models\ScenarioNotify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScenarioController extends Controller
{
    public function index( ){
        //$scenarios = Scenario::with(['devices', 'scenarioModule'])->get();
        $scenarios = Scenario::all();
        return view('Scenarios.index', compact('scenarios'));
    }
    public function create( ){
        $devices = Device::where('user_id',  Auth::id())->get();

        return view('Scenarios.create',compact('devices'));
    }
    private function getValidator(){
        return [
            'devices_id' => 'required|exists:devices,id',
            'key' => 'required|string',
            'value' => 'required|string',
            'actions' => 'required|array',
            'log_format' => 'nullable|string',
            'db_login' => 'nullable|string',
            'db_password' => 'nullable|string',
            'db_name' => 'nullable|string',
            'db_table' => 'nullable|string',
            'db_key_field' => 'nullable|string',
            'db_value_field' => 'nullable|string',
            'notification_message' => 'nullable|string',
            'notification_type' => 'nullable|string',
            'change_module' => 'nullable|exists:devices,id',
            'change_command' => 'nullable|string',
            'change_arg'=>'nullable|string',
            'api_url' => 'nullable|url',
            'api_body' => 'nullable|string',
        ];
    }
    public function store(request $request){

        $request->validate($this->getValidator());
        $scenarioLogId = null;
        if (in_array('log', $request->actions)) {
            $log = ScenarioLog::create(['format' => $request->log_format]);
            $scenarioLogId = $log->id;
        }

        $scenarioDbId = null;
        if (in_array('save_db', $request->actions)) {
            $db = ScenarioDb::create([
                'login' => $request->db_login,
                'password' => $request->db_password,
                'db_name' => $request->db_name,
                'table_name' => $request->db_table,
                'name_key' => $request->db_key_field,
                'name_value' => $request->db_value_field,
            ]);
            $scenarioDbId = $db->id;
        }

        $scenarioNotifyId = null;
        if (in_array('notify', $request->actions)) {
            $notify = ScenarioNotify::create(['format' => $request->notification_message,'type'=>$request->notification_type]);
            $scenarioNotifyId = $notify->id;
        }

        $scenarioModuleId = null;
        if (in_array('change_state', $request->actions)) {
            $module = ScenarioModule::create([
                'devices_id' => $request->change_module,
                'command' => $request->change_command,
                'arg'=>$request->change_arg,
            ]);
            $scenarioModuleId = $module->id;
        }

        $scenarioApiId = null;
        if (in_array('send_api', $request->actions)) {
            $api = ScenarioApi::create([
                'format' => $request->api_body,
                'url' => $request->api_url,
            ]);
            $scenarioApiId = $api->id;
        }

        Scenario::create([
            'users_id' => Auth::id(),
            'devices_id' => $request->devices_id,
            'key' => $request->key,
            'value' => $request->value,
            'scenario_logs_id' => $scenarioLogId,
            'scenario_apis_id' => $scenarioApiId,
            'scenario_dbs_id' => $scenarioDbId,
            'scenario_notifies_id' => $scenarioNotifyId,
            'scenario_modules_id' => $scenarioModuleId,
        ]);
        return redirect()->route('scenario')->with('success', 'Сценарий успешно создан');
    }
    public function edit($id)
    {
        $scenario = Scenario::findOrFail($id);
        $devices = Device::all();


        return view('Scenarios.edit', compact('scenario', 'devices'));
    }

    private function update_scanries($valueFromTables,$nameInArray,$array,$class,$UpdateCallback,$CreateCallback){
        if(in_array($nameInArray,$array)){
            if($valueFromTables==null)
                return $CreateCallback()->id;
            else{
                $UpdateCallback($class::findOrFail($valueFromTables));
                return $valueFromTables;

            }

        }
        if($valueFromTables!=null){
            $class::findOrFail($valueFromTables)->delete();
        }
        return NULL;
    }
    public function update(Request $request, $id)
    {
        // Валидация входящих данных
        $request->validate($this->getValidator());

        // Получаем сценарий по ID
        $scenario = Scenario::findOrFail($id);

        $scenarioLogId = $this->update_scanries($scenario->scenario_logs_id,'log',$request->actions,ScenarioLog::class, function($object) use ($request){
            return $object->update(['format' => $request->log_format]);
        },
            function() use ($request){
            return ScenarioLog::create(['format' => $request->log_format]);
        });



        $scenarioDbId = $this->update_scanries($scenario->scenario_dbs_id,'save_db',$request->actions,ScenarioDb::class, function($object) use ($request){
            return $object->update(['login' => $request->db_login,
                'password' => $request->db_password,
                'db_name' => $request->db_name,
                'table_name' => $request->db_table,
                'name_key' => $request->db_key_field,
                'name_value' => $request->db_value_field,]);
        },
            function() use ($request){
                return ScenarioDb::create([
                    'login' => $request->db_login,
                    'password' => $request->db_password,
                    'db_name' => $request->db_name,
                    'table_name' => $request->db_table,
                    'name_key' => $request->db_key_field,
                    'name_value' => $request->db_value_field,
                ]);
            });


        $scenarioNotifyId = $this->update_scanries($scenario->scenario_notifies_id,'notify',$request->actions,ScenarioNotify::class, function($object) use ($request){
            return $object->update(['format' => $request->notification_message,'type'=>$request->notification_type]);
        },
            function() use ($request){
                return  ScenarioNotify::create(['format' => $request->notification_message,'type'=>$request->notification_type]);
            });


        $scenarioModuleId = $this->update_scanries($scenario->scenario_modules_id,'change_state',$request->actions,ScenarioModule::class, function($object) use ($request){
            return $object->update(['devices_id' => $request->change_module,
                'command' => $request->change_command,'arg'=>$request->change_arg,]);
        },
            function() use ($request){
                return ScenarioModule::create([
                    'devices_id' => $request->change_module,
                    'command' => $request->change_command,
                ]);
            });


        $scenarioApiId =$this->update_scanries($scenario->scenario_apis_id,'send_api',$request->actions,ScenarioApi::class, function($object) use ($request){
            return $object->update(['format' => $request->api_body,
                'url' => $request->api_url,]);
        },
            function() use ($request){
                return ScenarioApi::create([
                    'format' => $request->api_body,
                    'url' => $request->api_url,
                ]);
            });

        // Обновляем сценарий с новыми данными
        $scenario->update([
            'devices_id' => $request->devices_id,
            'key' => $request->key,
            'value' => $request->value,
            'scenario_logs_id' => $scenarioLogId,
            'scenario_apis_id' => $scenarioApiId,
            'scenario_dbs_id' => $scenarioDbId,
            'scenario_notifies_id' => $scenarioNotifyId,
            'scenario_modules_id' => $scenarioModuleId,
        ]);

        return redirect()->route('scenario')->with('success', 'Сценарий успешно обновлён.');
    }

    public function delete($id)
    {


        $scenario = Scenario::findOrFail($id);
        if($scenario->users_id != Auth::id()){
            return redirect()->route('scenario')->with('error', 'Это не ваш сценарий.');
        }
        if($scenario->scenario_logs_id != null){
            $scenario->scenarioLog->delete();
        }
        if($scenario->scenario_apis_id != null){
            $scenario->scenarioApi->delete();
        }
        if($scenario->scenario_dbs_id != null){
            $scenario->ScenarioDb->delete();
        }
        if($scenario->scenario_notifies_id != null){
            $scenario->scenarioNotify->delete();
        }
        if($scenario->scenario_modules_id != null){
            $scenario->scenarioModule->delete();
        }
        $scenario->delete();






        return redirect()->route('scenario')->with('success', 'Сценарий успешно удалён.');
    }

}
