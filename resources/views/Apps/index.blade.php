@extends('layouts.menu')
@section('title', __('ui.apps.title'))

@section('styles')
    <style>
        .apps-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }

        .apps-subtitle {
            margin: 0;
            color: var(--ink-body);
            max-width: 72ch;
        }

        .apps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 12px;
        }

        .apps-card {
            display: flex;
            flex-direction: column;
            min-height: 220px;
        }

        .apps-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .apps-actions form {
            grid-column: 1 / -1;
        }

        .apps-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 10px;
        }

        .apps-badge {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            border: 1px solid var(--line);
            background: var(--surface-muted);
            color: var(--ink-soft);
            padding: 4px 9px;
            font-size: 0.74rem;
            font-weight: 700;
        }

        .apps-empty {
            border: 1px dashed var(--line);
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            color: var(--ink-soft);
            background: color-mix(in srgb, var(--surface-strong) 86%, transparent);
        }

        @media (max-width: 640px) {
            .apps-grid {
                grid-template-columns: 1fr;
            }

            .apps-actions {
                grid-template-columns: 1fr;
            }

            .apps-header .btn {
                width: 100%;
            }

            .apps-card {
                min-height: 0;
            }
        }
    </style>
@endsection

@section('content')
    <section class="apps-header">
        <div>
            <p class="apps-subtitle">{{ __('ui.apps.subtitle') }}</p>
        </div>
        <a href="{{ route('apps.upload') }}" class="btn btn-primary">{{ __('ui.apps.install_new') }}</a>
    </section>

    @if ($apps->isEmpty())
        <section class="apps-empty">
            <p class="mb-2">{{ __('ui.apps.empty') }}</p>
            <a href="{{ route('apps.upload') }}" class="btn btn-outline-primary">{{ __('ui.apps.upload_archive') }}</a>
        </section>
    @else
        <section class="apps-grid">
            @foreach ($apps as $app)
                <article class="card apps-card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-2">
                        <strong>{{ $app->name }}</strong>
                        <span class="apps-badge">{{ $app->slug }}</span>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class="apps-meta">
                            <span class="apps-badge">{{ __('ui.apps.version') }}: {{ $app->version ?? '-' }}</span>
                        </div>
                        <p class="mb-3">{{ $app->description ?: __('ui.apps.no_description') }}</p>
                        <div class="mt-auto">
                            <div class="apps-actions">
                                <a href="{{ route('apps.open', $app) }}" class="btn btn-primary">{{ __('ui.apps.open') }}</a>
                                <a href="{{ route('apps.update.form', $app) }}" class="btn btn-outline-primary">{{ __('ui.apps.update') }}</a>
                                <form method="POST" action="{{ route('apps.destroy', $app) }}" onsubmit="return confirm('{{ __('ui.apps.delete_confirm') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger w-100">{{ __('ui.apps.delete') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </section>
    @endif
@endsection
