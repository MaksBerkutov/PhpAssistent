<div id="log-card" class="card mb-3 d-none">
    <div class="card-body">
        <h5 class="card-title">Настройки логирования</h5>
        <div class="mb-3">
            <label for="log_format" class="form-label">Формат строки лога</label>
            <input type="text" id="log_format" name="log_format"
                class="form-control @error('log_format') is-invalid @enderror"
                placeholder="Пример: Ключ: {key}, Значение: {value}"
                value="{{ old('log_format', isset($scenario)?optional($scenario->scenarioLog)->format:'') }}">
            @error('log_format')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
