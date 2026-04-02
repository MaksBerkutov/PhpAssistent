@extends('layouts.menu')
@section('title', 'Устройства')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/deactive.css') }}">
    <style>
        .device-list {
            display: grid;
            gap: 12px;
        }

        .device-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            flex-wrap: wrap;
        }

        .device-commands {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 8px;
            margin-bottom: 12px;
        }

        .device-commands .btn {
            width: 100%;
        }

        .device-extra-actions {
            display: grid;
            gap: 10px;
        }

        @media (max-width: 640px) {
            .device-commands {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-shell">
        <section class="page-head">
            <div>
                <h2 class="page-title">Устройства</h2>
                <p class="page-subtitle">Отправляйте команды, обновляйте прошивку и открывайте конфигурацию устройств.</p>
            </div>
            <a href="{{ route('devices.create') }}" class="btn btn-primary">Добавить устройство</a>
        </section>

        @if($devices->isEmpty())
            <section class="page-empty">
                <p class="mb-2">Устройства ещё не добавлены.</p>
                <a href="{{ route('devices.create') }}" class="btn btn-outline-primary">Создать первое устройство</a>
            </section>
        @else
            <section class="device-list">
                @foreach($devices as $device)
                    @php
                        $commands = json_decode($device->command, true) ?? [];
                        $alternate = true;
                    @endphp

                    <article class="card @if(!$device->available) deactivated @endif">
                        <div class="card-header">
                            <div class="device-header">
                                <h5 class="mb-0">{{ $device->name }}</h5>
                                <x-online-status status="{{ $device->available }}"/>
                            </div>
                        </div>

                        <div class="card-body">
                            <form method="post" action="{{ route('devices.send') }}" class="mb-3">
                                @csrf
                                <input type="hidden" name="url" value="{{ $device->url }}">

                                <div class="device-commands">
                                    @foreach($commands as $command)
                                        @if(str_ends_with($command, '_REC'))
                                            <button type="submit" name="command" value="{{ $command }}" class="btn btn-outline-success">{{ $command }}</button>
                                        @elseif($alternate)
                                            <button type="submit" name="command" value="{{ $command }}" class="btn btn-outline-primary">{{ $command }}</button>
                                        @else
                                            <button type="submit" name="command" value="{{ $command }}" class="btn btn-outline-secondary">{{ $command }}</button>
                                        @endif
                                        @php($alternate = !$alternate)
                                    @endforeach
                                </div>

                                <div>
                                    <label for="arg-{{ $device->id }}" class="form-label">Аргумент (необязательно)</label>
                                    <input type="text" class="form-control @error('arg') is-invalid @enderror" id="arg-{{ $device->id }}" name="arg" value="{{ $device->url == old('url') ? old('arg') : '' }}">
                                    @error('arg')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </form>

                            <div class="device-extra-actions">
                                @if($device->ota)
                                    <form method="post" action="{{ route('devices.firmware') }}" enctype="multipart/form-data" class="page-card p-3">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $device->id }}">
                                        <label for="firmware-{{ $device->id }}" class="form-label mb-1">Обновление прошивки (.bin)</label>
                                        <input id="firmware-{{ $device->id }}" type="file" name="firmware" accept=".bin" class="form-control mb-2">
                                        <button type="submit" class="btn btn-outline-warning">Обновить прошивку</button>
                                        @error('id')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                        @error('firmware')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </form>
                                @endif

                                @if($device->configuration)
                                    <form method="get" action="{{ route('devices.configure') }}">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $device->id }}">
                                        <button type="submit" class="btn btn-outline-primary">Открыть конфигурацию</button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <div class="card-footer text-muted">
                            {{ $device->name_board }}
                        </div>
                    </article>
                @endforeach
            </section>
        @endif
    </div>
@endsection
