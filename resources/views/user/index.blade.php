@extends('layouts.main')
@section('title','Authentication')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}?v={{ filemtime(public_path('css/login.css')) }}">
    <script src="{{ asset('js/login.js') }}?v={{ filemtime(public_path('js/login.js')) }}"></script>
@endsection
@section('content')
<div class="auth-wrap">
    <div class="auth-shell">
        <aside class="auth-intro">
            <p class="auth-kicker">Smart Home Platform</p>
            <h1 class="auth-title">PhpAssistant</h1>
            <p class="auth-description">
                Управляйте модулями, сценариями и голосовыми командами в одном аккуратном интерфейсе.
            </p>
            <div class="auth-points">
                <span>Единая панель устройств</span>
                <span>Автоматизация сценариев</span>
                <span>Быстрый доступ с телефона</span>
            </div>
        </aside>

        <div class="card auth-card p-4 p-lg-5">
            <ul class="nav nav-pills mb-4 d-flex justify-content-center" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active mx-1" id="pills-home-tab" data-bs-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-1" id="pills-profile-tab" data-bs-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Signup</a>
                </li>
            </ul>

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                    <form method="post" action="{{route('authentication')}}">
                        @csrf
                        <div class="form">
                            <x-default-form-input type="text" name="email"/>
                            <x-default-form-input type="password" name="password"/>
                            <button class="btn btn-dark w-100">Login</button>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                    <form method="post" action="{{route('register')}}">
                        @csrf
                        <div class="form">
                            <x-default-form-input type="text" name="name"/>
                            <x-default-form-input type="text" name="email"/>
                            <x-default-form-input type="password" name="password"/>
                            <x-default-form-input type="password" name="password_confirmation" placeholder="Password confirmation"/>
                            <button class="btn btn-dark w-100">Signup</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
