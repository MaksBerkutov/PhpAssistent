@extends('layouts.menu')
@section('title', 'Сценарии')

@section('styles')
    <style>
        .scenario-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 12px;
        }

        .scenario-card {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .scenario-section {
            border: 1px solid var(--line);
            border-radius: 12px;
            background: color-mix(in srgb, var(--surface-muted) 92%, transparent);
            padding: 10px 12px;
            margin-bottom: 10px;
        }

        .scenario-section h6 {
            margin: 0 0 8px;
            font-size: 0.88rem;
            color: var(--ink);
        }

        .scenario-section p {
            margin: 0 0 4px;
            font-size: 0.86rem;
            color: var(--ink-body);
        }

        .scenario-section p:last-child {
            margin-bottom: 0;
        }

        @media (max-width: 640px) {
            .scenario-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-shell">
        <section class="page-head">
            <div>
                <h2 class="page-title">Сценарии автоматизации</h2>
                <p class="page-subtitle">Настраивайте реакции системы на события устройств.</p>
            </div>
            <a href="{{ route('scenario.create') }}" class="btn btn-primary">Создать сценарий</a>
        </section>

        @if($scenarios->isEmpty())
            <section class="page-empty">
                <p class="mb-2">Сценарии ещё не созданы.</p>
                <a href="{{ route('scenario.create') }}" class="btn btn-outline-primary">Добавить первый сценарий</a>
            </section>
        @else
            <section class="scenario-grid">
                @foreach($scenarios as $scenario)
                    <article class="card scenario-card">
                        <div class="card-header d-flex justify-content-between align-items-center gap-2">
                            <strong>Сценарий #{{ $scenario->id }}</strong>
                            <span class="chip">{{ optional($scenario->device)->name ?? 'Неизвестное устройство' }}</span>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <div class="kv-grid mb-3">
                                <div class="kv-item">
                                    <small>Ключ</small>
                                    <strong>{{ $scenario->key }}</strong>
                                </div>
                                <div class="kv-item">
                                    <small>Значение</small>
                                    <strong>{{ $scenario->value }}</strong>
                                </div>
                            </div>

                            @if($scenario->scenarioLog)
                                <div class="scenario-section">
                                    <h6>Логирование</h6>
                                    <p><strong>Формат:</strong> {{ $scenario->scenarioLog->format }}</p>
                                </div>
                            @endif

                            @if($scenario->scenarioApi)
                                <div class="scenario-section">
                                    <h6>Внешний API</h6>
                                    <p><strong>URL:</strong> {{ $scenario->scenarioApi->url }}</p>
                                    <p><strong>Payload:</strong> {{ $scenario->scenarioApi->format }}</p>
                                </div>
                            @endif

                            @if($scenario->ScenarioDb)
                                <div class="scenario-section">
                                    <h6>База данных</h6>
                                    <p><strong>Логин:</strong> {{ $scenario->ScenarioDb->login }}</p>
                                    <p><strong>База:</strong> {{ $scenario->ScenarioDb->db_name }}</p>
                                    <p><strong>Таблица:</strong> {{ $scenario->ScenarioDb->table_name }}</p>
                                    <p><strong>Ключ / Значение:</strong> {{ $scenario->ScenarioDb->name_key }} / {{ $scenario->ScenarioDb->name_value }}</p>
                                </div>
                            @endif

                            @if($scenario->scenarioNotify)
                                <div class="scenario-section">
                                    <h6>Уведомление</h6>
                                    <p><strong>Тип:</strong> {{ $scenario->scenarioNotify->type ?: 'Не указан' }}</p>
                                    <p><strong>Текст:</strong> {{ $scenario->scenarioNotify->format ?: 'Не указан' }}</p>
                                </div>
                            @endif

                            @if($scenario->scenarioModule)
                                <div class="scenario-section">
                                    <h6>Изменение состояния</h6>
                                    <p><strong>Устройство:</strong> {{ optional($scenario->scenarioModule->device)->name ?? 'Неизвестное устройство' }}</p>
                                    <p><strong>Команда:</strong> {{ $scenario->scenarioModule->command }}</p>
                                    <p><strong>Аргумент:</strong> {{ $scenario->scenarioModule->arg ?: '—' }}</p>
                                </div>
                            @endif

                            <div class="mt-auto d-flex flex-wrap gap-2 pt-2">
                                <a href="{{ route('scenario.edit', $scenario->id) }}" class="btn btn-outline-primary">Редактировать</a>
                                <form action="{{ route('scenario.delete', $scenario->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Удалить сценарий #{{ $scenario->id }}?')">Удалить</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </section>
        @endif
    </div>
@endsection
