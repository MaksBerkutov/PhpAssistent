@extends('layouts.menu')
@section('title', __('ui.devices.create_title'))

@section('content')
    <div class="page-shell">
        <section class="page-head">
            <div>
                <h2 class="page-title">{{ __('ui.devices.create_title') }}</h2>
                <p class="page-subtitle">{{ __('ui.devices.create_subtitle') }}</p>
            </div>
            <a href="{{ route('devices') }}" class="btn btn-outline-primary">{{ __('ui.devices.back_to_list') }}</a>
        </section>

        <section class="page-card" style="max-width: 760px;">
            <form action="{{ route('devices.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">{{ __('ui.devices.name_label') }}</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    <div class="form-text">{{ __('ui.devices.name_help') }}</div>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="url" class="form-label">{{ __('ui.devices.url_label') }}</label>
                    <input type="text" class="form-control @error('url') is-invalid @enderror" id="url" name="url" value="{{ old('url') }}" placeholder="{{ __('ui.devices.url_placeholder') }}" required>
                    @error('url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary">{{ __('ui.devices.submit_add') }}</button>
                    <a href="{{ route('devices') }}" class="btn btn-outline-primary">{{ __('ui.common.cancel') }}</a>
                </div>
            </form>
        </section>
    </div>
@endsection
