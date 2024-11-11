@extends('layouts.menu')
@section('title', 'Arduino Scenario Сreate')

@section('content')
    <div class="container" >
        <form action="{{ route('scenario.store') }}" method="POST">
            @csrf
            <!-- Выбор модуля -->
            <div class="mb-3">
                <label for="module" class="form-label">Модуль</label>
                <select id="module" name="devices_id" class="form-select @error('devices_id') is-invalid @enderror"
                    required>
                    <option value="">Выберите модуль</option>
                    @foreach ($devices as $device)
                        <option value="{{ $device->id }}" {{ old('devices_id') == $device->id ? 'selected' : '' }}>
                            {{ $device->name }}</option>
                    @endforeach
                </select>
                @error('devices_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Поле для ввода ключа -->
            <x-default-form-input type="text" name="key" placeholder="Пример: State" text="Ключ" />

            <!-- Поле для ввода значения -->
            <x-default-form-input type="text" name="value" placeholder="Пример: open" text="Значение" />
            <!-- Выбор действий -->
            <div class="mb-3">
                <label for="actions" class="form-label">Выберите действие(я)</label>
                <select id="actions" name="actions[]" class="form-select @error('actions') is-invalid @enderror" multiple
                    required>
                    <option value="log" {{ in_array('log', old('actions', [])) ? 'selected' : '' }}>Логировать данные
                    </option>
                    <option value="save_db" {{ in_array('save_db', old('actions', [])) ? 'selected' : '' }}>Записать в базу
                        данных</option>
                    <option value="notify" {{ in_array('notify', old('actions', [])) ? 'selected' : '' }}>Отправить
                        уведомление</option>
                    <option value="change_state" {{ in_array('change_state', old('actions', [])) ? 'selected' : '' }}>
                        Изменить состояние модуля</option>
                    <option value="send_api" {{ in_array('send_api', old('actions', [])) ? 'selected' : '' }}>Отправить на
                        внешний API</option>
                </select>
                @error('actions')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <!-- Настройки действий (карточки) -->
            <div id="action-settings" class="mt-4">
                <!-- Карточка для логирования -->
                @include('Scenarios.Cards.log')

                <!-- Карточка для базы данных -->
                @include('Scenarios.Cards.db')

                <!-- Карточка для уведомлений -->
                @include('Scenarios.Cards.notify')

                <!-- Карточка для изменения состояния модуля -->
                @include('Scenarios.Cards.module')

                <!-- Карточка для отправки API -->
                @include('Scenarios.Cards.api')

            </div>

            <button type="submit" class="btn btn-primary">Создать сценарий</button>
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
