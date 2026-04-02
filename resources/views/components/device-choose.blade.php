@php
    $selectedValue = old($name, $old ?? null);
@endphp

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <select id="{{ $name }}" name="{{ $name }}" class="form-select @error($name) is-invalid @enderror" required>
        <option value="">{{ __('ui.common.select_device') }}</option>
        @foreach ($devices as $device)
            <option
                data-commands="{{ json_encode($device->command) }}"
                value="{{ $device->id }}"
                @selected((string) $selectedValue === (string) $device->id)
            >
                {{ $device->available ? '●' : '○' }} {{ $device->name }}
            </option>
        @endforeach
    </select>
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
