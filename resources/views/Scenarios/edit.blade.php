@extends('layouts.menu')
@section('title', 'Редактировать сценарий')

@php
    $reference = [
        'scenario_logs_id' => 'log',
        'scenario_dbs_id' => 'save_db',
        'scenario_notifies_id' => 'notify',
        'scenario_modules_id' => 'change_state',
        'scenario_apis_id' => 'send_api',
    ];

    $selectedItems = [];
    foreach ($reference as $key => $value) {
        if (!empty($scenario[$key])) {
            $selectedItems[] = $value;
        }
    }
@endphp

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
                <h2 class="page-title">Редактирование сценария #{{ $scenario->id }}</h2>
                <p class="page-subtitle">Обновите параметры триггера и действий.</p>
            </div>
            <a href="{{ route('scenario') }}" class="btn btn-outline-primary">К списку сценариев</a>
        </section>

        <section class="page-card scenario-form">
            <form action="{{ route('scenario.update', $scenario->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="module" class="form-label">Устройство-источник</label>
                    <select id="module" name="devices_id" class="form-select @error('devices_id') is-invalid @enderror" required>
                        <option value="">Выберите устройство</option>
                        @foreach ($devices as $device)
                            <option value="{{ $device->id }}" {{ old('devices_id', $scenario->devices_id) == $device->id ? 'selected' : '' }}>
                                {{ $device->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('devices_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="key" class="form-label">Ключ</label>
                    <input type="text" id="key" name="key" class="form-control @error('key') is-invalid @enderror" required value="{{ old('key', $scenario->key) }}">
                    @error('key')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="value" class="form-label">Значение</label>
                    <input type="text" id="value" name="value" class="form-control @error('value') is-invalid @enderror" required value="{{ old('value', $scenario->value) }}">
                    @error('value')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="actions" class="form-label">Действия</label>
                    <select id="actions" name="actions[]" class="form-select @error('actions') is-invalid @enderror" multiple required>
                        <option value="log" {{ in_array('log', old('actions', $selectedItems)) ? 'selected' : '' }}>Логирование</option>
                        <option value="save_db" {{ in_array('save_db', old('actions', $selectedItems)) ? 'selected' : '' }}>Сохранение в БД</option>
                        <option value="notify" {{ in_array('notify', old('actions', $selectedItems)) ? 'selected' : '' }}>Уведомление</option>
                        <option value="change_state" {{ in_array('change_state', old('actions', $selectedItems)) ? 'selected' : '' }}>Изменение состояния устройства</option>
                        <option value="send_api" {{ in_array('send_api', old('actions', $selectedItems)) ? 'selected' : '' }}>Отправка во внешний API</option>
                    </select>
                    <div class="scenario-action-note">Удерживайте Ctrl (или Cmd), чтобы выбрать несколько пунктов.</div>
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
                    <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                    <a href="{{ route('scenario') }}" class="btn btn-outline-primary">Отмена</a>
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
