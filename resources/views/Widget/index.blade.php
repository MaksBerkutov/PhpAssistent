@extends('layouts.menu')
@section('title', 'Управление виджетами')

@section('styles')
    <style>
        .widget-admin-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 12px;
        }

        .widget-admin-card {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .widget-admin-params {
            display: grid;
            gap: 8px;
            margin-top: 10px;
        }

        .widget-admin-param {
            border: 1px solid var(--line);
            border-radius: 10px;
            background: color-mix(in srgb, var(--surface-muted) 90%, transparent);
            padding: 8px 10px;
        }

        .widget-admin-param small {
            display: block;
            color: var(--ink-soft);
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 2px;
        }

        @media (max-width: 640px) {
            .widget-admin-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-shell">
        <section class="page-head">
            <div>
                <h2 class="page-title">Управление виджетами</h2>
                <p class="page-subtitle">Устанавливайте, редактируйте и удаляйте виджеты системы.</p>
            </div>
            <a href="{{ route('widget.create') }}" class="btn btn-primary">Создать виджет</a>
        </section>

        <section class="page-card">
            <form id="installForm" method="POST" action="{{ route('widgets.install') }}" enctype="multipart/form-data" class="row g-2 align-items-end">
                @csrf
                <div class="col-md-8">
                    <label for="widgetZip" class="form-label mb-1">ZIP архив виджета</label>
                    <input id="widgetZip" type="file" name="widget" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-success w-100">Установить / обновить</button>
                </div>
            </form>
        </section>

        @if($widgets->isEmpty())
            <section class="page-empty">
                <p class="mb-0">Пока нет установленных виджетов.</p>
            </section>
        @else
            <section class="widget-admin-grid">
                @foreach ($widgets as $widget)
                    @php
                        $commands = json_decode($widget->input_params, true) ?? [];
                    @endphp

                    <article class="card widget-admin-card">
                        <div class="card-header d-flex justify-content-between align-items-center gap-2">
                            <strong>{{ $widget->name }}</strong>
                            <span class="chip">{{ $widget->widget_name }}</span>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div class="kv-grid mb-3">
                                <div class="kv-item">
                                    <small>Ключ безопасности</small>
                                    <strong>{{ $widget->accesses_key ?: 'Не задан' }}</strong>
                                </div>
                                <div class="kv-item">
                                    <small>Версия</small>
                                    <strong>{{ $widget->version ?: '—' }}</strong>
                                </div>
                            </div>

                            @if(!empty($commands))
                                <div class="widget-admin-params">
                                    @foreach ($commands as $key => $value)
                                        <div class="widget-admin-param">
                                            <small>{{ $key }}</small>
                                            <strong>{{ $value }}</strong>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted mb-0">У виджета нет дополнительных параметров.</p>
                            @endif

                            <div class="mt-auto d-flex flex-wrap gap-2 pt-3">
                                <a href="{{ route('widget.edit', $widget->id) }}" class="btn btn-outline-primary">Редактировать</a>
                                <form action="{{ route('widget.delete', $widget->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Удалить виджет {{ $widget->name }}?')">Удалить</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </section>
        @endif
    </div>
@endsection
