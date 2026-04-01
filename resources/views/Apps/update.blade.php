@extends('layouts.menu')
@section('title', __('ui.apps.update_title'))

@section('styles')
    <style>
        .update-wrap {
            max-width: 760px;
        }

        .update-hint {
            color: var(--ink-body);
            margin: 0;
        }

        .update-card {
            border-radius: 18px;
            border: 1px solid var(--line);
            background: var(--surface-strong);
            padding: 16px;
            box-shadow: var(--shadow-card);
        }

        .update-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 12px;
        }

        .update-badge {
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

        .update-drop {
            width: 100%;
            border: 1px dashed var(--line);
            border-radius: 14px;
            background: color-mix(in srgb, var(--surface-muted) 88%, transparent);
            padding: 12px;
            cursor: pointer;
            transition: border-color var(--transition), background-color var(--transition);
        }

        .update-drop:hover {
            border-color: var(--secondary);
            background: color-mix(in srgb, var(--secondary-soft) 32%, var(--surface-muted));
        }

        .update-drop-top {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .update-drop-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 38px;
            padding: 0 12px;
            border-radius: 10px;
            font-size: 0.84rem;
            font-weight: 700;
            color: var(--accent-contrast);
            background: linear-gradient(140deg, var(--accent), var(--accent-strong));
            box-shadow: 0 10px 18px rgba(187, 78, 29, 0.2);
        }

        .update-file-name {
            font-size: 0.88rem;
            color: var(--ink-soft);
        }

        .update-note {
            margin-top: 6px;
            font-size: 0.76rem;
            color: var(--ink-soft);
        }
    </style>
@endsection

@section('content')
    <section class="update-wrap">
        <p class="update-hint">{{ __('ui.apps.update_subtitle') }}</p>

        <form method="POST" action="{{ route('apps.update', $app) }}" enctype="multipart/form-data" class="update-card mt-3">
            @csrf

            <div class="update-meta">
                <span class="update-badge">{{ __('ui.apps.name') }}: {{ $app->name }}</span>
                <span class="update-badge">{{ __('ui.apps.slug') }}: {{ $app->slug }}</span>
                <span class="update-badge">{{ __('ui.apps.version') }}: {{ $app->version ?? '-' }}</span>
            </div>

            <div class="mb-3">
                <label for="app_zip" class="form-label">{{ __('ui.apps.archive_label') }}</label>
                <label for="app_zip" class="update-drop">
                    <span class="update-drop-top">
                        <span class="update-drop-btn">{{ __('ui.apps.upload_archive') }}</span>
                        <span class="update-file-name" id="updateFileName">{{ __('ui.apps.file_not_selected') }}</span>
                    </span>
                    <span class="update-note">{{ __('ui.apps.update_help') }}</span>
                </label>
                <input id="app_zip" type="file" name="app_zip" class="d-none" accept=".zip" required>
                @error('app_zip')
                    <small class="text-danger d-block mt-2">{{ $message }}</small>
                @enderror
            </div>

            <button class="btn btn-primary">{{ __('ui.apps.update') }}</button>
            <a href="{{ route('apps.index') }}" class="btn btn-outline-primary ms-2">{{ __('ui.apps.back_to_list') }}</a>
        </form>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('app_zip');
            const fileName = document.getElementById('updateFileName');

            if (!input || !fileName) {
                return;
            }

            input.addEventListener('change', function () {
                const selected = input.files && input.files.length > 0 ? input.files[0].name : "{{ __('ui.apps.file_not_selected') }}";
                fileName.textContent = selected;
            });
        });
    </script>
@endsection
