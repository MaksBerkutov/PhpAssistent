@extends('layouts.menu')
@section('title', 'Добавление виджета')

@section('styles')
    <style>
        .widget-add-form {
            max-width: 860px;
        }

        .widget-dynamic-block {
            border: 1px solid var(--line);
            border-radius: 12px;
            background: color-mix(in srgb, var(--surface-muted) 90%, transparent);
            padding: 12px;
            margin-bottom: 12px;
        }
    </style>
@endsection

@section('content')
    @php
        $commands = json_decode($widget->input_params, true) ?? [];
    @endphp

    <div class="page-shell">
        <section class="page-head">
            <div>
                <h2 class="page-title">Добавление виджета: {{ $widget->name }}</h2>
                <p class="page-subtitle">Заполните параметры и привяжите виджет к устройству.</p>
            </div>
            <a href="{{ route('dashboard.widget') }}" class="btn btn-outline-primary">Назад к каталогу</a>
        </section>

        <section class="page-card widget-add-form">
            <form id="widgetAddForm" action="{{ route('dashboard.store') }}" method="post">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Название виджета на панели</label>
                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" required value="{{ old('name') }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <x-device-choose name="device_id" :devices="$devices" label="Устройство" />

                <x-device-cmd-choose name="command" deviceChoseName="device_id" label="Команда для устройства" />

                <x-device-arg-cmd-choose name="argument" label="Аргумент команды" />

                <div class="mb-3">
                    <label for="key" class="form-label">Ключ данных</label>
                    <input type="text" id="key" name="key" class="form-control @error('key') is-invalid @enderror" required value="{{ old('key') }}">
                    @error('key')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div id="customFields">
                    @foreach($commands as $key => $value)
                        @if($value === 'command')
                            <div class="widget-dynamic-block">
                                <x-device-cmd-choose name="{{ $key }}" deviceChoseName="device_id" label="Команда для {{ $key }}" />
                                <x-device-arg-cmd-choose name="arg_{{ $key }}" label="Аргумент для {{ $key }}" />
                            </div>
                        @elseif($value === 'text')
                            <div class="widget-dynamic-block">
                                <label for="{{ $key }}" class="form-label">{{ $key }}</label>
                                <input type="text" id="{{ $key }}" name="{{ $key }}" class="form-control @error($key) is-invalid @enderror" required value="{{ old($key) }}">
                                @error($key)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                    @endforeach
                </div>

                <input type="hidden" id="values" name="values">
                <input type="hidden" name="widget_id" value="{{ $widget->id }}">

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary">Добавить виджет</button>
                    <a href="{{ route('dashboard.widget') }}" class="btn btn-outline-primary">Отмена</a>
                </div>
            </form>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('widgetAddForm');
            const valuesInput = document.getElementById('values');
            const customFields = document.getElementById('customFields');

            if (!form || !valuesInput || !customFields) {
                return;
            }

            form.addEventListener('submit', function () {
                const formData = {};
                const inputs = customFields.querySelectorAll('input, select');

                inputs.forEach(function (input) {
                    if (input.name && input.value !== '') {
                        formData[input.name] = input.value;
                    }
                });

                valuesInput.value = JSON.stringify(formData);
            });
        });
    </script>
@endsection
