@extends('layouts.menu')
@section('title', 'Приложение: ' . $app->name)

@section('content')
    <div class="container mt-5">
        <h3>{{ $app->name }}</h3>
        <div class="mb-3">
            <a href="{{ route('apps.index') }}" class="btn btn-secondary mb-3">Назад к списку</a>
        </div>
        <div class="app-content">
            {!! $html !!}
        </div>
    </div>
@endsection
