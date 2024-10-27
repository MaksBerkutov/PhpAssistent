@extends('layouts.menu')
@section('title','Authentication')

@section('content')
    <div class="container">
        <h1 class="text-center ion-fade-in">Добавить новое устройство</h1>

        <form action="{{ route('devices.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Имя устройства (используеться только для отображения)</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="url">URL Устройства</label>
                <input type="text" class="form-control @error('url') is-invalid @enderror" id="url" name="url" value="{{ old('url') }}" required>
                @error('url')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary ion-fade-in">Добавить</button>
        </form>
    </div>
@endsection
