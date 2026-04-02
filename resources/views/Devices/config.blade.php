@extends('layouts.menu')
@section('title', __('ui.devices.config_title'))

@section('content')
    @php
        $data = json_decode($jsonData, true) ?? [];
    @endphp

    <div class="page-shell">
        <section class="page-head">
            <div>
                <h2 class="page-title">{{ __('ui.devices.config_edit_title') }}</h2>
                <p class="page-subtitle">{{ __('ui.devices.config_subtitle') }}</p>
            </div>
            <a href="{{ route('devices') }}" class="btn btn-outline-primary">{{ __('ui.devices.back_to_list') }}</a>
        </section>

        <section class="page-card" style="max-width: 860px;">
            <form id="configureForm" method="post" action="{{ route('devices.configure') }}">
                @csrf

                @forelse ($data as $key => $value)
                    <div class="mb-3">
                        <label for="cfg-{{ $key }}" class="form-label">{{ ucfirst($key) }}</label>

                        @if (is_bool($value))
                            <select id="cfg-{{ $key }}" class="form-select config-input" data-name="{{ $key }}" required>
                                <option value="true" @selected($value === true)>true</option>
                                <option value="false" @selected($value === false)>false</option>
                            </select>
                        @elseif (is_numeric($value) && strpos((string) $value, '.') !== false)
                            <input id="cfg-{{ $key }}" type="number" class="form-control config-input" data-name="{{ $key }}" value="{{ $value }}" step="any" required>
                        @elseif (is_numeric($value))
                            <input id="cfg-{{ $key }}" type="number" class="form-control config-input" data-name="{{ $key }}" value="{{ $value }}" required>
                        @else
                            <input id="cfg-{{ $key }}" type="text" class="form-control config-input" data-name="{{ $key }}" value="{{ $value }}" required>
                        @endif
                    </div>
                @empty
                    <p class="mb-0 text-muted">{{ __('ui.devices.config_empty') }}</p>
                @endforelse

                <input type="hidden" id="jsonData" name="jsonData">
                <input type="hidden" name="id" value="{{ $id }}">

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary">{{ __('ui.devices.config_save') }}</button>
                    <a href="{{ route('devices') }}" class="btn btn-outline-primary">{{ __('ui.common.cancel') }}</a>
                </div>
            </form>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('configureForm');
            const jsonDataInput = document.getElementById('jsonData');

            if (!form || !jsonDataInput) {
                return;
            }

            function normalizeValue(input) {
                const raw = input.value;
                const inputType = input.getAttribute('type');

                if (input.tagName === 'SELECT' && (raw === 'true' || raw === 'false')) {
                    return raw === 'true';
                }

                if (inputType === 'number') {
                    if (raw.includes('.')) {
                        return parseFloat(raw);
                    }
                    return parseInt(raw, 10);
                }

                return raw;
            }

            function collectData() {
                const updatedData = {};
                const inputs = form.querySelectorAll('.config-input');

                inputs.forEach(function (input) {
                    const name = input.dataset.name;
                    if (!name) {
                        return;
                    }
                    updatedData[name] = normalizeValue(input);
                });

                jsonDataInput.value = JSON.stringify(updatedData);
            }

            form.addEventListener('submit', collectData);
            form.addEventListener('input', collectData);
            form.addEventListener('change', collectData);
            collectData();
        });
    </script>
@endsection
