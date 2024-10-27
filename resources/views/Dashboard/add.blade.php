@extends('layouts.menu')
@section('title','Add Widget Dashboard')

@section('content')
    <div class="container">
        <form id="myForm" action="{{route('dashboard.store')}}" method="post">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Имя</label>
                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" required value="{{ old('name') }}">
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="module" class="form-label">Модуль</label>
                <select id="module" name="device_id" class="form-select @error('device_id') is-invalid @enderror" required>
                    <option value="">Выберите модуль</option>
                    @foreach($devices as $device)
                        <option data-commands="{{ json_encode($device->command) }}" value="{{ $device->id }}" {{ old('device_id') == $device->id ? 'selected' : '' }}>{{ $device->name }}</option>
                    @endforeach
                </select>
                @error('device_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="command" class="form-label">Команда для модуля</label>
                <select id="command" name="command" class="form-select @error('command') is-invalid @enderror">
                    <option value="">Выберите команду</option>
                </select>
                @error('command')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="key" class="form-label">Ключ</label>
                <input type="text" id="key" name="key" class="form-control @error('key') is-invalid @enderror" required value="{{ old('key') }}">
                @error('key')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            @php
                $commands = json_decode($widget->input_params,true );
                $arrayCommandID = [];
            @endphp
            <div id="customFields">
                @foreach($commands as $key => $value)
                    @if($value=='command')
                        @php($arrayCommandID[]=$key)
                        <div class="mb-3">
                            <label for="{{$key}}" class="form-label">Команда для {{$key}}</label>
                            <select id="{{$key}}" name="{{$key}}" class="form-select @error($key) is-invalid @enderror">
                                <option value="">Выберите команду</option>
                            </select>
                            @error($key)
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @elseif($value=='text')
                        <div class="mb-3">
                            <label for="{{$key}}" class="form-label">{{$key}}</label>
                            <input type="text" id="{{$key}}" name="{{$key}}" class="form-control @error($key) is-invalid @enderror" required value="{{ old($key) }}">
                            @error($key)
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif
                @endforeach
            </div>
            <input type="hidden" id="values" name="values">
            <input type="hidden" name="widget_id" value="{{$widget->id}}">

            <button type="submit" class="btn btn-primary">Добавить виджет</button>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('myForm').addEventListener('submit', function(event) {
                let formData = {};
                let inputs = document.getElementById('customFields').querySelectorAll('input, select');

                inputs.forEach(function(input) {
                    if (input.name && input.value) {
                        formData[input.name] = input.value;
                    }
                });

                document.getElementById('values').value = JSON.stringify(formData);
            });
            document.getElementById('module').addEventListener('change', function (){
                ChangeCommand('command');
                @foreach($arrayCommandID as $command)
                ChangeCommand('{{$command}}');
                @endforeach
            });

            ChangeCommand('command');
            function ChangeCommand(name) {

                const module = document.getElementById('module');

                const commandSelect = document.getElementById(name);
                console.log(commandSelect)
                commandSelect.innerHTML = '<option value="">Выберите команду</option>';

                const selectedModule = module.options[module.selectedIndex];
                console.log(selectedModule)
                const commands = selectedModule.getAttribute('data-commands');
                if (commands) {
                    const commandArray = JSON.parse(JSON.parse(commands));
                    //console.log(typeof commandArray)

                    commandArray.forEach(command => {
                        const option = document.createElement('option');
                        option.value = command;
                        option.textContent = command;
                        if(command == '{{ old('change_command')}}')
                            option.selected = true;

                        commandSelect.appendChild(option);
                    });
                }

        }});
    </script>
@endsection

