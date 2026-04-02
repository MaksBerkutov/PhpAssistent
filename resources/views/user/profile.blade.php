@extends('layouts.menu')
@section('title', 'Профиль')

@section('styles')
    <style>
        .profile-grid {
            display: grid;
            grid-template-columns: minmax(240px, 320px) 1fr;
            gap: 12px;
        }

        .profile-avatar {
            width: 112px;
            height: 112px;
            border-radius: 18px;
            object-fit: cover;
            border: 1px solid var(--line);
            box-shadow: var(--shadow-card);
        }

        .profile-side {
            text-align: center;
            display: grid;
            justify-items: center;
            gap: 10px;
        }

        @media (max-width: 900px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    @php($user = Auth::user())

    <div class="page-shell">
        <section class="page-head">
            <div>
                <h2 class="page-title">Профиль пользователя</h2>
                <p class="page-subtitle">Основная информация аккаунта и статус безопасности.</p>
            </div>
        </section>

        <section class="profile-grid">
            <article class="page-card profile-side">
                <img src="data:image/png;base64,{{ $user->image }}" class="profile-avatar" alt="User avatar">
                <div>
                    <h4 class="mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-0">Роль: {{ $user->role }}</p>
                </div>
                <div class="chip-list justify-content-center">
                    <span class="chip">{{ $user->hasVerifiedEmail() ? 'Email подтверждён' : 'Email не подтверждён' }}</span>
                </div>
            </article>

            <article class="page-card">
                <div class="kv-grid">
                    <div class="kv-item">
                        <small>Email</small>
                        <strong>{{ $user->email }}</strong>
                    </div>
                    <div class="kv-item">
                        <small>Имя</small>
                        <strong>{{ $user->name }}</strong>
                    </div>
                    <div class="kv-item">
                        <small>Роль</small>
                        <strong>{{ $user->role }}</strong>
                    </div>
                    <div class="kv-item">
                        <small>Дата регистрации</small>
                        <strong>{{ optional($user->created_at)->format('Y-m-d H:i') ?: '—' }}</strong>
                    </div>
                    <div class="kv-item">
                        <small>Последнее обновление</small>
                        <strong>{{ optional($user->updated_at)->format('Y-m-d H:i') ?: '—' }}</strong>
                    </div>
                    <div class="kv-item">
                        <small>Безопасность</small>
                        <strong>{{ $user->email_verified_at ? 'Подтверждено' : 'Требуется подтверждение' }}</strong>
                    </div>
                </div>
            </article>
        </section>
    </div>
@endsection
