@extends('layouts.menu')
@section('title', __('ui.scenarios.create_scenario'))

@section('styles')
    <style>
        .scenario-form {
            max-width: 900px;
        }

        .scenario-action-note {
            color: var(--ink-soft);
            font-size: 0.82rem;
            margin-top: 6px;
        }
    </style>
@endsection

@section('content')
    <div class="page-shell">
        <section class="page-head">
            <div>
                <h2 class="page-title">{{ __('ui.scenarios.create_title') }}</h2>
                <p class="page-subtitle">{{ __('ui.scenarios.create_subtitle') }}</p>
            </div>
            <a href="{{ route('scenario') }}" class="btn btn-outline-primary">{{ __('ui.scenarios.back_to_list') }}</a>
        </section>

        <section class="page-card scenario-form">
            <form action="{{ route('scenario.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="module" class="form-label">{{ __('ui.scenarios.source_device') }}</label>
                    <select id="module" name="devices_id" class="form-select @error('devices_id') is-invalid @enderror" required>
                        <option value="">{{ __('ui.common.select_device') }}</option>
                        @foreach ($devices as $device)
                            <option value="{{ $device->id }}" {{ old('devices_id') == $device->id ? 'selected' : '' }}>
                                {{ $device->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('devices_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <x-default-form-input type="text" name="key" :placeholder="__('ui.scenarios.trigger_key_placeholder')" :text="__('ui.scenarios.trigger_key')" />
                <x-default-form-input type="text" name="value" :placeholder="__('ui.scenarios.trigger_value_placeholder')" :text="__('ui.scenarios.trigger_value')" />

                <div class="mb-3">
                    <label for="actions" class="form-label">{{ __('ui.scenarios.actions') }}</label>
                    <select id="actions" name="actions[]" class="form-select @error('actions') is-invalid @enderror" multiple required>
                        <option value="log" {{ in_array('log', old('actions', [])) ? 'selected' : '' }}>{{ __('ui.scenarios.action_log') }}</option>
                        <option value="save_db" {{ in_array('save_db', old('actions', [])) ? 'selected' : '' }}>{{ __('ui.scenarios.action_save_db') }}</option>
                        <option value="notify" {{ in_array('notify', old('actions', [])) ? 'selected' : '' }}>{{ __('ui.scenarios.action_notify') }}</option>
                        <option value="change_state" {{ in_array('change_state', old('actions', [])) ? 'selected' : '' }}>{{ __('ui.scenarios.action_change_state') }}</option>
                        <option value="send_api" {{ in_array('send_api', old('actions', [])) ? 'selected' : '' }}>{{ __('ui.scenarios.action_send_api') }}</option>
                    </select>
                    <div class="scenario-action-note">{{ __('ui.scenarios.action_note') }}</div>
                    @error('actions')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div id="action-settings" class="mt-4">
                    @include('Scenarios.Cards.log')
                    @include('Scenarios.Cards.db')
                    @include('Scenarios.Cards.notify')
                    @include('Scenarios.Cards.module')
                    @include('Scenarios.Cards.api')
                </div>

                <div class="d-flex flex-wrap gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">{{ __('ui.scenarios.submit_create') }}</button>
                    <a href="{{ route('scenario') }}" class="btn btn-outline-primary">{{ __('ui.common.cancel') }}</a>
                </div>
            </form>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const actionSelect = document.getElementById('actions');

            if (!actionSelect) {
                return;
            }

            actionSelect.addEventListener('change', toggleActionCards);
            toggleActionCards();
        });

        function toggleActionCards() {
            const actionSelect = document.getElementById('actions');
            const selectedOptions = Array.from(actionSelect.selectedOptions).map(option => option.value);

            const cardMap = {
                log: document.getElementById('log-card'),
                save_db: document.getElementById('db-card'),
                notify: document.getElementById('notify-card'),
                change_state: document.getElementById('state-card'),
                send_api: document.getElementById('api-card')
            };

            Object.keys(cardMap).forEach(function (key) {
                const card = cardMap[key];
                if (!card) {
                    return;
                }
                card.classList.toggle('d-none', !selectedOptions.includes(key));
            });
        }
    </script>
@endsection
