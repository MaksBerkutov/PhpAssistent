@extends('layouts.menu')
@section('title', __('ui.home.page_title'))

@section('styles')
    <style>
        .home-grid {
            display: grid;
            gap: 16px;
        }

        .home-hero {
            border: 1px solid var(--line);
            border-radius: 22px;
            background: linear-gradient(145deg, rgba(31, 122, 114, 0.14), rgba(216, 100, 44, 0.1));
            padding: clamp(18px, 2.8vw, 32px);
        }

        .home-kicker {
            margin: 0 0 8px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--ink-soft);
        }

        .home-title {
            margin: 0;
            color: var(--ink);
            font-weight: 800;
            font-size: clamp(1.35rem, 2vw, 2rem);
        }

        .home-subtitle {
            margin: 12px 0 0;
            max-width: 70ch;
            color: var(--ink-body);
        }

        .home-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 14px;
        }

        .home-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 6px 11px;
            font-size: 0.76rem;
            font-weight: 700;
            background: var(--surface-strong);
            border: 1px solid var(--line);
            color: var(--ink-body);
        }

        .quick-actions {
            display: grid;
            gap: 10px;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
        }

        .quick-actions .btn {
            min-height: 46px;
        }

        .info-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 12px;
        }

        .info-card {
            border-radius: 16px;
            border: 1px solid var(--line);
            background: var(--surface-strong);
            padding: 14px;
        }

        .info-card small {
            display: block;
            font-size: 0.74rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--ink-soft);
            margin-bottom: 6px;
            font-weight: 700;
        }

        .info-card strong {
            font-size: 1.05rem;
            color: var(--ink);
            font-weight: 700;
        }

        .start-list {
            margin: 0;
            padding-left: 18px;
            color: var(--ink-body);
            display: grid;
            gap: 8px;
        }
    </style>
@endsection

@section('content')
    <div class="home-grid">
        <section class="home-hero">
            <p class="home-kicker">{{ __('ui.home.kicker') }}</p>
            <h2 class="home-title">{{ __('ui.home.title', ['name' => Auth::user()->name]) }}</h2>
            <p class="home-subtitle">{{ __('ui.home.subtitle') }}</p>
            <div class="home-badges">
                <span class="home-badge">{{ __('ui.home.role', ['role' => Auth::user()->role]) }}</span>
                <span class="home-badge">
                    {{ __('ui.home.email', ['status' => Auth::user()->hasVerifiedEmail() ? __('ui.home.email_verified') : __('ui.home.email_not_verified')]) }}
                </span>
            </div>
        </section>

        <section class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('ui.home.quick_actions') }}</h5>
            </div>
            <div class="card-body">
                <div class="quick-actions">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">{{ __('ui.home.open_dashboard') }}</a>
                    <a href="{{ route('devices') }}" class="btn btn-outline-primary">{{ __('ui.home.devices') }}</a>
                    <a href="{{ route('scenario') }}" class="btn btn-outline-primary">{{ __('ui.home.scenarios') }}</a>
                    <a href="{{ route('voice') }}" class="btn btn-outline-primary">{{ __('ui.home.voice_assistant') }}</a>
                </div>
            </div>
        </section>

        <section class="info-cards">
            <article class="info-card">
                <small>{{ __('ui.home.account') }}</small>
                <strong>{{ Auth::user()->email }}</strong>
            </article>
            <article class="info-card">
                <small>{{ __('ui.home.status') }}</small>
                <strong>{{ Auth::user()->hasVerifiedEmail() ? __('ui.home.status_active') : __('ui.home.status_verification') }}</strong>
            </article>
            <article class="info-card">
                <small>{{ __('ui.home.default_entry') }}</small>
                <strong>{{ __('ui.home.default_dashboard') }}</strong>
            </article>
            <article class="info-card">
                <small>{{ __('ui.home.session') }}</small>
                <strong>{{ now()->format('Y-m-d H:i') }}</strong>
            </article>
        </section>

        <section class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('ui.home.checklist_title') }}</h5>
            </div>
            <div class="card-body">
                <ol class="start-list">
                    <li>{{ __('ui.home.checklist_1') }}</li>
                    <li>{{ __('ui.home.checklist_2') }}</li>
                    <li>{{ __('ui.home.checklist_3') }}</li>
                    <li>{{ __('ui.home.checklist_4') }}</li>
                </ol>
            </div>
        </section>
    </div>
@endsection
