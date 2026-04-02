@extends('layouts.menu')
@section('title', __('ui.voice.add_command'))
@section('styles')
    <script src="{{ asset('js/voice.js') }}"></script>
@endsection

@section('content')
    <div class="page-shell">
        <section class="page-head">
            <div>
                <h2 class="page-title">{{ __('ui.voice.create_title') }}</h2>
                <p class="page-subtitle">{{ __('ui.voice.create_subtitle') }}</p>
            </div>
            <a href="{{ route('voice') }}" class="btn btn-outline-primary">{{ __('ui.voice.back_to_assistant') }}</a>
        </section>

        <section class="page-card" style="max-width: 840px;">
            <form action="{{ route('voice.store') }}" method="post">
                @csrf

                <div class="mb-3">
                    <label for="module" class="form-label">{{ __('ui.voice.device') }}</label>
                    <select id="module" name="devices_id" class="form-select @error('devices_id') is-invalid @enderror" required>
                        <option value="">{{ __('ui.common.select_device') }}</option>
                        @foreach($devices as $device)
                            <option value="{{ $device->id }}" data-commands="{{ json_encode($device->command) }}" {{ old('devices_id') == $device->id ? 'selected' : '' }}>
                                {{ $device->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('devices_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="command" class="form-label">{{ __('ui.voice.device_command') }}</label>
                    <select id="command" name="command" class="form-select @error('command') is-invalid @enderror" required>
                        <option value="">{{ __('ui.common.select_command') }}</option>
                    </select>
                    @error('command')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <x-default-form-input type="text" name="text_trigger" :placeholder="__('ui.voice.trigger_placeholder')" :text="__('ui.voice.trigger')"/>
                <x-default-form-input type="text" name="voice" :placeholder="__('ui.voice.response_text_placeholder')" :text="__('ui.voice.response_text')"/>

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary">{{ __('ui.voice.save_command') }}</button>
                    <a href="{{ route('voice') }}" class="btn btn-outline-primary">{{ __('ui.common.cancel') }}</a>
                </div>
            </form>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const moduleSelect = document.getElementById('module');
            const commandSelect = document.getElementById('command');
            const oldCommand = @json(old('command'));

            if (!moduleSelect || !commandSelect) {
                return;
            }

            function parseCommands(rawCommands) {
                if (!rawCommands) {
                    return [];
                }

                try {
                    const firstParse = JSON.parse(rawCommands);
                    return Array.isArray(firstParse) ? firstParse : JSON.parse(firstParse);
                } catch (e) {
                    return [];
                }
            }

            function refreshCommands() {
                commandSelect.innerHTML = '<option value="">{{ __('ui.common.select_command') }}</option>';
                const selectedOption = moduleSelect.options[moduleSelect.selectedIndex];
                const commands = parseCommands(selectedOption ? selectedOption.getAttribute('data-commands') : null);

                commands.forEach(function (command) {
                    const option = document.createElement('option');
                    option.value = command;
                    option.textContent = command;

                    if (String(command) === String(oldCommand)) {
                        option.selected = true;
                    }

                    commandSelect.appendChild(option);
                });
            }

            moduleSelect.addEventListener('change', refreshCommands);
            refreshCommands();
        });
    </script>
@endsection
