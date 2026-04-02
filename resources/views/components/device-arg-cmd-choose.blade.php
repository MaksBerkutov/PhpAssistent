<div class="mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <input type="text" class="form-control @error($name) is-invalid @enderror" id="{{ $name }}" name="{{ $name }}" value="{{ old($name, $old ?? '') }}">
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
