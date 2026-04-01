@extends('layouts.menu')
@section('title', __('ui.apps.install_title'))

@section('styles')
    <style>
        .upload-wrap {
            max-width: 720px;
        }

        .upload-hint {
            color: var(--ink-body);
            margin: 8px 0 0;
        }

        .upload-card {
            border-radius: 18px;
            border: 1px solid var(--line);
            background: var(--surface-strong);
            padding: 16px;
            box-shadow: var(--shadow-card);
        }

        .upload-meta {
            margin-top: 10px;
            font-size: 0.86rem;
            color: var(--ink-soft);
        }
    </style>
@endsection

@section('content')
    <section class="upload-wrap">
        <h2 class="mb-0">{{ __('ui.apps.install_title') }}</h2>
        <p class="upload-hint">{{ __('ui.apps.install_subtitle') }}</p>

        <form method="POST" action="{{ route('apps.install') }}" enctype="multipart/form-data" class="upload-card mt-3">
            @csrf
            <div class="mb-3">
                <label for="app_zip" class="form-label">{{ __('ui.apps.archive_label') }}</label>
                <input id="app_zip" type="file" name="app_zip" class="form-control" accept=".zip" required>
                @error('app_zip')
                    <small class="text-danger d-block mt-2">{{ $message }}</small>
                @enderror
            </div>

            <button class="btn btn-primary">{{ __('ui.apps.install') }}</button>
            <a href="{{ route('apps.index') }}" class="btn btn-outline-primary ms-2">{{ __('ui.apps.back_to_list') }}</a>

            <p class="upload-meta">{{ __('ui.apps.install_help') }}</p>
        </form>
    </section>
@endsection
