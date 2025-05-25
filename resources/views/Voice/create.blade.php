<!doctype html>
@extends('layouts.menu')
@section('title','Arduino Create Voice ')
@section('styles')
    <script src="{{asset('js/voice.js')}}"></script>
@endsection
@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">Додавання команд</h2>

        <form action="{{route('voice.store')}}" method="post" >
            @csrf
            <div class="form-group">
                <label for="module">Виберіть модуль</label>
                <select id="module" name="devices_id" class="form-control @error('devices_id') is-invalid @enderror" required>
                    <option value="">Виберіть модуль</option>
                    @foreach($devices as $device)
                        <option value="{{ $device->id }}" data-commands="{{ json_encode($device->command) }}" {{ old('devices_id') == $device->id ? 'selected' : '' }}>{{ $device->name }}</option>
                    @endforeach
                </select>
                @error('devices_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="command">Виберіть команду для модуля</label>
                <select id="command" name="command" class="form-control @error('command') is-invalid @enderror" required>
                    <option value="">Виберіть команду</option>
                </select>
                @error('command')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <x-default-form-input type="text" name="text_trigger" placeholder="Приклад: Увімкни світло" text="Введіть команду"/>
            <x-default-form-input type="text" name="voice" placeholder="Приклад: Світло увімкнено" text="Текст повідомлення"/>
            <!-- Кнопка для генерации голосовой команды -->
            <button type="submit" class="btn btn-primary" id="generateVoiceCommand">Згенерувати голосову команду</button>
        </form>
    </div>

    <script>
        document.getElementById('module').addEventListener('change', function() {
            const commandSelect = document.getElementById('command');
            commandSelect.innerHTML = '<option value="">Виберіть команду</option>';

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

