@extends('layouts.menu')
@section('title', 'Ответ устройства')

@section('styles')
    <style>
        .response-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px;
        }

        .response-list {
            margin: 0;
            padding-left: 18px;
            display: grid;
            gap: 4px;
        }
    </style>
@endsection

@section('content')
    <div class="page-shell">
        <section class="page-head">
            <div>
                <h2 class="page-title">Ответ на команду: {{ $validated['command'] }}</h2>
                <p class="page-subtitle">Источник: {{ $validated['url'] }}</p>
            </div>
            <a href="{{ route('devices') }}" class="btn btn-outline-primary">К устройствам</a>
        </section>

        @if(empty($response))
            <section class="page-empty">
                <p class="mb-0">Устройство не вернуло данных.</p>
            </section>
        @else
            <section class="response-grid">
                @foreach($response as $key => $value)
                    <article class="card h-100">
                        <div class="card-header">
                            <strong>{{ $key }}</strong>
                        </div>
                        <div class="card-body">
                            @if(is_array($value) || is_object($value))
                                <ul class="response-list">
                                    @foreach($value as $subKey => $subValue)
                                        <li><strong>{{ $subKey }}:</strong> {{ $subValue }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="mb-0"><strong>{{ $value }}</strong></p>
                            @endif
                        </div>
                    </article>
                @endforeach
            </section>
        @endif
    </div>
@endsection
