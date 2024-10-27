<div id="api-card" class="card mb-3 d-none">
    <div class="card-body">
        <h5 class="card-title">Настройки отправки API</h5>
        <div class="mb-3">
            <label for="api_url" class="form-label">URL для API</label>
            <input type="text" id="api_url" name="api_url" class="form-control @error('api_url') is-invalid @enderror" placeholder="Введите URL" value="{{ old('api_url', isset($scenario)?optional($scenario->scenarioApi)->url:'') }}">
            @error('api_url')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="api_body" class="form-label">Формат запроса</label>
            <input type="text" id="api_body" name="api_body" class="form-control @error('api_body') is-invalid @enderror" placeholder="Пример: {key}: {value}" value="{{ old('api_body', isset($scenario)?optional($scenario->scenarioApi)->format:'') }}">
            @error('api_body')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
