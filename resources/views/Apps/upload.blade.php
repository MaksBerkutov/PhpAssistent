@extends('layouts.menu')
@section('title', 'Установка App')

@section('content')
    <div class="container mt-5">
        <h3>Установить новое приложение</h3>
        <form method="POST" action="{{ route('apps.install') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label>ZIP файл приложения</label>
                <input type="file" name="app_zip" class="form-control" required>
            </div>
            <button class="btn btn-primary">Установить</button>
        </form>
    </div>
@endsection
