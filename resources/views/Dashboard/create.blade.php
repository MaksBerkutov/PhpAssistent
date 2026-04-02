@extends('layouts.menu')
@section('title', 'Каталог виджетов')

@section('styles')
    <style>
        .widget-filter {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 10px;
            align-items: end;
        }

        .widget-card {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .widget-params {
            display: grid;
            gap: 8px;
            margin-top: 10px;
        }

        .widget-param {
            border: 1px solid var(--line);
            border-radius: 10px;
            background: color-mix(in srgb, var(--surface-muted) 90%, transparent);
            padding: 8px 10px;
        }

        .widget-param small {
            display: block;
            color: var(--ink-soft);
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 2px;
        }

        @media (max-width: 640px) {
            .widget-filter {
                grid-template-columns: 1fr;
            }

            .widget-filter .btn {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-shell">
        <section class="page-head">
            <div>
                <h2 class="page-title">Каталог виджетов</h2>
                <p class="page-subtitle">Выберите подходящий виджет и добавьте его на дашборд. Для приватных виджетов можно использовать ключ доступа.</p>
            </div>
        </section>

        <section class="page-card">
            <form id="accessForm" class="widget-filter">
                <div>
                    <label for="accessKey" class="form-label mb-1">Ключ доступа (необязательно)</label>
                    <input id="accessKey" type="text" class="form-control" placeholder="Введите ключ безопасности">
                </div>
                <button type="button" class="btn btn-outline-primary" onclick="applyAccessKey()">Применить ключ</button>
            </form>
        </section>

        @if($widgets->isEmpty())
            <section class="page-empty">
                <p class="mb-0">Виджеты не найдены для текущего ключа.</p>
            </section>
        @else
            <section class="page-grid">
                @foreach($widgets as $widget)
                    @php
                        $commands = json_decode($widget->input_params, true) ?? [];
                    @endphp
                    <article class="card widget-card">
                        <div class="card-header d-flex justify-content-between align-items-center gap-2">
                            <strong>{{ $widget->name }}</strong>
                            <span class="chip">{{ $widget->widget_name }}</span>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div class="kv-grid mb-3">
                                <div class="kv-item">
                                    <small>Ключ доступа</small>
                                    <strong>{{ $widget->accesses_key ?: 'Не требуется' }}</strong>
                                </div>
                            </div>

                            @if (!empty($commands))
                                <div class="widget-params">
                                    @foreach($commands as $key => $value)
                                        <div class="widget-param">
                                            <small>{{ $key }}</small>
                                            <strong>{{ $value }}</strong>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted mb-0">Параметры не требуются.</p>
                            @endif

                            <div class="mt-auto pt-3">
                                <a href="{{ route('dashboard.widget.add', $widget->id) }}" class="btn btn-primary w-100">Добавить на дашборд</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </section>
        @endif
    </div>

    <script>
        function applyAccessKey() {
            const accessInput = document.getElementById('accessKey');
            const value = (accessInput.value || '').trim();

            if (!value) {
                window.location.replace('{{ route('dashboard.widget') }}');
                return;
            }

            window.location.replace(`{{ route('dashboard.widget') }}/${encodeURIComponent(value)}`);
        }
    </script>
@endsection
