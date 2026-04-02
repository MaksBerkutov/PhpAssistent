@extends('layouts.menu')
@section('title', 'Добавить устройство')

@section('content')
    <div class="page-shell">
        <section class="page-head">
            <div>
                <h2 class="page-title">Добавить устройство</h2>
                <p class="page-subtitle">Создайте новое устройство для управления командами и отображения в виджетах.</p>
            </div>
            <a href="{{ route('devices') }}" class="btn btn-outline-primary">К списку устройств</a>
        </section>

        <section class="page-card" style="max-width: 760px;">
            <form action="{{ route('devices.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Название устройства</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    <div class="form-text">Используется в интерфейсе и карточках управления.</div>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="url" class="form-label">URL устройства</label>
                    <input type="text" class="form-control @error('url') is-invalid @enderror" id="url" name="url" value="{{ old('url') }}" placeholder="Например: http://192.168.1.15" required>
                    @error('url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary">Добавить устройство</button>
                    <a href="{{ route('devices') }}" class="btn btn-outline-primary">Отмена</a>
                </div>
            </form>
        </section>
    </div>
@endsection
