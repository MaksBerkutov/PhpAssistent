@extends('layouts.main')
@section('title', __('ui.verification.title'))
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}?v={{ filemtime(public_path('css/login.css')) }}">
@endsection

@section('content')
    <div class="auth-wrap">
        <div class="auth-shell" style="grid-template-columns: 1fr; max-width: 760px;">
            <div class="card auth-card p-4 p-lg-5">
                <h2 class="mb-2">{{ __('ui.verification.heading') }}</h2>
                <p class="text-muted mb-4">{{ __('ui.verification.description') }}</p>

                @if (session('status') === 'verification-link-sent')
                    <div class="alert alert-success" role="alert">
                        {{ __('ui.verification.link_sent') }}
                    </div>
                @endif

                <form method="post" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-dark">{{ __('ui.verification.resend') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
