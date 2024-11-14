<div id="state-card" class="card mb-3 d-none">
    <div class="card-body">
        <h5 class="card-title">Настройки изменения состояния модуля</h5>
        <x-device-choose name="change_module" :devices="$devices"
                         label="Выберите модуль для изменения состояния"
                         old="{{isset($scenario)?optional($scenario->scenarioModule)->devices_id:''}}"/>

        <x-device-cmd-choose name="change_command" deviceChoseName="change_module"
                             old="{{isset($scenario)?optional($scenario->scenarioModule)->command:''}}"
        label="Команда для модуля"/>
        <div class="form-group">
            <label for="change_arg">Аргумент</label>
            <input type="text" class="form-control " id="change_arg" name="change_arg"
                   value="{{isset($scenario)?optional($scenario->scenarioModule)->arg:''}}">
        </div>

    </div>
</div>
