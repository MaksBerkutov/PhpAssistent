<!doctype html>
@extends('layouts.menu')
@section('title','Arduino Scenario')

@section('content')
    <div class="container">
        <h1 class="mb-4">Сценарии</h1>

        <div class="row">
            @foreach($scenarios as $scenario)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            Сценарий #{{ $scenario->id }}
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Устройство: {{ optional($scenario->device)->name ?? 'Неизвестное устройство' }}</h5>
                            <p class="card-text">Ключ: {{ $scenario->key }}</p>
                            <p class="card-text">Значение: {{ $scenario->value }}</p>
                            <p class="card-text">Лог: {{ optional($scenario->scenarioLog)->format?? 'Не выбранно' }}</p>
                            @if($scenario->scenarioApi)
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">API</h5>
                                        <p><strong>ID:</strong> {{ $scenario->scenarioApi->id }}</p>
                                        <p><strong>Формат:</strong> {{ $scenario->scenarioApi->format }}</p>
                                        <p><strong>Тип:</strong> {{ $scenario->scenarioApi->type }}</p>
                                    </div>
                                </div>
                            @else
                                <p class="card-text">API: Не выбранно</p>
                            @endif
                            @if($scenario->scenarioDb)
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">Database</h5>
                                        <p><strong>Логин:</strong> {{ $scenario->scenarioDb->login }}</p>
                                        <p><strong>Пароль:</strong> {{ $scenario->scenarioDb->password }}</p>
                                        <p><strong>Имя базы:</strong> {{ $scenario->scenarioDb->db_name }}</p>
                                        <p><strong>Имя таблицы:</strong> {{ $scenario->scenarioDb->table_name }}</p>
                                        <p><strong>Ключ:</strong> {{ $scenario->scenarioDb->name_key }}</p>
                                        <p><strong>Значение:</strong> {{ $scenario->scenarioDb->name_value }}</p>
                                    </div>
                                </div>
                            @else
                                <p class="card-text">Database: Не выбранно</p>

                            @endif
                            @if($scenario->scenarioNotify)
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">Уведомление</h5>
                                        <p><strong>Формат:</strong> {{ optional($scenario->scenarioNotify)->format ?? 'Неизвестный формат'  }}</p>
                                        <p><strong>Тип:</strong> {{ optional($scenario->scenarioNotify)->type?? 'Неизвестный комманда' }}</p>
                                    </div>
                                </div>
                            @else
                                <p class="card-text">Изменить состояние модуля: Не выбранно</p>
                            @endif
                            @if($scenario->scenarioModule)
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">Изменить состояние модуля</h5>
                                        <p><strong>Модуль:</strong> {{ optional($scenario->scenarioModule->device)->name ?? 'Неизвестное устройство'  }}</p>
                                        <p><strong>Команда:</strong> {{ $scenario->scenarioModule->command }}</p>
                                    </div>
                                </div>
                            @else
                                <p class="card-text">Изменить состояние модуля: Не выбранно</p>
                            @endif
                        </div>

                        <div class="card-footer text-end">
                            <a href="{{ route('scenario.edit', $scenario->id) }}" class="btn btn-primary">Редактировать</a>
                            <form action="{{ route('scenario.delete', $scenario->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Удалить</button>
                            </form>
                        </div>
                    </div>
                </div>




            @endforeach
        </div>
    </div>

@endsection

