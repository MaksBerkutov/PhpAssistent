<div id="state-card" class="card mb-3 d-none">
    <div class="card-body">
        <h5 class="card-title">Изменение состояния устройства</h5>
        <p class="text-muted small mb-3">Выберите устройство и команду, которая будет отправлена при срабатывании сценария.</p>

        <x-device-choose
            name="change_module"
            :devices="$devices"
            label="Устройство"
            old="{{ isset($scenario) ? optional($scenario->scenarioModule)->devices_id : '' }}"
        />

        <x-device-cmd-choose
            name="change_command"
            deviceChoseName="change_module"
            old="{{ isset($scenario) ? optional($scenario->scenarioModule)->command : '' }}"
            label="Команда"
        />

        <x-device-arg-cmd-choose
            name="change_arg"
            label="Аргумент"
            old="{{ isset($scenario) ? optional($scenario->scenarioModule)->arg : '' }}"
        />
    </div>
</div>
