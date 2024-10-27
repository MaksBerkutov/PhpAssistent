@extends('layouts.menu')
@section('title', 'Edit Arduino Scenario')
@php
    $reference = [
        'scenario_logs_id' => 'log',
        'scenario_dbs_id' => 'save_db',
        'scenario_notifies_id' => 'notify',
        'scenario_modules_id' => 'change_state',
        'scenario_apis_id' => 'send_api',
    ];
    $selcetedItems = [];
    foreach ($reference as $key => $value) {
        if ($scenario[$key] != null) {
            $selcetedItems[] = $value;
        }
    }

@endphp
@section('content')
    <div class="container mt-5">
        <h2>Редактирование сценария</h2>

        <form action="{{ route('scenario.update', $scenario->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="module" class="form-label">Модуль</label>
                <select id="module" name="devices_id" class="form-select @error('devices_id') is-invalid @enderror"
                    required>
                    <option value="">Выберите модуль</option>
                    @foreach ($devices as $device)
                        <option value="{{ $device->id }}"
                            {{ old('devices_id', $scenario->devices_id) == $device->id ? 'selected' : '' }}>
                            {{ $device->name }}</option>
                    @endforeach
                </select>
                @error('devices_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="key" class="form-label">Ключ</label>
                <input type="text" id="key" name="key" class="form-control @error('key') is-invalid @enderror"
                    required value="{{ old('key', $scenario->key) }}">
                @error('key')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="value" class="form-label">Значение</label>
                <input type="text" id="value" name="value"
                    class="form-control @error('value') is-invalid @enderror" required
                    value="{{ old('value', $scenario->value) }}">
                @error('value')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="actions" class="form-label">Выберите действие(я)</label>
                <select id="actions" name="actions[]" class="form-select @error('actions') is-invalid @enderror" multiple
                    required>
                    <option value="log" {{ in_array('log', old('actions', $selcetedItems)) ? 'selected' : '' }}>
                        Логировать данные</option>
                    <option value="save_db" {{ in_array('save_db', old('actions', $selcetedItems)) ? 'selected' : '' }}>
                        Записать в базу данных</option>
                    <option value="notify" {{ in_array('notify', old('actions', $selcetedItems)) ? 'selected' : '' }}>
                        Отправить уведомление</option>
                    <option value="change_state"
                        {{ in_array('change_state', old('actions', $selcetedItems)) ? 'selected' : '' }}>Изменить состояние
                        модуля</option>
                    <option value="send_api" {{ in_array('send_api', old('actions', $selcetedItems)) ? 'selected' : '' }}>
                        Отправить на внешний API</option>
                </select>
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

            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const actionSelect = document.getElementById('actions');

            actionSelect.addEventListener('change', Change);
            Change()
            ChangeCommand();
        });

        function Change() {
            const actionSelect = document.getElementById('actions');
            const logCard = document.getElementById('log-card');
            const dbCard = document.getElementById('db-card');
            const notifyCard = document.getElementById('notify-card');
            const stateCard = document.getElementById('state-card');
            const apiCard = document.getElementById('api-card');
            const selectedOptions = Array.from(actionSelect.selectedOptions).map(option => option.value);

            logCard.classList.toggle('d-none', !selectedOptions.includes('log'));
            dbCard.classList.toggle('d-none', !selectedOptions.includes('save_db'));
            notifyCard.classList.toggle('d-none', !selectedOptions.includes('notify'));
            stateCard.classList.toggle('d-none', !selectedOptions.includes('change_state'));
            apiCard.classList.toggle('d-none', !selectedOptions.includes('send_api'));
        }
    </script>
@endsection
