@extends('layouts.menu')
@section('title', __('ui.dashboard.catalog_title'))

@section('styles')
    <style>
        .widget-filter {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 10px;
            align-items: end;
        }

        .widget-card {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .widget-card .card-body {
            display: flex;
            flex-direction: column;
            min-height: 0;
            max-height: min(62vh, 560px);
            overflow-y: auto;
            scrollbar-width: thin;
        }

        .widget-params {
            display: grid;
            gap: 8px;
            margin-top: 10px;
        }

        .widget-param {
            border: 1px solid var(--line);
            border-radius: 10px;
            background: color-mix(in srgb, var(--surface-muted) 90%, transparent);
            padding: 8px 10px;
        }

        .widget-param small {
            display: block;
            color: var(--ink-soft);
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 2px;
        }

        .widget-modal-block {
            border: 1px solid var(--line);
            border-radius: 12px;
            background: color-mix(in srgb, var(--surface-muted) 90%, transparent);
            padding: 10px;
            margin-bottom: 10px;
        }

        #addWidgetModal .modal-dialog {
            max-width: min(960px, calc(100vw - 24px));
        }

        #addWidgetModal .modal-body {
            max-height: calc(100dvh - 210px);
            overflow-y: auto;
        }

        @media (max-width: 640px) {
            .widget-filter {
                grid-template-columns: 1fr;
            }

            .widget-filter .btn {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-shell">
        <section class="page-head">
            <div>
                <h2 class="page-title">{{ __('ui.dashboard.catalog_title') }}</h2>
                <p class="page-subtitle">{{ __('ui.dashboard.catalog_subtitle') }}</p>
            </div>
        </section>

        <section class="page-card">
            <form id="accessForm" class="widget-filter">
                <div>
                    <label for="accessKey" class="form-label mb-1">{{ __('ui.dashboard.access_key_label', ['optional' => __('ui.common.optional')]) }}</label>
                    <input id="accessKey" type="text" class="form-control" placeholder="{{ __('ui.dashboard.access_key_placeholder') }}">
                </div>
                <button type="button" class="btn btn-outline-primary" onclick="applyAccessKey()">{{ __('ui.dashboard.apply_key') }}</button>
            </form>
        </section>

        @if($widgets->isEmpty())
            <section class="page-empty">
                <p class="mb-0">{{ __('ui.dashboard.no_widgets_for_key') }}</p>
            </section>
        @else
            <section class="page-grid">
                @foreach($widgets as $widget)
                    @php
                        $commands = is_array($widget->input_params) ? $widget->input_params : (json_decode($widget->input_params, true) ?? []);
                    @endphp
                    <article class="card widget-card">
                        <div class="card-header d-flex justify-content-between align-items-center gap-2">
                            <strong>{{ $widget->name }}</strong>
                            <span class="chip">{{ $widget->widget_name }}</span>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div class="kv-grid mb-3">
                                <div class="kv-item">
                                    <small>{{ __('ui.dashboard.access_key') }}</small>
                                    <strong>{{ $widget->accesses_key ?: __('ui.dashboard.not_required') }}</strong>
                                </div>
                            </div>

                            @if (!empty($commands))
                                <div class="widget-params">
                                    @foreach($commands as $key => $value)
                                        <div class="widget-param">
                                            <small>{{ $key }}</small>
                                            <strong>{{ $value }}</strong>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted mb-0">{{ __('ui.dashboard.params_not_required') }}</p>
                            @endif

                            <div class="mt-auto pt-3">
                                <button type="button"
                                    class="btn btn-primary w-100 js-open-widget-modal"
                                    data-widget-id="{{ $widget->id }}"
                                    data-widget-name="{{ $widget->name }}"
                                    data-widget-params='@json($commands)'>
                                    {{ __('ui.dashboard.add_to_dashboard') }}
                                </button>
                            </div>
                        </div>
                    </article>
                @endforeach
            </section>
        @endif
    </div>

    <div class="modal fade" id="addWidgetModal" tabindex="-1" aria-labelledby="addWidgetModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form id="widgetAddForm" action="{{ route('dashboard.store') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addWidgetModalLabel">{{ __('ui.dashboard.submit_add_widget') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="modal_widget_name" class="form-label">{{ __('ui.dashboard.display_name') }}</label>
                            <input type="text" id="modal_widget_name" name="name" class="form-control @error('name') is-invalid @enderror" required value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="modal_device_id" class="form-label">{{ __('ui.dashboard.device') }}</label>
                            <select id="modal_device_id" name="device_id" class="form-select @error('device_id') is-invalid @enderror" required>
                                <option value="">{{ __('ui.common.select_device') }}</option>
                                @foreach ($devices as $device)
                                    <option
                                        data-commands="{{ json_encode($device->command) }}"
                                        value="{{ $device->id }}"
                                        @selected((string) old('device_id') === (string) $device->id)
                                    >
                                        {{ $device->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('device_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="modal_command" class="form-label">{{ __('ui.dashboard.command_for_device') }}</label>
                            <select id="modal_command" name="command" class="form-select @error('command') is-invalid @enderror" required>
                                <option value="">{{ __('ui.common.select_command') }}</option>
                            </select>
                            @error('command')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="modal_argument" class="form-label">{{ __('ui.dashboard.command_argument') }}</label>
                            <input type="text" id="modal_argument" name="argument" class="form-control @error('argument') is-invalid @enderror" value="{{ old('argument') }}">
                            @error('argument')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="modal_key" class="form-label">{{ __('ui.dashboard.data_key') }}</label>
                            <input type="text" id="modal_key" name="key" class="form-control @error('key') is-invalid @enderror" required value="{{ old('key') }}">
                            @error('key')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="modal_dynamic_fields"></div>

                        <input type="hidden" id="modal_values" name="values">
                        <input type="hidden" id="modal_widget_id" name="widget_id" value="{{ old('widget_id') }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">{{ __('ui.common.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('ui.dashboard.submit_add_widget') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @php
        $widgetMeta = $widgets->mapWithKeys(function ($widget) {
            return [
                $widget->id => [
                    'name' => $widget->name,
                    'params' => is_array($widget->input_params) ? $widget->input_params : (json_decode($widget->input_params, true) ?? []),
                ],
            ];
        });
    @endphp

    <script>
        function applyAccessKey() {
            const accessInput = document.getElementById('accessKey');
            const value = (accessInput.value || '').trim();

            if (!value) {
                window.location.replace('{{ route('dashboard.widget') }}');
                return;
            }

            window.location.replace(`{{ route('dashboard.widget') }}/${encodeURIComponent(value)}`);
        }

        document.addEventListener('DOMContentLoaded', function () {
            const modalEl = document.getElementById('addWidgetModal');
            if (modalEl && modalEl.parentElement !== document.body) {
                document.body.appendChild(modalEl);
            }
            const form = document.getElementById('widgetAddForm');
            const modalTitle = document.getElementById('addWidgetModalLabel');
            const modalWidgetName = document.getElementById('modal_widget_name');
            const modalWidgetId = document.getElementById('modal_widget_id');
            const modalDeviceId = document.getElementById('modal_device_id');
            const modalCommand = document.getElementById('modal_command');
            const modalValues = document.getElementById('modal_values');
            const dynamicFields = document.getElementById('modal_dynamic_fields');
            const openButtons = document.querySelectorAll('.js-open-widget-modal');
            const widgetModal = new bootstrap.Modal(modalEl);
            const widgetMeta = @json($widgetMeta);
            const oldValues = @json(json_decode(old('values', '{}'), true) ?? []);
            const oldWidgetId = @json(old('widget_id'));
            const oldCommand = @json(old('command'));

            function parseCommands(rawCommands) {
                if (!rawCommands) {
                    return [];
                }

                try {
                    const firstParse = JSON.parse(rawCommands);
                    return Array.isArray(firstParse) ? firstParse : JSON.parse(firstParse);
                } catch (e) {
                    return [];
                }
            }

            function refreshMainCommands(selectedValue = null) {
                const selectedModule = modalDeviceId.options[modalDeviceId.selectedIndex];
                const commandArray = parseCommands(selectedModule ? selectedModule.getAttribute('data-commands') : null);
                modalCommand.innerHTML = `<option value="">{{ __('ui.common.select_command') }}</option>`;

                commandArray.forEach(function (command) {
                    const option = document.createElement('option');
                    option.value = command;
                    option.textContent = command;
                    if (selectedValue !== null && String(command) === String(selectedValue)) {
                        option.selected = true;
                    }
                    modalCommand.appendChild(option);
                });
            }

            function buildDynamicCommandSelect(name, selectedValue = null) {
                const wrapper = document.createElement('div');
                wrapper.className = 'widget-modal-block';
                wrapper.innerHTML = `
                    <label class="form-label" for="dyn_${name}">{{ __('ui.dashboard.command_for', ['name' => '__NAME__']) }}</label>
                    <select id="dyn_${name}" name="${name}" class="form-select" data-dynamic="1" required>
                        <option value="">{{ __('ui.common.select_command') }}</option>
                    </select>
                    <label class="form-label mt-2" for="dyn_arg_${name}">{{ __('ui.dashboard.arg_for', ['name' => '__NAME__']) }}</label>
                    <input id="dyn_arg_${name}" name="arg_${name}" type="text" class="form-control" data-dynamic="1">
                `.replaceAll('__NAME__', name);

                dynamicFields.appendChild(wrapper);

                const select = wrapper.querySelector(`select[name="${name}"]`);
                const arg = wrapper.querySelector(`input[name="arg_${name}"]`);

                const selectedModule = modalDeviceId.options[modalDeviceId.selectedIndex];
                const commandArray = parseCommands(selectedModule ? selectedModule.getAttribute('data-commands') : null);
                commandArray.forEach(function (command) {
                    const option = document.createElement('option');
                    option.value = command;
                    option.textContent = command;
                    if (selectedValue !== null && String(command) === String(selectedValue)) {
                        option.selected = true;
                    }
                    select.appendChild(option);
                });

                if (oldValues[`arg_${name}`]) {
                    arg.value = oldValues[`arg_${name}`];
                }
            }

            function buildDynamicTextInput(name, selectedValue = '') {
                const wrapper = document.createElement('div');
                wrapper.className = 'widget-modal-block';
                wrapper.innerHTML = `
                    <label class="form-label" for="dyn_text_${name}">${name}</label>
                    <input id="dyn_text_${name}" name="${name}" type="text" class="form-control" data-dynamic="1" required>
                `;
                dynamicFields.appendChild(wrapper);
                wrapper.querySelector(`input[name="${name}"]`).value = selectedValue || '';
            }

            function renderDynamicFields(params) {
                dynamicFields.innerHTML = '';
                Object.entries(params || {}).forEach(function ([key, value]) {
                    if (value === 'command') {
                        buildDynamicCommandSelect(key, oldValues[key] ?? null);
                        return;
                    }
                    if (value === 'text') {
                        buildDynamicTextInput(key, oldValues[key] ?? '');
                    }
                });
            }

            function refreshAllDynamicCommandOptions() {
                const selectedModule = modalDeviceId.options[modalDeviceId.selectedIndex];
                const commandArray = parseCommands(selectedModule ? selectedModule.getAttribute('data-commands') : null);
                const selects = dynamicFields.querySelectorAll('select[data-dynamic="1"]');

                selects.forEach(function (select) {
                    const current = select.value;
                    select.innerHTML = `<option value="">{{ __('ui.common.select_command') }}</option>`;
                    commandArray.forEach(function (command) {
                        const option = document.createElement('option');
                        option.value = command;
                        option.textContent = command;
                        if (String(command) === String(current)) {
                            option.selected = true;
                        }
                        select.appendChild(option);
                    });
                });
            }

            function openModalFromWidget(widgetId, widgetName, params, keepOldName = false) {
                modalTitle.textContent = `{{ __('ui.dashboard.add_title', ['name' => '__WIDGET__']) }}`.replace('__WIDGET__', widgetName);
                modalWidgetId.value = widgetId;
                if (!keepOldName) {
                    modalWidgetName.value = widgetName;
                }
                renderDynamicFields(params);
                refreshMainCommands(oldCommand ?? null);
                widgetModal.show();
            }

            openButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    const widgetId = button.getAttribute('data-widget-id');
                    const widgetName = button.getAttribute('data-widget-name') || '';
                    const params = JSON.parse(button.getAttribute('data-widget-params') || '{}');
                    openModalFromWidget(widgetId, widgetName, params);
                });
            });

            modalDeviceId.addEventListener('change', function () {
                refreshMainCommands();
                refreshAllDynamicCommandOptions();
            });

            form.addEventListener('submit', function () {
                const payload = {};
                dynamicFields.querySelectorAll('input[data-dynamic="1"], select[data-dynamic="1"]').forEach(function (field) {
                    if (field.name && field.value !== '') {
                        payload[field.name] = field.value;
                    }
                });
                modalValues.value = JSON.stringify(payload);
            });

            @if ($errors->any() && old('widget_id'))
                (function () {
                    const oldId = String(oldWidgetId);
                    const item = widgetMeta[oldId] || null;
                    if (item) {
                        openModalFromWidget(oldId, item.name || '', item.params || {}, true);
                    }
                })();
            @endif
        });
    </script>
@endsection
