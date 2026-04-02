@extends('layouts.main')
@section('title', 'Подтверждение email')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}?v={{ filemtime(public_path('css/login.css')) }}">
@endsection

@section('content')
    <div class="auth-wrap">
        <div class="auth-shell" style="grid-template-columns: 1fr; max-width: 760px;">
            <div class="card auth-card p-4 p-lg-5">
                <h2 class="mb-2">Подтвердите email</h2>
                <p class="text-muted mb-4">
                    Мы отправили ссылку для подтверждения регистрации на ваш адрес электронной почты.
                    После подтверждения станут доступны все функции аккаунта.
                </p>

                @if (session('status') === 'verification-link-sent')
                    <div class="alert alert-success" role="alert">
                        Новая ссылка подтверждения была отправлена.
                    </div>
                @endif

                <form method="post" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-dark">Отправить ссылку повторно</button>
                </form>
            </div>
        </div>
    </div>
@endsection
