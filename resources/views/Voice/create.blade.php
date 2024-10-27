<!doctype html>
@extends('layouts.menu')
@section('title','Arduino Create Voice ')
@section('styles')
    <script src="{{asset('js/voice.js')}}"></script>
@endsection
@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">Добавление Команд</h2>

        <form action="{{route('voice.store')}}" method="post" >
            @csrf
            <div class="form-group">
                <label for="module">Выберите модуль</label>
                <select id="module" name="devices_id" class="form-control @error('devices_id') is-invalid @enderror" required>
                    <option value="">Выберите модуль</option>
                    @foreach($devices as $device)
                        <option value="{{ $device->id }}" data-commands="{{ json_encode($device->command) }}" {{ old('devices_id') == $device->id ? 'selected' : '' }}>{{ $device->name }}</option>
                    @endforeach
                </select>
                @error('devices_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="command">Выберите команду для модуля</label>
                <select id="command" name="command" class="form-control @error('command') is-invalid @enderror" required>
                    <option value="">Выберите команду</option>
                </select>
                @error('command')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <x-default-form-input type="text" name="text_trigger" placeholder="Пример: Включи свет" text="Введите команду"/>
            <x-default-form-input type="text" name="voice" placeholder="Пример: Включи свет" text="Сообщение уведомления"/>
            <!-- Кнопка для генерации голосовой команды -->
            <button type="submit" class="btn btn-primary" id="generateVoiceCommand">Сгенерировать голосовую команду</button>
        </form>
    </div>

    <script>
        document.getElementById('module').addEventListener('change', function() {
            const commandSelect = document.getElementById('command');
            commandSelect.innerHTML = '<option value="">Выберите команду</option>';

            const selectedModule = this.options[this.selectedIndex];
            const commands = selectedModule.getAttribute('data-commands');

            if (commands) {
                const commandArray = JSON.parse(JSON.parse(commands));
                commandArray.forEach(command => {
                    const option = document.createElement('option');
                    option.value = command;
                    option.textContent = command;
                    commandSelect.appendChild(option);
                });
            }
        });


    </script>

@endsection

