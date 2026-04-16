@extends('layouts.menu')
@section('title', __('ui.public_api.page_title'))

@section('styles')
    <style>
        .settings-shell {
            display: grid;
            gap: 14px;
        }

        .settings-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .settings-card {
            height: 100%;
        }

        .settings-avatar {
            width: 88px;
            height: 88px;
            border-radius: 18px;
            object-fit: cover;
            border: 1px solid var(--line);
        }

        .settings-summary {
            display: flex;
            gap: 14px;
            align-items: center;
            flex-wrap: wrap;
        }

        .settings-kv {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .settings-kv .kv-item {
            padding: 12px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: color-mix(in srgb, var(--surface-muted) 88%, transparent);
        }

        .token-form-grid {
            display: grid;
            gap: 14px;
        }

        .ability-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 12px;
        }

        .ability-card {
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 12px;
            background: color-mix(in srgb, var(--surface-muted) 86%, transparent);
            display: grid;
            gap: 10px;
        }

        .ability-card label {
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .ability-routes {
            display: grid;
            gap: 6px;
        }

        .ability-route {
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
            font-size: 0.9rem;
            padding: 8px 10px;
            border-radius: 10px;
            background: rgba(15, 23, 42, 0.06);
        }

        .token-list {
            display: grid;
            gap: 14px;
        }

        .token-head {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }

        .token-meta {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .token-mono {
            width: 100%;
            min-height: 120px;
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
            font-size: 0.95rem;
        }

        .docs-table-wrap {
            overflow-x: auto;
        }

        .code-sample {
            white-space: pre-wrap;
            word-break: break-word;
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
            font-size: 0.93rem;
            padding: 12px;
            border-radius: 12px;
            border: 1px solid var(--line);
            background: rgba(15, 23, 42, 0.06);
        }

        @media (max-width: 980px) {
            .settings-grid,
            .settings-kv {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    @php
        $createdToken = session('public_api_created_token');
        $createdTokenName = session('public_api_created_token_name');
        $createSelectedAbilities = old('abilities', []);
    @endphp

    <section class="settings-shell">
        <header class="page-head">
            <div>
                <h2 class="page-title">{{ __('ui.public_api.page_title') }}</h2>
                <p class="page-subtitle">{{ __('ui.public_api.subtitle') }}</p>
            </div>
        </header>

        <section class="settings-grid">
            <article class="page-card settings-card">
                <div class="settings-summary mb-3">
                    <img src="data:image/png;base64,{{ $user->image }}" class="settings-avatar" alt="User avatar">
                    <div>
                        <h4 class="mb-1">{{ $user->name }}</h4>
                        <p class="text-muted mb-0">{{ __('ui.public_api.account_role', ['role' => $user->role]) }}</p>
                    </div>
                </div>

                <div class="settings-kv">
                    <div class="kv-item">
                        <small>{{ __('ui.public_api.account_email') }}</small>
                        <strong>{{ $user->email }}</strong>
                    </div>
                    <div class="kv-item">
                        <small>{{ __('ui.public_api.account_status') }}</small>
                        <strong>{{ $user->hasVerifiedEmail() ? __('ui.public_api.account_verified') : __('ui.public_api.account_not_verified') }}</strong>
                    </div>
                    <div class="kv-item">
                        <small>{{ __('ui.public_api.account_registered') }}</small>
                        <strong>{{ optional($user->created_at)->format('Y-m-d H:i') ?: __('ui.common.not_specified') }}</strong>
                    </div>
                    <div class="kv-item">
                        <small>{{ __('ui.public_api.account_updated') }}</small>
                        <strong>{{ optional($user->updated_at)->format('Y-m-d H:i') ?: __('ui.common.not_specified') }}</strong>
                    </div>
                </div>
            </article>

            <article class="page-card settings-card">
                <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-3">
                    <div>
                        <h4 class="mb-1">{{ __('ui.public_api.toggle_title') }}</h4>
                        <p class="text-muted mb-0">{{ __('ui.public_api.toggle_description') }}</p>
                    </div>
                    <span class="chip @if ($user->public_api_enabled) bg-success-subtle text-success-emphasis @else bg-secondary-subtle text-secondary-emphasis @endif">
                        {{ $user->public_api_enabled ? __('ui.public_api.enabled') : __('ui.public_api.disabled') }}
                    </span>
                </div>

                <form action="{{ route('settings.public-api.update') }}" method="POST" class="d-grid gap-3">
                    @csrf
                    @method('PUT')
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="public_api_enabled" name="public_api_enabled" value="1" @checked($user->public_api_enabled)>
                        <label class="form-check-label" for="public_api_enabled">{{ __('ui.public_api.enable_switch') }}</label>
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('ui.public_api.save_settings') }}</button>
                </form>
            </article>
        </section>

        @if ($createdToken)
            <article class="page-card">
                <div class="d-grid gap-2">
                    <div>
                        <h4 class="mb-1">{{ __('ui.public_api.token_created_title') }}</h4>
                        <p class="text-muted mb-0">{{ __('ui.public_api.token_created_description', ['name' => $createdTokenName ?: __('ui.public_api.token_fallback_name')]) }}</p>
                    </div>
                    <textarea class="form-control token-mono" readonly>{{ $createdToken }}</textarea>
                </div>
            </article>
        @endif

        <article class="page-card">
            <div class="mb-3">
                <h4 class="mb-1">{{ __('ui.public_api.create_token_title') }}</h4>
                <p class="text-muted mb-0">{{ __('ui.public_api.create_token_description') }}</p>
            </div>

            <form action="{{ route('settings.public-api.tokens.store') }}" method="POST" class="token-form-grid">
                @csrf
                <div class="row g-3">
                    <div class="col-lg-8">
                        <label for="token_name" class="form-label">{{ __('ui.public_api.token_name') }}</label>
                        <input id="token_name" type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-lg-4 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="token_is_enabled" name="is_enabled" value="1" @checked(old('is_enabled', '1'))>
                            <label class="form-check-label" for="token_is_enabled">{{ __('ui.public_api.token_enabled') }}</label>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="form-label">{{ __('ui.public_api.permissions_title') }}</label>
                    <div class="ability-grid">
                        @foreach ($publicApiGroups as $group)
                            <section class="ability-card">
                                <div>
                                    <h6 class="mb-1">{{ __($group['title_key']) }}</h6>
                                    <p class="text-muted mb-0">{{ __($group['description_key']) }}</p>
                                </div>

                                @foreach ($group['abilities'] as $ability)
                                    <div>
                                        <label>
                                            <input type="checkbox" name="abilities[]" value="{{ $ability['key'] }}" @checked(in_array($ability['key'], $createSelectedAbilities, true))>
                                            <span>{{ __($ability['title_key']) }}</span>
                                        </label>
                                        <p class="text-muted mb-2">{{ __($ability['description_key']) }}</p>
                                        <div class="ability-routes">
                                            @foreach ($ability['routes'] as $route)
                                                <div class="ability-route">{{ $route['method'] }} {{ $route['uri'] }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </section>
                        @endforeach
                    </div>
                    @error('abilities')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                    @error('abilities.*')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary">{{ __('ui.public_api.create_token_button') }}</button>
                </div>
            </form>
        </article>

        <article class="page-card">
            <div class="token-head mb-3">
                <div>
                    <h4 class="mb-1">{{ __('ui.public_api.tokens_title') }}</h4>
                    <p class="text-muted mb-0">{{ __('ui.public_api.tokens_description') }}</p>
                </div>
                <span class="chip">{{ __('ui.public_api.tokens_count', ['count' => $publicApiTokens->count()]) }}</span>
            </div>

            @if ($publicApiTokens->isEmpty())
                <section class="page-empty">
                    <p class="mb-0">{{ __('ui.public_api.tokens_empty') }}</p>
                </section>
            @else
                <div class="token-list">
                    @foreach ($publicApiTokens as $token)
                        @php($tokenAbilities = $token->abilities ?? [])
                        <section class="border rounded-4 p-3">
                            <div class="token-head mb-3">
                                <div>
                                    <h5 class="mb-1">{{ $token->name }}</h5>
                                    <div class="token-meta">
                                        <span class="chip @if ($token->is_enabled) bg-success-subtle text-success-emphasis @else bg-secondary-subtle text-secondary-emphasis @endif">
                                            {{ $token->is_enabled ? __('ui.public_api.enabled') : __('ui.public_api.disabled') }}
                                        </span>
                                        <span class="chip">{{ __('ui.public_api.last_used', ['value' => optional($token->last_used_at)->format('Y-m-d H:i') ?: __('ui.public_api.never_used')]) }}</span>
                                        <span class="chip">{{ __('ui.public_api.created_at', ['value' => optional($token->created_at)->format('Y-m-d H:i') ?: __('ui.common.not_specified')]) }}</span>
                                    </div>
                                </div>
                            </div>

                            <form action="{{ route('settings.public-api.tokens.update', $token->id) }}" method="POST" class="token-form-grid">
                                @csrf
                                @method('PUT')

                                <div class="row g-3">
                                    <div class="col-lg-8">
                                        <label class="form-label">{{ __('ui.public_api.token_name') }}</label>
                                        <input type="text" name="name" value="{{ $token->name }}" class="form-control" required>
                                    </div>
                                    <div class="col-lg-4 d-flex align-items-end">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="token-enabled-{{ $token->id }}" name="is_enabled" value="1" @checked($token->is_enabled)>
                                            <label class="form-check-label" for="token-enabled-{{ $token->id }}">{{ __('ui.public_api.token_enabled') }}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="ability-grid">
                                    @foreach ($publicApiGroups as $group)
                                        <section class="ability-card">
                                            <div>
                                                <h6 class="mb-1">{{ __($group['title_key']) }}</h6>
                                                <p class="text-muted mb-0">{{ __($group['description_key']) }}</p>
                                            </div>

                                            @foreach ($group['abilities'] as $ability)
                                                <div>
                                                    <label>
                                                        <input type="checkbox" name="abilities[]" value="{{ $ability['key'] }}" @checked(in_array($ability['key'], $tokenAbilities, true))>
                                                        <span>{{ __($ability['title_key']) }}</span>
                                                    </label>
                                                    <p class="text-muted mb-2">{{ __($ability['description_key']) }}</p>
                                                    <div class="ability-routes">
                                                        @foreach ($ability['routes'] as $route)
                                                            <div class="ability-route">{{ $route['method'] }} {{ $route['uri'] }}</div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </section>
                                    @endforeach
                                </div>

                                <div class="d-flex flex-wrap gap-2">
                                    <button type="submit" class="btn btn-primary">{{ __('ui.public_api.update_token_button') }}</button>
                                </div>
                            </form>

                            <form action="{{ route('settings.public-api.tokens.destroy', $token->id) }}" method="POST" class="mt-3" onsubmit="return confirm('{{ __('ui.public_api.delete_confirm', ['name' => addslashes($token->name)]) }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">{{ __('ui.public_api.delete_token_button') }}</button>
                            </form>
                        </section>
                    @endforeach
                </div>
            @endif
        </article>

        <section class="settings-grid">
            <article class="page-card settings-card">
                <div class="mb-3">
                    <h4 class="mb-1">{{ __('ui.public_api.quickstart_title') }}</h4>
                    <p class="text-muted mb-0">{{ __('ui.public_api.quickstart_description') }}</p>
                </div>

                <div class="code-sample">Authorization: Bearer &lt;TOKEN&gt;
Accept: application/json
Base URL: {{ $publicApiBaseUrl }}</div>
            </article>

            <article class="page-card settings-card">
                <div class="mb-3">
                    <h4 class="mb-1">{{ __('ui.public_api.example_title') }}</h4>
                    <p class="text-muted mb-0">{{ __('ui.public_api.example_description') }}</p>
                </div>

                <div class="code-sample">curl -X GET "{{ $publicApiBaseUrl }}/devices" \
-H "Authorization: Bearer YOUR_TOKEN" \
-H "Accept: application/json"</div>
            </article>
        </section>

        <article class="page-card">
            <div class="mb-3">
                <h4 class="mb-1">{{ __('ui.public_api.routes_title') }}</h4>
                <p class="text-muted mb-0">{{ __('ui.public_api.routes_description') }}</p>
            </div>

            <div class="docs-table-wrap">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 110px;">{{ __('ui.public_api.route_method') }}</th>
                            <th>{{ __('ui.public_api.route_uri') }}</th>
                            <th style="width: 320px;">{{ __('ui.public_api.route_permission') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($publicApiRoutes as $route)
                            <tr>
                                <td><span class="chip">{{ $route['method'] }}</span></td>
                                <td><code>{{ $route['uri'] }}</code></td>
                                <td>{{ __($route['ability_title_key']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </article>
    </section>
@endsection
