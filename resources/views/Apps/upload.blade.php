@extends('layouts.menu')
@section('title', __('ui.apps.install_title'))

@section('styles')
    <style>
        .upload-wrap {
            max-width: 720px;
        }

        .upload-hint {
            color: var(--ink-body);
            margin: 0;
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

        .upload-drop {
            width: 100%;
            border: 1px dashed var(--line);
            border-radius: 14px;
            background: color-mix(in srgb, var(--surface-muted) 88%, transparent);
            padding: 12px;
            cursor: pointer;
            transition: border-color var(--transition), background-color var(--transition);
        }

        .upload-drop:hover {
            border-color: var(--secondary);
            background: color-mix(in srgb, var(--secondary-soft) 32%, var(--surface-muted));
        }

        .upload-drop-top {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .upload-drop-btn {
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

        .upload-file-name {
            font-size: 0.88rem;
            color: var(--ink-soft);
        }

        .upload-drop-note {
            margin-top: 6px;
            font-size: 0.76rem;
            color: var(--ink-soft);
        }
    </style>
@endsection

@section('content')
    <section class="upload-wrap">
        <p class="upload-hint">{{ __('ui.apps.install_subtitle') }}</p>

        <form method="POST" action="{{ route('apps.install') }}" enctype="multipart/form-data" class="upload-card mt-3">
            @csrf
            <div class="mb-3">
                <label for="app_zip" class="form-label">{{ __('ui.apps.archive_label') }}</label>
                <label for="app_zip" class="upload-drop">
                    <span class="upload-drop-top">
                        <span class="upload-drop-btn">{{ __('ui.apps.upload_archive') }}</span>
                        <span class="upload-file-name" id="uploadFileName">{{ __('ui.apps.file_not_selected') }}</span>
                    </span>
                    <span class="upload-drop-note">{{ __('ui.apps.file_hint') }}</span>
                </label>
                <input id="app_zip" type="file" name="app_zip" class="d-none" accept=".zip" required>
                @error('app_zip')
                    <small class="text-danger d-block mt-2">{{ $message }}</small>
                @enderror
            </div>

            <button class="btn btn-primary">{{ __('ui.apps.install') }}</button>
            <a href="{{ route('apps.index') }}" class="btn btn-outline-primary ms-2">{{ __('ui.apps.back_to_list') }}</a>

            <p class="upload-meta">{{ __('ui.apps.install_help') }}</p>
        </form>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('app_zip');
            const fileName = document.getElementById('uploadFileName');

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
