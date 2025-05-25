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
                <x-device-choose name="device_id" :devices="$devices"
                                 label="Виберіть модуль"/>
            </div>
            <div class="mb-3">
                <x-device-cmd-choose name="command" deviceChoseName="device_id"
                                     label="Команда для модуля"/>
            </div>
            <div class="mb-3">

             <x-device-arg-cmd-choose name="argument"
                                     label="Аргумент для модуля"/>
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
                            <x-device-cmd-choose name="{{$key}}" deviceChoseName="device_id"
                                                 label="Команда для {{$key}}"/>
                            <x-device-arg-cmd-choose name="arg_{{$key}}"
                                                     label="Аргумент для {{$key}}"/>
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

            <button type="submit" class="btn btn-primary">Додати віджет</button>
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


        });
    </script>
@endsection

