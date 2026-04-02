<div id="api-card" class="card mb-3 d-none">
    <div class="card-body">
        <h5 class="card-title">Внешний API</h5>
        <p class="text-muted small mb-3">Отправка данных события в сторонний сервис.</p>

        <div class="mb-3">
            <label for="api_url" class="form-label">URL API</label>
            <input type="text" id="api_url" name="api_url" class="form-control @error('api_url') is-invalid @enderror" placeholder="https://example.com/webhook" value="{{ old('api_url', isset($scenario) ? optional($scenario->scenarioApi)->url : '') }}">
            @error('api_url')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-0">
            <label for="api_body" class="form-label">Формат payload</label>
            <input type="text" id="api_body" name="api_body" class="form-control @error('api_body') is-invalid @enderror" placeholder="Пример: {\"key\":\"{key}\",\"value\":\"{value}\"}" value="{{ old('api_body', isset($scenario) ? optional($scenario->scenarioApi)->format : '') }}">
            @error('api_body')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
