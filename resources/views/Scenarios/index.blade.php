<!doctype html>
@extends('layouts.menu')
@section('title','Arduino Scenario')

@section('content')
    <div class="container">
        <h1 class="mb-4">Сценарії</h1>

        <div class="row">
            @foreach($scenarios as $scenario)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            Сценарій #{{ $scenario->id }}
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Пристрій: {{ optional($scenario->device)->name ?? 'Невідомий пристрій' }}</h5>
                            <p class="card-text">Ключ: {{ $scenario->key }}</p>
                            <p class="card-text">Значення: {{ $scenario->value }}</p>
                            <p class="card-text">Лог: {{ optional($scenario->scenarioLog)->format?? 'Не обрано' }}</p>
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
                                <p class="card-text">API: Не обрано</p>
                            @endif
                            @if($scenario->scenarioDb)
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">Database</h5>
                                        <p><strong>Логін:</strong> {{ $scenario->scenarioDb->login }}</p>
                                        <p><strong>Пароль:</strong> {{ $scenario->scenarioDb->password }}</p>
                                        <p><strong>Ім'я бази:</strong> {{ $scenario->scenarioDb->db_name }}</p>
                                        <p><strong>Ім'я таблиці:</strong> {{ $scenario->scenarioDb->table_name }}</p>
                                        <p><strong>Ключ:</strong> {{ $scenario->scenarioDb->name_key }}</p>
                                        <p><strong>Значення:</strong> {{ $scenario->scenarioDb->name_value }}</p>
                                    </div>
                                </div>
                            @else
                                <p class="card-text">Database: Не обрано</p>

                            @endif
                            @if($scenario->scenarioNotify)
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">Повідомлення</h5>
                                        <p><strong>Формат:</strong> {{ optional($scenario->scenarioNotify)->format ?? 'Невідомий формат'  }}</p>
                                        <p><strong>Тип:</strong> {{ optional($scenario->scenarioNotify)->type?? 'Невідомий комманда' }}</p>
                                    </div>
                                </div>
                            @else
                                <p class="card-text">Змінити стан модуля: Не обрано</p>
                            @endif
                            @if($scenario->scenarioModule)
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">Змінити стан модуля</h5>
                                        <p><strong>Модуль:</strong> {{ optional($scenario->scenarioModule->device)->name ?? 'Невідомий пристрій'  }}</p>
                                        <p><strong>Команда:</strong> {{ $scenario->scenarioModule->command }}</p>
                                        <p><strong>Аргумент:</strong> {{ $scenario->scenarioModule->arg }}</p>
                                    </div>
                                </div>
                            @else
                                <p class="card-text">Змінити стан модуля: Не обрано</p>
                            @endif
                        </div>

                        <div class="card-footer text-end">
                            <a href="{{ route('scenario.edit', $scenario->id) }}" class="btn btn-primary">Редагувати</a>
                            <form action="{{ route('scenario.delete', $scenario->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Видалити</button>
                            </form>
                        </div>
                    </div>
                </div>




            @endforeach
        </div>
    </div>

@endsection

