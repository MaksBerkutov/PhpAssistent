<div class="mb-3">
    <label for="{{$name}}" class="form-label">{{$label}}</label>
    <select id="{{$name}}" name="{{$name}}" class="form-select @error($name) is-invalid @enderror"
            required>
        <option value="">Выберите модуль</option>
        @foreach ($devices as $device)
            <option data-commands="{{ json_encode($device->command) }}" value="{{ $device->id }}" {{ old($name,$old) == $device->id ? 'selected' : '' }}>
                {{ $device->name }}

            <x-online-status status="{{$device->available}}"/></option>
        @endforeach
    </select>
    @error($name)
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
