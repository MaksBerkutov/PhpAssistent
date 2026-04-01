@extends('layouts.menu')
@section('title', 'Status Beacon')

@section('styles')
    <style>
        .statusbeacon-page {
            max-width: 900px;
            display: grid;
            gap: 12px;
        }

        .statusbeacon-card {
            border: 1px solid var(--line);
            border-radius: 16px;
            background: var(--surface-strong);
            box-shadow: var(--shadow-card);
            padding: 16px;
        }

        .statusbeacon-title {
            margin: 0 0 8px;
            font-size: 1.3rem;
            color: var(--ink);
            font-weight: 700;
        }

        .statusbeacon-row {
            margin: 8px 0;
            color: var(--ink-body);
        }

        .statusbeacon-row strong {
            color: var(--ink);
        }

        .statusbeacon-hint {
            margin-top: 10px;
            color: var(--ink-soft);
            font-size: 0.9rem;
        }
    </style>
@endsection

@section('content')
    <section class="statusbeacon-page">
        <article class="statusbeacon-card">
            <h2 class="statusbeacon-title">Status Beacon</h2>
            <p class="statusbeacon-row">User: <strong>{{ $user ?? 'unknown' }}</strong></p>
            <p class="statusbeacon-row">Server time: <strong>{{ $serverTime }}</strong></p>
            <p class="statusbeacon-hint">Этот экран добавлен через app menuItems().</p>
        </article>
    </section>
@endsection
