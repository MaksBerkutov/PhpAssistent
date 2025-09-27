@extends('layouts.menu')
@section('title', 'Установленные приложения')

@section('content')
    <div class="container mt-5">
        <h3>Установленные приложения</h3>
        <div class="row">
            @foreach ($apps as $app)
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-header">
                            {{ $app->name }}
                        </div>
                        <div class="card-body">
                            <p>Версия: {{ $app->version ?? '-' }}</p>
                            <p>{{ $app->description ?? '' }}</p>
                            <a href="{{ route('apps.open', $app) }}" class="btn btn-primary">Открыть</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
