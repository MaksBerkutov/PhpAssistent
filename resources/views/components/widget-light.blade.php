<link rel="stylesheet" href="{{ asset('css/LightSwitch.css') }}">
<link rel="stylesheet" href="{{ asset('css/deactive.css') }}">

<div class="col-md-6 @if (!$isOnline) deactivated @endif">
    <div class="card rounded-3 switchContainer">
        <div class="card-header rounded-top d-flex justify-content-between align-items-center gap-2">
            <span>{{ $name }}</span>
            <x-online-status status="{{ $isOnline }}" />

            <div class="form-check form-switch mb-0">
                <label class="switch mb-0">
                    <input class="switch__input" type="checkbox" {{ $is_light ? 'checked' : '' }} role="switch" onchange="changeWidgetLight{{ $id }}(event)">
                    <svg class="switch__icon switch__icon--light" viewBox="0 0 12 12" width="12" height="12" aria-hidden="true">
                        <g fill="none" stroke="#fff" stroke-width="1" stroke-linecap="round">
                            <circle cx="6" cy="6" r="2" />
                            <g stroke-dasharray="1.5 1.5">
                                <polyline points="6 10,6 11.5" transform="rotate(0,6,6)" />
                                <polyline points="6 10,6 11.5" transform="rotate(45,6,6)" />
                                <polyline points="6 10,6 11.5" transform="rotate(90,6,6)" />
                                <polyline points="6 10,6 11.5" transform="rotate(135,6,6)" />
                                <polyline points="6 10,6 11.5" transform="rotate(180,6,6)" />
                                <polyline points="6 10,6 11.5" transform="rotate(225,6,6)" />
                                <polyline points="6 10,6 11.5" transform="rotate(270,6,6)" />
                                <polyline points="6 10,6 11.5" transform="rotate(315,6,6)" />
                            </g>
                        </g>
                    </svg>
                    <svg class="switch__icon switch__icon--dark" viewBox="0 0 12 12" width="12" height="12" aria-hidden="true">
                        <g fill="none" stroke="#fff" stroke-width="1" stroke-linejoin="round" transform="rotate(-45,6,6)">
                            <path d="m9,10c-2.209,0-4-1.791-4-4s1.791-4,4-4c.304,0,.598.041.883.105-.995-.992-2.367-1.605-3.883-1.605C2.962.5.5,2.962.5,6s2.462,5.5,5.5,5.5c1.516,0,2.888-.613,3.883-1.605-.285.064-.578.105-.883.105Z" />
                        </g>
                    </svg>
                    <span class="switch__sr">{{ __('ui.widgets.light_toggle') }}</span>
                </label>
            </div>
        </div>

        <div class="card-body text-center">
            <ion-icon name="sunny-outline" style="font-size: 50px;"></ion-icon>
            <h5 class="card-title mt-3">{{ __('ui.widgets.light_title') }}</h5>
            <p class="card-text mb-0">{{ __('ui.common.status') }}: <span id="light-status-{{ $id }}">{{ $is_light ? __('ui.widgets.light_on') : __('ui.widgets.light_off') }}</span></p>
        </div>
    </div>
</div>

<script>
    function changeWidgetLight{{ $id }}(event) {
        const status = document.getElementById('light-status-{{ $id }}');

        if (!event.target.checked) {
            PostSend({
                devices_id: '{{ $device_id }}',
                command: '{{ $command_on }}',
                arg: '{{ $arg_command_on }}'
            });
            status.textContent = '{{ __('ui.widgets.light_on') }}';
        } else {
            PostSend({
                devices_id: '{{ $device_id }}',
                command: '{{ $command_off }}',
                arg: '{{ $arg_command_off }}'
            });
            status.textContent = '{{ __('ui.widgets.light_off') }}';
        }
    }
</script>
