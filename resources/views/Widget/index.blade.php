@extends('layouts.menu')
@section('title', 'All Widgets')

@section('content')
    <div class="container py-4">

        {{-- Форма загрузки нового виджета --}}
        <div class="card shadow-sm mb-5">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Установить / Обновить виджет</h5>
            </div>
            <div class="card-body">
                <form id="installForm" method="POST" action="{{ route('widgets.install') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-2 align-items-center">
                        <div class="col-md-8">
                            <input type="file" name="widget" class="form-control" required>
                        </div>
                        <div class="col-md-4 text-end">
                            <button type="submit" class="btn btn-success w-100">Загрузить</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <h2 class="mb-4">Виджеты</h2>

        <div class="row">
            @foreach ($widgets as $widget)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-light">
                            <strong>{{ $widget->name }}</strong>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title">Имя компонента: <span
                                    class="text-secondary">{{ $widget->widget_name }}</span></h6>
                            <p class="card-text"><strong>Ключ:</strong> {{ $widget->accesses_key }}</p>

                            @php
                                $commands = json_decode($widget->input_params, true);
                            @endphp

                            @if (!empty($commands))
                                <h6 class="mt-3">Параметры:</h6>
                                <div class="accordion" id="accordion-{{ $widget->id }}">
                                    @foreach ($commands as $key => $value)
                                        <div class="accordion-item mb-2">
                                            <h2 class="accordion-header"
                                                id="heading-{{ $widget->id }}-{{ $loop->index }}">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapse-{{ $widget->id }}-{{ $loop->index }}"
                                                    aria-expanded="false"
                                                    aria-controls="collapse-{{ $widget->id }}-{{ $loop->index }}">
                                                    {{ $key }}
                                                </button>
                                            </h2>
                                            <div id="collapse-{{ $widget->id }}-{{ $loop->index }}"
                                                class="accordion-collapse collapse"
                                                aria-labelledby="heading-{{ $widget->id }}-{{ $loop->index }}"
                                                data-bs-parent="#accordion-{{ $widget->id }}">
                                                <div class="accordion-body">
                                                    <p>Тип: <strong>{{ $value }}</strong></p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">Параметры отсутствуют</p>
                            @endif
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{ route('widget.edit', $widget->id) }}"
                                class="btn btn-outline-primary btn-sm">Редактировать</a>
                            <form action="{{ route('widget.delete', $widget->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                    onclick="return confirm('Вы уверены, что хотите удалить виджет?')">Удалить</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
