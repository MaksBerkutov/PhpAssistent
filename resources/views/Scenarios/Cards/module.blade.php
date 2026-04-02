<div id="state-card" class="card mb-3 d-none">
    <div class="card-body">
        <h5 class="card-title">{{ __('ui.scenarios.state_change') }}</h5>
        <p class="text-muted small mb-3">{{ __('ui.scenarios.cards.module_desc') }}</p>

        <x-device-choose
            name="change_module"
            :devices="$devices"
            :label="__('ui.scenarios.cards.module_device')"
            old="{{ isset($scenario) ? optional($scenario->scenarioModule)->devices_id : '' }}"
        />

        <x-device-cmd-choose
            name="change_command"
            deviceChoseName="change_module"
            old="{{ isset($scenario) ? optional($scenario->scenarioModule)->command : '' }}"
            :label="__('ui.scenarios.cards.module_command')"
        />

        <x-device-arg-cmd-choose
            name="change_arg"
            :label="__('ui.scenarios.cards.module_argument')"
            old="{{ isset($scenario) ? optional($scenario->scenarioModule)->arg : '' }}"
        />
    </div>
</div>
