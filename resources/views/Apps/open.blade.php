@extends('layouts.menu')
@section('title', __('ui.apps.open_title', ['name' => $app->name]))

@section('styles')
    <style>
        .app-open-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }

        .app-open-subtitle {
            margin: 6px 0 0;
            color: var(--ink-body);
        }

        .app-runtime {
            border-radius: 18px;
            border: 1px solid var(--line);
            background: var(--surface-strong);
            box-shadow: var(--shadow-card);
            padding: 14px;
            max-width: 100%;
            overflow: hidden;
        }

        .app-runtime-inner {
            max-width: 100%;
            overflow-x: auto;
            overflow-y: auto;
            max-height: 68vh;
            scrollbar-width: thin;
        }

        .app-runtime-inner > * {
            max-width: 100%;
        }

        @media (max-width: 640px) {
            .app-runtime {
                padding: 10px;
                border-radius: 14px;
            }

            .app-runtime-inner {
                max-height: 62vh;
            }

            .app-open-head .btn {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <section class="app-open-head">
        <div>
            <h2 class="mb-0">{{ $app->name }}</h2>
            <p class="app-open-subtitle">{{ __('ui.apps.open_subtitle', ['slug' => $app->slug]) }}</p>
        </div>
        <a href="{{ route('apps.index') }}" class="btn btn-outline-primary">{{ __('ui.apps.back_to_list') }}</a>
    </section>

    <section class="app-runtime">
        <div class="app-runtime-inner">
            {!! $html !!}
        </div>
    </section>
@endsection
